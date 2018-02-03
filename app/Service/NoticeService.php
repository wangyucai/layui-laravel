<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Notice;
use App\Model\Admin;
use App\Model\Train;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class NoticeService extends BaseService
{
    /**
     * 添加通知
     * @param $data
     * @return array|bool
     */
    public function addNotice(array $data) : bool
    {
        $data['notice_yxq'] = strtotime($data['notice_yxq']);
        $data['notice_dwdm'] = serialize(explode(',', $data['notice_dwdm']));
        unset($data['file']);
        unset($data['token']);
        DB::beginTransaction();
        if($data['px_id']){
            Train::where('id', $data['px_id'])->update(['if_notice' => 1]);
        }
        $notice = Notice::create($data);
        // 分发任务--队列实现
        // dispatch(new \App\Jobs\SendMessage($notice));
        // 非队列实现
        $this->sendNotices($notice);
        if (!$notice) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 发送通知给指定单位用户
     * @param $data
     * @return array|bool
     */
    public function sendNotices($notice)
    {
        // 获取通知发送给哪个单位级别的人
        $notice_dwdm = $notice->notice_dwdm;
        $notice_dwdm_arr = unserialize($notice_dwdm);
        // 通知用户系统消息
        $users = Admin::whereIn('company_dwdm',$notice_dwdm_arr)->where('tel_hm','!=','')->get();
        foreach($users as $user){
            $user->addNotice($notice);
            // 每发送一条通知就给通知的用户未读通知书+1
            $no_notice_count = $user->no_notice_count+1;
            DB::update("update gzjcy_admins set no_notice_count = '{$no_notice_count}' where id = '{$user->id}'");
        }
    }
    /**
     * 编辑通知
     * @param $data
     * @return bool
     */
    public function editNotice(array $data) : bool
    {
        $data['notice_yxq'] = strtotime($data['notice_yxq']);
        $data['notice_dwdm'] = serialize(explode(',', $data['notice_dwdm']));
        unset($data['file']);
        $notice = Notice::find($data['id']);
        if($notice->px_id){
            Train::where('id', $notice->px_id)->update(['if_notice' => 1]);
        }
        $notice_dwdm_arr = unserialize($notice->notice_dwdm);
        // 手动开启事务
        DB::beginTransaction();
        $notice->title          = $data['title'];
        $notice->type           = $data['type'];
        $notice->content        = $data['content'];
        if($data['type'] == '02')   $notice->content2  = $data['content2'];
        $notice->from_dw        = $data['from_dw'];
        $notice->notice_dwdm    = $data['notice_dwdm'];
        $notice->from_dwdm      = $data['from_dwdm'];
        if(isset($data['attachment'])){
            $notice->attachment = $data['attachment'];
        }
        $this->delNotices($notice,$notice_dwdm_arr);
        $this->sendNotices($notice);
        $re = $notice->save();   
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
     * 删除指定单位用户的通知
     * @param $data
     * @return array|bool
     */
    public function delNotices($notice, $notice_dwdm_arr)
    {
        // 通知用户系统消息
        $users = Admin::whereIn('company_dwdm',$notice_dwdm_arr)->where('tel_hm','!=','')->get();

        foreach($users as $user){
            $user->deleteNotice($notice);
            $no_notice_count = $user->no_notice_count-1;
            DB::update("update gzjcy_admins set no_notice_count = '{$no_notice_count}' where id = '{$user->id}'");
        }
    }
    /**
     * 删除通知
     * @param $data
     * @return bool
     */
    public function delNotice(int $noticeId) : bool
    {
        $notice = Notice::find($noticeId);
        if($notice->px_id){
            Train::where('id', $notice->px_id)->update(['if_notice' => 0]);
        }
        $notice_dwdm_arr = unserialize($notice->notice_dwdm);
        $this->delNotices($notice,$notice_dwdm_arr);
        if (!$notice) {
            $this->error = '该通知类型不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $notice->delete();
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
     * 把我的通知标记为已读
     * @param $data
     * @return bool
     */
    public function readMyNotice(array $data) : bool
    {
        // print_r($data);die();
        // 更新我的通知表里是否已读的状态为已读
        $re1 = DB::table('user_notice')
            ->where('user_id', $data['user_id'])
            ->where('notice_id', $data['notic_id'])
            ->update(array('if_read' => 1));
        // 更新用户表该用户未读通知数自动减一
        $no_notice_count = Auth::guard('admin')->user()->no_notice_count-1;
        $re2 = DB::table('admins')
            ->where('id', $data['user_id'])
            ->update(array('no_notice_count' => $no_notice_count));
        if ($re1 === false && $re2 === false) {
            $this->error = '标记失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        return true;
    }
    /**
     * 下载附件记录
     * @param $data
     * @return bool
     */
    public function downAttachment(array $data) : bool
    {
        // 更新我的通知表里是否下载的状态为已下载
        $re = DB::table('user_notice')
            ->where('user_id', $data['user_id'])
            ->where('notice_id', $data['notice_id'])
            ->update(array('if_down' => 1));
        $attachment = Notice::where('id',$data['notice_id'])->value('attachment');
        if ($re === false) {
            $this->error = '下载失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        return  true;
    }
    /**
     * 培训通知报名
     * @param $data
     * @return bool
     */
    public function trainEnter(array $data) : bool
    {
        // 更新我的通知表里是否报名的状态为已下载,以及报名者的单位代码
        $re = DB::table('user_notice')
            ->where('user_id', $data['user_id'])
            ->where('notice_id', $data['notice_id'])
            ->update(array('if_enter' => 1, 'enter_dwdm' => $data['enter_dwdm']));
        if ($re === false) {
            $this->error = '报名失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        return  true;
    }
    /**
     * 把我的提示消息标记为已读
     * @param $data
     * @return bool
     */
    public function readMyMessage(array $data) : bool
    {
        // 更新我的提示消息表里是否已读的状态为已读
        $re = DB::table('prompt_messages')
                     ->where('id', $data['id'])
                     ->update(array('if_read' => 1));
        if ($re === false) {
            $this->error = '标记失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        return true;
    }
}