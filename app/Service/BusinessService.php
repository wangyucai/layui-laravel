<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Business;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class BusinessService extends BaseService
{
    /**
     * 添加司法鉴定业务范围
     * @param $data
     * @return array|bool
     */
    public function addBusiness(array $data) : bool
    {
        $has = Business::where('jdywfw_code', $data['jdywfw_code'])->count();
        if ($has > 0) {
            $this->error = '该司法鉴定业务范围代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $has = Business::where('jdywfw_name', $data['jdywfw_name'])->count();
        if ($has > 0) {
            $this->error = '该司法鉴定业务范围名称已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $business = Business::create($data);
        if (!$business) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑司法鉴定业务范围
     * @param $data
     * @return bool
     */
    public function editBusiness(array $data) : bool
    {
        $has = Business::where('jdywfw_name', $data['jdywfw_name'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该司法鉴定机构名称已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $business = Business::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $business->jdywfw_name = $data['jdywfw_name'];
        $re = $business->save();
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
     * 删除司法鉴定业务范围
     * @param $data
     * @return bool
     */
    public function delBusiness(int $businessId) : bool
    {
        $business = Business::find($businessId);
        if (!$business) {
            $this->error = '该司法鉴定业务范围不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $business->delete();
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