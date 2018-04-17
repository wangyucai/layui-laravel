<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\EquipmentAsset;
use App\Model\Inventory;
use App\Model\DeviceIdentity;
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
        $equipmentAsset->zcxz        = $data['zcxz'];
        $equipmentAsset->cd          = $data['cd'];
        $equipmentAsset->bfnx        = $data['bfnx'];

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
    /**
     * 装备资产入库
     * @param $data
     * @return array|bool
     */
    public function addInbound(array $data) : bool
    {
        $data['kc_rkrq'] = strtotime($data['kc_rkrq']);
        $data['kc_ynums'] = $data['kc_nums'];
        $has = Inventory::where('kc_zcid', $data['kc_zcid'])->count();
        if ($has > 0) {
            $this->error = '该装备资产已入库,不需要在进行此操作';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        unset($data['file']);
        if(isset($data['file_info'])){
            foreach ($data['file_info'] as $k => $v) {    
                $data['info_zspath'][] = $v;
            }
            unset($data['file_info']);
            $data['info_zspath'] = serialize($data['info_zspath']);
        }
        // 获取资产编号
        $zcbh = EquipmentAsset::where('id', $data['kc_zcid'])->value('zcbh');
        DB::beginTransaction();
        // 装备资产入库
        $inventory = Inventory::create($data);
        // 库存id
        $kc_id = $inventory->id;
        $time =  \Carbon\Carbon::now()->toDateTimeString();
        for ($i=1; $i <=$data['kc_nums'] ; $i++) { 
            $count[] = array('sbsf_zcbh' => $zcbh, 'sbsf_kcid' => $kc_id,'sbsf_xh'=>$i,'created_at' => $time,'updated_at' => $time);
        }
        // 设备身份入库
        $deviceIdentity = DeviceIdentity::insert($count);
        if (!$inventory || !$deviceIdentity) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
}