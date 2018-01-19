<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CompanyService extends BaseService
{
    /**
     * 添加单位
     * @param $data
     * @return array|bool
     */
    public function addCompany(array $data) : bool
    {
        $has = Company::where('dwdm', $data['dwdm'])->count();
        if ($has > 0) {
            $this->error = '该单位已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $company = Company::create($data);
        if (!$company) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑单位
     * @param $data
     * @return bool
     */
    public function editCompany(array $data) : bool
    {
        $has = Company::where('dwdm', $data['dwdm'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该单位已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $company = Company::find($data['id']);
        if($data['dwdm']=='100000') $data['dwjb']=1;
        // 手动开启事务
        DB::beginTransaction();
        $company->dwdm    = $data['dwdm'];
        $company->dwjc    = $data['dwjc'];
        $company->dwqc    = $data['dwqc'];
        $company->dwsx    = $data['dwsx'];
        $company->dwjb    = $data['dwjb'];
        $company->sjdm    = $data['sjdm'];
        $company->sjsx    = $data['sjsx'];
        $company->dwdz    = $data['dwdz'];
        $company->yzbm    = $data['yzbm'];
        $re1 = $company->save();
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
     * 删除单位
     * @param $data
     * @return bool
     */
    public function delCompany(int $companyId) : bool
    {
        $company = Company::find($companyId);
        if (!$company) {
            $this->error = '该单位不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $company->delete();
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