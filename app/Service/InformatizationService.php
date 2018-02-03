<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Informatization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class InformatizationService extends BaseService
{
    /**
     * 添加我的信息化资格证书
     * @param $data
     * @return bool
     */
    public function addInformatization(array $data) : bool
    {
        $has = Informatization::where('user_myid',$data['user_myid'])->where('info_zsbh', $data['info_zsbh'])->count();
        if ($has > 0) {
            $this->error = '该信息化资格证书编号已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $has = Informatization::where('user_myid',$data['user_myid'])->where('info_zsmc', $data['info_zsmc'])->count();
        if ($has > 0) {
            $this->error = '该信息化资格证书名称已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        unset($data['file']);
        foreach ($data['file_info'] as $k => $v) {    
            $data['info_zspath'][] = $v;
        }
        unset($data['file_info']);
        $data['info_zspath'] = serialize($data['info_zspath']);
        $data['info_fzrq'] = strtotime($data['info_fzrq']);
        // 手动开启事务
        DB::beginTransaction();
        $re = Informatization::create($data);
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
     * 编辑我的信息化资格证书
     * @param $data
     * @return bool
     */
    public function editInformatization(array $data) : bool
    {
        $informatization = Informatization::find($data['id']);
        unset($data['file']);
        // 手动开启事务
        DB::beginTransaction();
        $informatization->info_zsbh        = $data['info_zsbh'];
        $informatization->info_zsmc        = $data['info_zsmc'];
        $informatization->info_bzjg        = $data['info_bzjg'];
        $informatization->info_fzrq        = strtotime($data['info_fzrq']);
        if(isset($data['info_zspath'])){
            $identifyinfo->info_zspath = $data['info_zspath'];
        }
        $re = $informatization->save();
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
    public function delInformatization(int $informatizationId) : bool
    {
        $informatization = Informatization::find($informatizationId);
        if (!$informatization) {
            $this->error = '该信息化资格证书不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $informatization->delete();
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