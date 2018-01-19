<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Identifyinfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class IdentifyinfoService extends BaseService
{
    /**
     * 完善鉴定人员信息(添加我的证书)
     * @param $data
     * @return bool
     */
    public function completeIdentifyInfo(array $data) : bool
    {
        $has = Identifyinfo::where('admin_id',$data['user_id'])->where('jdywfw_code', $data['jdywfw_code'])->count();
        if ($has > 0) {
            $this->error = '属于该鉴定业务范围的证书已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        unset($data['file']);
        unset($data['user_id']);
        $data['jdry_fzrq'] = strtotime($data['jdry_fzrq']);
        $data['jdry_yxrq'] = strtotime($data['jdry_yxrq']);
        // 手动开启事务
        DB::beginTransaction();
        $re = Identifyinfo::create($data);
        if (!$re) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            // 事务回滚
            DB::rollBack();
            return false;
        }
        // 提交
        DB::commit(); 
        return true;
    }
    
     /**
     * 编辑我的证书
     * @param $data
     * @return bool
     */
    public function editIdentifyInfo(array $data) : bool
    {
        $identifyinfo = Identifyinfo::find($data['id']);
        unset($data['file']);
        // 手动开启事务
        DB::beginTransaction();
        $identifyinfo->jdry_name        = $data['jdry_name'];
        $identifyinfo->jdry_zsbh        = $data['jdry_zsbh'];
        $identifyinfo->jdywfw_code      = $data['jdywfw_code'];
        $identifyinfo->jdry_fzdw        = $data['jdry_fzdw'];
        $identifyinfo->jdry_fzrq        = strtotime($data['jdry_fzrq']);
        $identifyinfo->jdry_yxrq        = strtotime($data['jdry_yxrq']);
        $identifyinfo->jdjg_dwdm        = $data['jdjg_dwdm'];
        if(isset($data['jdry_zspath'])){
            $identifyinfo->jdry_zspath = $data['jdry_zspath'];
        }
        $re = $identifyinfo->save();
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
     * 删除我的证书
     * @param $data
     * @return bool
     */
    public function delIdentifyInfo(int $identifyInfoId) : bool
    {
        $identifyinfo = Identifyinfo::find($identifyInfoId);
        if (!$identifyinfo) {
            $this->error = '该鉴定人员信息证书不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $identifyinfo->delete();
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