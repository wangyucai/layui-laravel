<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\ProfessionCarCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ProfessionCarCodeService extends BaseService
{
    /**
     * 添加职业资格证书代码
     * @param $data
     * @return array|bool
     */
    public function addCarCode(array $data) : bool
    {
        $has = ProfessionCarCode::where('car_code', $data['car_code'])->count();
        if ($has > 0) {
            $this->error = '该职业资格证书代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $professionCarCode = ProfessionCarCode::create($data);
        if (!$professionCarCode) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑职业资格证书代码
     * @param $data
     * @return bool
     */
    public function editCarCode(array $data) : bool
    {
        $has = ProfessionCarCode::where('car_code', $data['car_code'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该职业资格证书代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $professionCarCode = ProfessionCarCode::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $professionCarCode->car_code    = $data['car_code'];
        $professionCarCode->car_name    = $data['car_name'];
        $re = $professionCarCode->save();
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
     * 删除职业资格证书代码
     * @param $data
     * @return bool
     */
    public function delCarCode(int $carCodeId) : bool
    {
        $carCode = ProfessionCarCode::find($carCodeId);
        if (!$carCode) {
            $this->error = '该职业资格证书代码不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $carCode->delete();
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