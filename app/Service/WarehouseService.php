<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class WarehouseService extends BaseService
{
    /**
     * 添加仓库
     * @param $data
     * @return array|bool
     */
    public function addWarehouse(array $data) : bool
    {
        unset($data['ckbh_all']);
        $has = Warehouse::where('ckbh', $data['ckbh'])->count();
        if ($has > 0) {
            $this->error = '该仓库编号已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $warehouse = Warehouse::create($data);
        if (!$warehouse) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑仓库
     * @param $data
     * @return bool
     */
    public function editWarehouse(array $data) : bool
    {
        unset($data['ckbh_all']);
        $warehouse = Warehouse::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $warehouse->ckmc    = $data['ckmc'];
        $warehouse->ckwz    = $data['ckwz'];
        $re = $warehouse->save();
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
     * 删除仓库
     * @param $data
     * @return bool
     */
    public function delWarehouse(int $warehouseId) : bool
    {
        $warehouse = Warehouse::find($warehouseId);
        if (!$warehouse) {
            $this->error = '该仓库不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $warehouse->delete();
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