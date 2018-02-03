<?php

namespace App\Listeners;

use App\Events\LoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Model\Log;

class LoginListener
{
    // handle方法中处理事件
    public function handle(LoginEvent $event)
    {
        //获取事件中保存的信息
        $user = $event->getUser();
        $agent = $event->getAgent();
        $ip = $event->getIp();
        $timestamp = $event->getTimestamp();
 
        //登录信息
        $login_info = [
           'ip' => $ip,
           'created_at' => $timestamp,
           'user_id' => $user->id,
           'user_name' => $user->username,
           'operator' => $user->real_name,
           'method' => 'get',
           'routes' => 'admin/login',
           'record' => '登录',
        ];
        // zhuzhichao/ip-location-zh 包含的方法获取ip地理位置
        // $addresses = \Ip::find($ip);
        // $login_info['address'] = implode(' ', $addresses);
        // jenssegers/agent 的方法来提取agent信息
        // $login_info['device'] = $agent->device(); //设备名称
        // $browser = $agent->browser();  
        // $login_info['browser'] = $browser . ' ' . $agent->version($browser); //浏览器
        // $platform = $agent->platform();
        // $login_info['platform'] = $platform . ' ' . $agent->version($platform); //操作系统
        // $login_info['language'] = implode(',', $agent->languages()); //语言
        // //设备类型
        // if ($agent->isTablet()) {
        //     // 平板
        //     $login_info['device_type'] = 'tablet';
        // } else if ($agent->isMobile()) {
        //     // 便捷设备
        //     $login_info['device_type'] = 'mobile';
        // } else if ($agent->isRobot()) {
        //     // 爬虫机器人
        //     $login_info['device_type'] = 'robot';
        //     $login_info['device'] = $agent->robot(); //机器人名称
        // } else {
        //     // 桌面设备
        //     $login_info['device_type'] = 'desktop';
        // }
        //插入到数据库
        Log::create($login_info);
    } 
}
