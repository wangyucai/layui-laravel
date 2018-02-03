<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Email;
use App\Model\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class EmailService extends BaseService
{
    /**
     * 添加内部邮件
     * @param $data
     * @return array|bool
     */
    public function addEmail(array $data) : bool
    {
        $pic_ext = ["png", "jpg", "gif", 'jpeg'];
        unset($data['file']);
        unset($data['token']);
        $data['email_sender'] = Auth::guard('admin')->user()->id;
        $data['email_receivers'] = serialize(explode(',', $data['email_receivers']));
        foreach ($data['file_info'] as $k => $v) {
            $extension = substr(strrchr($v, '.'), 1);
            if(in_array($extension, $pic_ext)){
                $data['email_pics'][] = $v;
            }else{
                $data['email_attachments'][] = $v;
            }
        }
        unset($data['file_info']);
        $data['email_pics'] = serialize($data['email_pics']);
        $data['email_attachments'] = serialize($data['email_attachments']);
        
        DB::beginTransaction();
        $email = Email::create($data);
        // 非队列实现
        $this->sendEmails($email);
        if (!$email) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 发送邮件给指定用户
     * @param $data
     * @return array|bool
     */
    public function sendEmails($email)
    {
        // 获取邮件发送给哪个人
        $email_receivers = $email->email_receivers;
        $email_receivers_arr = unserialize($email_receivers);
        // 通知用户系统消息
        $users = Admin::whereIn('tel',$email_receivers_arr)->where('tel_hm','!=','')->get();
        foreach($users as $user){
            $user->addEmail($email);
            // 每发送一条邮件就给通知的用户未读邮件数+1
            $no_email_count = $user->no_email_count+1;
            DB::update("update gzjcy_admins set no_email_count = '{$no_email_count}' where id = '{$user->id}'");
        }
    }
    /**
     * 编辑内部邮件
     * @param $data
     * @return bool
     */
    public function editEmail(array $data) : bool
    {
        $data['email_receivers'] = serialize(explode(',', $data['email_receivers']));
        $email = Email::find($data['id']);
        $email_receivers_arr = unserialize($email->email_receivers);
        // 手动开启事务
        DB::beginTransaction();
        $email->email_theme          = $data['email_theme'];
        $email->email_content        = $data['email_content'];
        $email->email_receivers      = $data['email_receivers'];
        $this->delEmails($email,$email_receivers_arr);
        $this->sendEmails($email);
        $re = $email->save();   
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
     * 删除指定用户的邮件
     * @param $data
     * @return array|bool
     */
    public function delEmails($email, $email_receivers_arr)
    {
        // 通知用户系统消息
        $users = Admin::whereIn('tel',$email_receivers_arr)->where('tel_hm','!=','')->get();
        foreach($users as $user){
            $user->deleteEmail($email);
            $no_email_count = $user->no_email_count-1;
            DB::update("update gzjcy_admins set no_email_count = '{$no_email_count}' where id = '{$user->id}'");
        }
    }
    /**
     * 删除邮件
     * @param $data
     * @return bool
     */
    public function delEmail(int $emailId) : bool
    {
        $email = Email::find($emailId);
        $email_receivers_arr = unserialize($email->email_receivers);
        $this->delEmails($email,$email_receivers_arr);
        if (!$email) {
            $this->error = '该邮件不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $email->delete();
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
     * 把我的邮件标记为已读
     * @param $data
     * @return bool
     */
    public function readMyEmail(array $data) : bool
    {
        // 更新我的邮件表里是否已读的状态为已读
        $re1 = DB::table('user_email')
            ->where('user_id', $data['user_id'])
            ->where('email_id', $data['email_id'])
            ->update(array('if_read' => 1));
        // 更新用户表该用户未读邮件数自动减一
        $no_email_count = Auth::guard('admin')->user()->no_email_count-1;
        $re2 = DB::table('admins')
            ->where('id', $data['user_id'])
            ->update(array('no_email_count' => $no_email_count));
        if ($re1 === false && $re2 === false) {
            $this->error = '标记失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        return true;
    }
}