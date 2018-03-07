<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\ProfessionCarModule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ProfessionCarModuleService extends BaseService
{
    /**
     * 添加职业资格证书模板
     * @param $data
     * @return array|bool
     */
    public function addCarModule(array $data) : bool
    {
        $has = ProfessionCarModule::where('zsbh', $data['zsbh'])->orwhere('zsmc',$data['zsmc'])->count();
        if ($has > 0) {
            $this->error = '该职业资格证书模板已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $data['ywlb'] = implode(',', $data['ywlb']);
        DB::beginTransaction();
        $professionCarModule = ProfessionCarModule::create($data);
        if (!$professionCarModule) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑职业资格证书模板
     * @param $data
     * @return bool
     */
    public function editCarModule(array $data) : bool
    {
        $professionCarModule = ProfessionCarModule::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();

        $professionCarModule->zsmc        = $data['zsmc'];
        $professionCarModule->zsbh        = $data['zsbh'];
        $professionCarModule->ywlb        = implode(',', $data['ywlb']);
        $professionCarModule->fzjg        = $data['fzjg'];
        $professionCarModule->zsyxq       = $data['zsyxq'];
        $professionCarModule->bz          = $data['bz'];

        $re = $professionCarModule->save();
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