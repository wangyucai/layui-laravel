<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\EquipmentAsset;
use App\Model\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Auth;

class InventoryService extends BaseService
{
    /**
     * 编辑入库资产
     * @param $data
     * @return bool
     */
    public function editInboundAsset(array $data) : bool
    {
        $data['kc_rkrq'] = strtotime($data['kc_rkrq']);
        $inventory = Inventory::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        
        $inventory->kc_ckid        = $data['kc_ckid'];
        $inventory->kc_nums        = $data['kc_nums'];
        $inventory->kc_zczk        = $data['kc_zczk'];
        $inventory->kc_qryj        = $data['kc_qryj'];
        $inventory->kc_rkrq        = $data['kc_rkrq'];

        $re = $inventory->save();
        if ($re === false) {
            $this->error = '修改失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 删除入库资产
     * @param $data
     * @return bool
     */
    public function delInboundAsset(int $inventoryId) : bool
    {
        $inventory = Inventory::find($inventoryId);
        if (!$inventory) {
            $this->error = '该入库资产不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $inventory->delete();
        if ($re === false) {
            $this->error = '删除失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 审核反馈信息给入库资产的用户
     * @param $data
     * @return bool
     */
    public function checkInboundAsset(array $data) : bool
    {
        $my_id = Auth::guard('admin')->user()->id;
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        $time = \Carbon\Carbon::now()->toDateTimeString();
        DB::beginTransaction();
        $has = DB::table('prompt_messages')
                        ->where([['receiver_id', '=', $data['kc_uid']],['msg_type', '=', '4'],['type_id', '=', $data['id']]])
                        ->count();
        if($has){
            $this->error = '该入库资产已成功反馈信息，无需审核';
            $this->httpCode = HttpCode::NOT_FOUND;
            return false;
        }   
        $msg_arr = ['receiver_id' => $data['kc_uid'], 'sender_id' => $my_id, 
                    'sender_dwdm' => $my_dwdm, 'msg_type' => 4, 
                    'msg_content' => $data['feedback_msg'], 'type_id' => $data['id'],
                    'created_at' => $time, 'updated_at' => $time];
        $re = DB::table('prompt_messages')->insert($msg_arr);
        if($data['check_result']==1){// 审核通过
            $re1 = DB::table('inventories')
                     ->where('id', $data['id'])
                     ->update(array('if_check' => 1, 'check_time'=>time()));
        }elseif($data['check_result']==2){//审核不通过
            $re1 = DB::table('inventories')
                     ->where('id', $data['id'])
                     ->update(array('if_check' => 2, 'check_time'=>time()));
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
}