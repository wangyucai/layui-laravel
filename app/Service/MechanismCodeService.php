<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\MechanismCode;
use App\Model\Company;
use App\Model\Mechanism;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MechanismCodeService extends BaseService
{
    /**
     * 添加内设机构代码
     * @param $data
     * @return array|bool
     */
    public function addMechanismCode(array $data) : bool
    {
        $has = MechanismCode::where('code', $data['code'])->count();
        if ($has > 0) {
            $this->error = '该内设机构代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        // 获取全部单位
        $companies = Company::where('dwdm','!=','100000')->get()->toArray();
        foreach ($companies as $key => $company) {
            $dwdm = $company['dwdm'];
            $nsjgxzjb = $company['dwjb'];
            Mechanism::create(['company_dwdm' => $dwdm, 'nsjgxzjb' => $nsjgxzjb, 'mechanism_code' => $data['code'], 'nsjgmc' => $data['code_name']]);
        }
        DB::beginTransaction();
        $mechanismCode = MechanismCode::create($data);
        if (!$mechanismCode) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑内设机构代码
     * @param $data
     * @return bool
     */
    public function editMechanismCode(array $data) : bool
    {
        $has = MechanismCode::where('code', $data['code'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该内设机构代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $mechanismcode = MechanismCode::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $mechanismcode->code         = $data['code'];
        $mechanismcode->code_name    = $data['code_name'];
        $re = $mechanismcode->save();
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
     * 删除内设机构代码
     * @param $data
     * @return bool
     */
    public function delMechanismCode(int $mechanismCodeId) : bool
    {
        $mechanismCode = MechanismCode::find($mechanismCodeId);
        if (!$mechanismCode) {
            $this->error = '该内设机构代码不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $mechanismCode->delete();
        $re1 = Mechanism::where('mechanism_code', $mechanismCode->code)->delete();
        if ($re === false || $re1 === false) {
            $this->error = '删除失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
}