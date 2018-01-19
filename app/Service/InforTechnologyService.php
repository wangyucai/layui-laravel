<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\InforTechnology;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class InforTechnologyService extends BaseService
{
    /**
     * 添加信息化技术代码
     * @param $data
     * @return array|bool
     */
    public function addInfortech(array $data) : bool
    {
        $has = InforTechnology::where('xxhjs_code', $data['xxhjs_code'])->count();
        if ($has > 0) {
            $this->error = '该信息化技术代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $inforTechnology = InforTechnology::create($data);
        if (!$inforTechnology) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑信息化技术代码
     * @param $data
     * @return bool
     */
    public function editInfortech(array $data) : bool
    {
        $has = InforTechnology::where('xxhjs_code', $data['xxhjs_code'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该信息化技术代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $inforTechnology = InforTechnology::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $inforTechnology->xxhjs_code    = $data['xxhjs_code'];
        $inforTechnology->xxhjs_name    = $data['xxhjs_name'];
        $re = $inforTechnology->save();
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
    public function delInfortech(int $inforTechId) : bool
    {
        $inforTechnology = InforTechnology::find($inforTechId);
        if (!$inforTechnology) {
            $this->error = '该信息化技术代码不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $inforTechnology->delete();
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