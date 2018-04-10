<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\CertificateBid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;

class CertificateBidService extends BaseService
{
    /**
     * 申报职业资格证书
     * @param $data
     * @return array|bool
     */
    public function addCarBid(array $data) : bool
    {
        if (empty($data['ywlb'])) {
            $this->error = '请选择业务类别';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $if_check = CertificateBid::where('zsmc', $data['zsmc'])->where('ywlb',$data['ywlb'])->where('my_name',$data['my_name'])->pluck('if_check')->toArray();
        if (in_array(1, $if_check)) {
            $this->error = '该职业资格证书您已申请，正在审核中！';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }elseif (in_array(2, $if_check)) {
            $this->error = '该职业资格证书已审核通过，请勿重复申请！';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $data['if_check'] = 1;
        $data['ywlb'] = implode(',', $data['ywlb']);
        DB::beginTransaction();
        $certificateBid = CertificateBid::create($data);
        if (!$certificateBid) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 编辑申办的职业资格证书模板
     * @param $data
     * @return bool
     */
    public function editCertificate(array $data) : bool
    {
        $certificateBid = CertificateBid::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $certificateBid->ywlb        = implode(',', $data['ywlb']);
        $certificateBid->fzjg        = $data['fzjg'];
        $re = $certificateBid->save();
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
     * 把申报的证书上报
     * @param $data
     * @return bool
     */
    public function reportingCertificate(array $data) : bool
    {
        // 判断登录管理员账号的单位级别
        if($data['admin_dwjb']==3){// 市级admin 上报
            $re = DB::table('certificate_bids')
                     ->where('id', $data['id'])
                     ->update(array('city_if_check' => 1));
        }elseif($data['admin_dwjb']==4){// 县级admin 上报
            $re = DB::table('certificate_bids')
                     ->where('id', $data['id'])
                     ->update(array('county_if_check' => 1));
        }    
        if ($re === false) {
            $this->error = '上报失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        return true;
    }
    
    /**
     * 反馈信息给申报证书的用户
     * @param $data
     * @return bool
     */
    public function checkCertificate(array $data) : bool
    {
        $my_id = Auth::guard('admin')->user()->id;
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        $time = \Carbon\Carbon::now()->toDateTimeString();
        DB::beginTransaction();
        $has = DB::table('prompt_messages')
                        ->where([['receiver_id', '=', $data['id']],['msg_type', '=', '2'],['zyzs_id', '=', $data['id']]])
                        ->count();
        if($has){
            $this->error = $data['real_name'].'的'.$data['zsmc'].'已成功反馈信息，无需审核';
            $this->httpCode = HttpCode::NOT_FOUND;
            return false;
        }   
        $msg_arr = ['receiver_id' => $data['user_id'], 'sender_id' => $my_id, 
                    'sender_dwdm' => $my_dwdm, 'msg_type' => 2, 
                    'msg_content' => $data['feedback_msg'], 'zyzs_id' => $data['id'],
                    'created_at' => $time, 'updated_at' => $time];
        $re = DB::table('prompt_messages')->insert($msg_arr);
        if($data['check_result']==2){// 审核通过
            $re1 = DB::table('certificate_bids')
                     ->where('id', $data['id'])
                     ->update(array('if_check' => 2, 'check_time'=>time()));
        }elseif($data['check_result']==3){//审核不通过
            $re1 = DB::table('certificate_bids')
                     ->where('id', $data['id'])
                     ->update(array('if_check' => 3, 'check_time'=>time()));
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
     * 删除职业资格证书模板
     * @param $data
     * @return bool
     */
    public function delCarModule(int $professionCarModuleId) : bool
    {
        $professionCarModule = ProfessionCarModule::find($professionCarModuleId);
        if (!$professionCarModule) {
            $this->error = '该职业资格证书模板不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $professionCarModule->delete();
        if ($re === false) {
            $this->error = '删除失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
}