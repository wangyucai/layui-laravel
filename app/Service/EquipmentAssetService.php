<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\EquipmentAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class EquipmentAssetService extends BaseService
{
    /**
     * 添加装备资产
     * @param $data
     * @return array|bool
     */
    public function addEquipmentAsset(array $data) : bool
    {
        unset($data['zcbh_all']);
        $has = EquipmentAsset::where('zcbh', $data['zcbh'])->count();
        if ($has > 0) {
            $this->error = '该装备资产已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $equipmentAsset = EquipmentAsset::create($data);
        if (!$equipmentAsset) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑装备资产
     * @param $data
     * @return bool
     */
    public function editEquipmentAsset(array $data) : bool
    {
        unset($data['zcbh_all']);
        $equipmentAsset = EquipmentAsset::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();

        $equipmentAsset->zcmc        = $data['zcmc'];
        $equipmentAsset->zcpp        = $data['zcpp'];
        $equipmentAsset->zcxh        = $data['zcxh'];
        $equipmentAsset->zcdw        = $data['zcdw'];
        $equipmentAsset->zcxz       = $data['zcxz'];
        $equipmentAsset->cd          = $data['cd'];

        $re = $equipmentAsset->save();
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
     * 删除装备资产
     * @param $data
     * @return bool
     */
    public function delEquipmentAsset(int $equipmentAssetId) : bool
    {
        $equipmentAsset = EquipmentAsset::find($equipmentAssetId);
        if (!$equipmentAsset) {
            $this->error = '该装备资产不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $equipmentAsset->delete();
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