<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\InstitutionCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class InstitutionCodeService extends BaseService
{
    /**
     * 添加司法鉴定机构代码
     * @param $data
     * @return array|bool
     */
    public function addInstitutionCode(array $data) : bool
    {
        $has = InstitutionCode::where('jdjg_code', $data['jdjg_code'])->count();
        if ($has > 0) {
            $this->error = '该司法鉴定机构代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $has = InstitutionCode::where('jdjg_name', $data['jdjg_name'])->count();
        if ($has > 0) {
            $this->error = '该司法鉴定机构名称已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $institutioncode = InstitutionCode::create($data);
        if (!$institutioncode) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑司法鉴定机构代码
     * @param $data
     * @return bool
     */
    public function editInstitutionCode(array $data) : bool
    {
        $has = InstitutionCode::where('jdjg_name', $data['jdjg_name'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该司法鉴定机构名称已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $institutioncode = InstitutionCode::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $institutioncode->jdjg_dwdm     = $data['jdjg_dwdm'];
        $institutioncode->jdjg_name     = $data['jdjg_name'];
        $institutioncode->fj_jdjg_code  = $data['fj_jdjg_code'];
        $institutioncode->jdjg_level    = $data['jdjg_level'];
        $re1 = $institutioncode->save();
        if ($re1 === false) {
            $this->error = '修改失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 删除司法鉴定机构代码
     * @param $data
     * @return bool
     */
    public function delInstitutionCode(int $institutionCodeId) : bool
    {
        $institutioncode = InstitutionCode::find($institutionCodeId);
        if (!$institutioncode) {
            $this->error = '该司法鉴定机构代码不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $institutioncode->delete();
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