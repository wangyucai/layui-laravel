<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Train;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TrainService extends BaseService
{
    /**
     * 添加培训信息
     * @param $data
     * @return array|bool
     */
    public function addTrain(array $data) : bool
    {
        unset($data['token']);
        if($data['px_end_time']<time()) $data['if_expire'] = 1;
        $data['px_time'] = $data['px_end_time'] - $data['px_start_time'];
        $data['px_start_time'] = strtotime($data['px_start_time']);
        $data['px_end_time'] = strtotime($data['px_end_time']);
        $data['px_notice_dw'] = serialize(explode(',', $data['px_notice_dw']));
        $data['uid']          = Auth::guard('admin')->user()->id;
        $data['px_dwdm']      = Auth::guard('admin')->user()->company_dwdm;
        // 查询主办单位表
        $dw = DB::table('host_unit')->where('name', '=', $data['zbdw_id'])->first();
        if (empty($dw)) {
            $zbdw['name'] = $data['zbdw_id'];
            $data['zbdw_id'] = DB::table('host_unit')->insertGetId($zbdw);
        } else {
            $data['zbdw_id'] = $dw->id;
        }
        DB::beginTransaction();
        $train = Train::create($data);
        if (!$train) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑培训信息
     * @param $data
     * @return bool
     */
    public function editTrain(array $data) : bool
    {
        $train = Train::find($data['px_id']);
        $px_start_time = strtotime($data['px_start_time']);
        $px_end_time = strtotime($data['px_end_time']);
        if($px_end_time<time()) $data['if_expire'] = 1;
            $data['if_expire'] = 0;
        $data['px_time'] = $px_end_time - $px_start_time;
        // 手动开启事务
        DB::beginTransaction();
        $train->px_title         = $data['px_title'];
        $train->px_place         = $data['px_place'];
        $train->px_start_time    = $px_start_time;
        $train->px_end_time      = $px_end_time;
        $train->if_expire        = $data['if_expire'];
        $train->px_time          = $data['px_time'];
        $train->px_fx            = $data['px_fx'];
        $train->px_renshu        = $data['px_renshu'];
        $train->px_notice_dw     = serialize(explode(',', $data['px_notice_dw']));
        // 查询主办单位表
        $dw = DB::table('host_unit')->where('name', '=', $data['zbdw_id'])->first();
        if (empty($dw)) {
            $zbdw['name'] = $data['zbdw_id'];
            $train->zbdw_id = DB::table('host_unit')->insertGetId($zbdw);
        } else {
            $train->zbdw_id = $dw->id;
        }
        $re = $train->save();
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
     * 删除培训信息
     * @param $data
     * @return bool
     */
    public function delTrain(int $trainId) : bool
    {
        $train = Train::find($trainId);
        if (!$train) {
            $this->error = '该培训信息不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $train->delete();
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