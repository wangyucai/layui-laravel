<?php

namespace App\Listeners;

use App\Events\AnnualEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use App\Model\Email;
use App\Model\Admin;
use App\Model\Identifyinfo;   //完善职业信息中录入的鉴定人资格信息
use App\Model\CertificateBid; //申报的职业资格证书

class AnnualListener
{
    /**
     * 处理事件.
     *
     * @param  AnnualEvent  $event
     * @return void
     */
    public function handle(AnnualEvent $event)
    {
        //获取事件中保存的信息
        $user = $event->getUser();
        $timestamp = $event->getTimestamp();

        $my_id = $user->id;
        // 邮件接收者
        $email_receivers = $user->tel;
        // 邮件发送者
        $email_sender = 1;//默认超级管理员发送
        $email_info = [
           'email_receivers' => $email_receivers,
           'created_at' => $timestamp,
           'updated_at' => $timestamp,
           'email_sender' => $email_sender,
        ];
        // 获取我的鉴定人资格证书
        $myIdentifyinfo = Identifyinfo::where('admin_id',$my_id)->where('if_remind',0)->get()->toArray();
        foreach ($myIdentifyinfo as $k => $v) {
            if(time()>=$v['jdry_yxrq']-3600*24*90){//失效前 3个月 发送邮件
                //插入到数据库
                $email_info['email_theme'] = '鉴定人资格证书年审到期提醒';
                $email_info['email_content'] = '您的鉴定业务范围为'.$v['jdywfw_code'].'的鉴定人资格证书还有三个月到期，请及时修改年审日期';
                $email = Email::create($email_info);
                $email_id = $email->id;
                $my_info = Admin::where('tel',$email_receivers)->where('tel_hm','!=','')->first();
                $my_info->addEmail($email);
                $no_email_count = $my_info->no_email_count+1;
                DB::update("update gzjcy_admins set no_email_count = '{$no_email_count}' where id = '{$user->id}'");
                Identifyinfo::where('id', $v['id'])->update(['if_remind' => 1]);
            }
        }
        // 获取我申报的职业资格证书
        $myCertificateBid = CertificateBid::where('user_id',$my_id)->where('if_check',1)->where('if_remind',0)->get()->toArray();
        foreach ($myCertificateBid as $k => $v) {
            if(time()>=$v['check_time']+$v['zsyxq']*(3600*24*30*12)-3600*24*90){//失效前 3个月 发送邮件
                //插入到数据库
                $email_info['email_theme'] = '您申报的职业资格证书年审到期提醒';
                $email_info['email_content'] = '您申报的'.$v['zsmc'].'还有三个月到期，请及时修改年审日期';
                $email = Email::create($email_info);
                $email_id = $email->id;
                $my_info = Admin::where('tel',$email_receivers)->where('tel_hm','!=','')->first();
                $my_info->addEmail($email);
                $no_email_count = $my_info->no_email_count+1;
                DB::update("update gzjcy_admins set no_email_count = '{$no_email_count}' where id = '{$user->id}'");
                CertificateBid::where('id', $v['id'])->update(['if_remind' => 1]);
            }
        }     
    }
}