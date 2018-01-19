<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\NoticeType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class NoticeTypeService extends BaseService
{
    /**
     * 添加通知类型
     * @param $data
     * @return array|bool
     */
    public function addNoticeType(array $data) : bool
    {
        $has = NoticeType::where('notice_type_code', $data['notice_type_code'])->count();
        if ($has > 0) {
            $this->error = '该培训方向代码已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        DB::beginTransaction();
        $noticeType = NoticeType::create($data);
        if (!$noticeType) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * 编辑通知类型
     * @param $data
     * @return bool
     */
    public function editNoticeType(array $data) : bool
    {
        $has = NoticeType::where('notice_type_code', $data['notice_type_code'])->where('id', '!=', $data['id'])->count();
        if ($has > 0) {
            $this->error = '该通知类型已存在';
            $this->httpCode = HttpCode::CONFLICT;
            return false;
        }
        $noticeType = NoticeType::find($data['id']);
        // 手动开启事务
        DB::beginTransaction();
        $noticeType->notice_type_code    = $data['notice_type_code'];
        $noticeType->notice_type_name    = $data['notice_type_name'];
        $re = $noticeType->save();
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
     * 删除通知类型
     * @param $data
     * @return bool
     */
    public function delNoticeType(int $noticeTypeId) : bool
    {
        $noticeType = NoticeType::find($noticeTypeId);
        if (!$noticeType) {
            $this->error = '该通知类型不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $noticeType->delete();
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