<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\FixedAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class FixedAssetService extends BaseService
{
    /**
     * 添加我的固定资产
     * @param $data
     * @return array|bool
     */
    public function addMyFixedAsset(array $data) : bool
    {
        unset($data['file']);
        unset($data['gdzc_bh_all']);
        $data['gdzc_lqrq'] = strtotime($data['gdzc_lqrq']);
        $has = FixedAsset::where('gdzc_bh', $data['gdzc_bh'])->count();
        if ($has > 0) {
            $this->error = '该固定资产已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $myFixedAsset = FixedAsset::create($data);
        if (!$myFixedAsset) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑我的固定资产
     * @param $data
     * @return bool
     */
    public function editMyFixedAsset(array $data) : bool
    {
        $myFixedAsset = FixedAsset::find($data['id']);
        unset($data['gdzc_bh_all']);
        unset($data['file']);
        // 手动开启事务
        DB::beginTransaction();
        $myFixedAsset->gdzc_mc          = $data['gdzc_mc'];
        $myFixedAsset->gdzc_pp          = $data['gdzc_pp'];
        $myFixedAsset->gdzc_xh          = $data['gdzc_xh'];
        $myFixedAsset->gdzc_ffbm        = $data['gdzc_ffbm'];
        $myFixedAsset->price            = $data['price'];
        $myFixedAsset->gdzc_nums        = $data['gdzc_nums'];
        $myFixedAsset->gdzc_bz          = $data['gdzc_bz'];
        $myFixedAsset->gdzc_lqrq        = strtotime($data['gdzc_lqrq']);
        if(isset($data['gdzc_pic'])){
            $myFixedAsset->gdzc_pic = $data['gdzc_pic'];
        }
        $re = $myFixedAsset->save();
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
     * 删除我的固定资产
     * @param $data
     * @return bool
     */
    public function delMyFixedAsset(int $myFixedAssetId) : bool
    {
        $myFixedAsset = FixedAsset::find($myFixedAssetId);
        if (!$myFixedAsset) {
            $this->error = '该固定资产不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $myFixedAsset->delete();
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