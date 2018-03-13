<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\AssetUnit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class AssetUnitService extends BaseService
{
    /**
     * 添加资产单位代码
     * @param $data
     * @return array|bool
     */
    public function addAssetUnit(array $data) : bool
    {
        $has = AssetUnit::where('zcdw_code', $data['zcdw_code'])->count();
        if ($has > 0) {
            $this->error = '该资产单位代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $assetUnit = AssetUnit::create($data);
        if (!$assetUnit) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑资产单位代码
     * @param $data
     * @return bool
     */
    public function editAssetUnit(array $data) : bool
    {
        $has = AssetUnit::where('zcdw_name', $data['zcdw_name'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该资产单位名称已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $assetUnit = AssetUnit::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $assetUnit->zcdw_code    = $data['zcdw_code'];
        $assetUnit->zcdw_name    = $data['zcdw_name'];
        $re = $assetUnit->save();
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
    public function delAssetUnit(int $assetUnitId) : bool
    {
        $assetUnit = AssetUnit::find($assetUnitId);
        if (!$assetUnit) {
            $this->error = '该资产单位代码不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $assetUnit->delete();
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