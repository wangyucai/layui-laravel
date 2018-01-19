<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Mechanism;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MechanismService extends BaseService
{
    /**
     * 添加本单位内设机构
     * @param $data
     * @return array|bool
     */
    public function addMyMechanismCode(array $data) : bool
    {
        // $has = Mechanism::where('code', $data['code'])->count();
        // if ($has > 0) {
        //     $this->error = '该本单位内设机构代码已存在';
        //     $this->httpCode = HttpCode::CONFLICT;
        //     return false;
        // }
        DB::beginTransaction();
        $mechanism = Mechanism::create($data);
        if (!$mechanism) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑本单位内设机构
     * @param $data
     * @return bool
     */
    public function editMyMechanismCode(array $data) : bool
    {
        // $has = Mechanism::where('code', $data['code'])->where('id', '!=', $data['id'])->count();
        // if ($has > 0) {
        //     $this->error = '该内设机构代码已存在';
        //     $this->httpCode = HttpCode::CONFLICT;
        //     return false;
        // }
        $mechanism = Mechanism::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $mechanism->nsjgmc    = $data['nsjgmc'];
        $re = $mechanism->save();
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
     * 删除本单位内设机构
     * @param $data
     * @return bool
     */
    public function delMyMechanismCode(int $mechanismId) : bool
    {
        $mechanism = Mechanism::find($mechanismId);
        if (!$mechanism) {
            $this->error = '该内设机构不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $mechanism->delete();
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