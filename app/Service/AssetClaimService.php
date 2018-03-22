<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\AssetClaim;
use App\Model\Inventory;
use App\Model\DeviceIdentity;
use App\Model\EquipmentAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Auth;

class AssetClaimService extends BaseService
{
    /**
     * 申领装备资产
     * @param $data
     * @return array|bool
     */
    public function addAssetClaim(array $data) : bool
    {
        // 根据资产id 查询资产编号
        $zcbh = EquipmentAsset::where('id',$data['ly_zcid'])->value('zcbh');
        // 获取申领的数量
        $ly_nums = $data['ly_nums'];
         // 获取该设备信息
        $deviceIdentities = DeviceIdentity::where('sbsf_zcbh',$zcbh)->where('if_ck',0)->where('if_bf',0)->limit($ly_nums)->pluck('id');
        foreach ($deviceIdentities as $k => $v) {
            // 更新该条设备信息出库
            DeviceIdentity::where('id',$v)->update(['if_ck' => 1]);
            // 添加到用户持有资产表
            $count[] = array('user_id' => $data['ly_uid'], 'zc_id' => $data['ly_zcid'],'sbsf_id'=>$v,'add_time' => time());
        }
        
        $data['lyrq'] = strtotime($data['lyrq']);
        // 库存总量-申领数量=剩余库存量
        $sy_nums = $data['kc_nums']-$ly_nums;
        unset($data['kc_nums']);
        DB::beginTransaction();
        // 添加到用户持有资产表
        $userReceive = DB::table('user_receive')->insert($count);

        $assetClaim = AssetClaim::create($data);
        // 更新库存量
        $inventory = Inventory::where('kc_zcid', $data['ly_zcid'])->update(['kc_nums' => $sy_nums]);
        if (!$assetClaim || !$inventory || !$userReceive) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 反馈信息给已申领物品的用户
     * @param $data
     * @return bool
     */
    public function checkAssetclaim(array $data) : bool
    {
        $my_id = Auth::guard('admin')->user()->id;
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        $time = \Carbon\Carbon::now()->toDateTimeString();
        DB::beginTransaction();
        $has = DB::table('prompt_messages')
                        ->where([['receiver_id', '=', $data['ly_uid']],['msg_type', '=', '3'],['type_id', '=', $data['id']]])
                        ->count();
        if($has){
            $this->error = $data['real_name'].'申领数量为'.$data['ly_nums'].'的'.$data['zcpp'].$data['zcmc'].'已成功反馈信息，无需审核';
            $this->httpCode = HttpCode::NOT_FOUND;
            return false;
        }   
        $msg_arr = ['receiver_id' => $data['ly_uid'], 'sender_id' => $my_id, 
                    'sender_dwdm' => $my_dwdm, 'msg_type' => 3, 
                    'msg_content' => $data['feedback_msg'], 'type_id' => $data['id'],
                    'created_at' => $time, 'updated_at' => $time];
        $re = DB::table('prompt_messages')->insert($msg_arr);
        if($data['check_result']==1){// 审核通过
            $re1 = AssetClaim::where('ly_zcid', $data['id'])->update(['if_check' => 1,'check_time'=>time()]);
        }elseif($data['check_result']==2){//审核不通过
            $re1 = AssetClaim::where('ly_zcid', $data['id'])->update(['if_check' => 2,'check_time'=>time()]);
        }
        if ($re === false || $re1 === false) {
            $this->error = '信息反馈失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 编辑设备身份
     * @param $data
     * @return bool
     */
    public function editDeviceIdentity(array $data) : bool
    {
        unset($data['sbsf_xh']);
        $deviceIdentity = DeviceIdentity::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $deviceIdentity->sbsf_bz        = $data['sbsf_bz'];
        $re = $deviceIdentity->save();
        if ($re === false) {
            $this->error = '修改失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
}