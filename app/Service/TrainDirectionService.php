<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\TrainDirection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TrainDirectionService extends BaseService
{
    /**
     * 添加培训方向代码
     * @param $data
     * @return array|bool
     */
    public function addTrainDirection(array $data) : bool
    {
        $has = TrainDirection::where('pxfx_code', $data['pxfx_code'])->count();
        if ($has > 0) {
            $this->error = '该培训方向代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $trainDirection = TrainDirection::create($data);
        if (!$trainDirection) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑培训方向代码
     * @param $data
     * @return bool
     */
    public function editTrainDirection(array $data) : bool
    {
        $has = TrainDirection::where('pxfx_code', $data['pxfx_code'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该培训方向代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $trainDirection = TrainDirection::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $trainDirection->pxfx_code    = $data['pxfx_code'];
        $trainDirection->pxfx_name    = $data['pxfx_name'];
        $re = $trainDirection->save();
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
     * 删除培训方向代码
     * @param $data
     * @return bool
     */
    public function delTrainDirection(int $trainDirectionId) : bool
    {
        $trainDirection = InforTechnology::find($trainDirectionId);
        if (!$trainDirection) {
            $this->error = '该培训方向代码不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $trainDirection->delete();
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