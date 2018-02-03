<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Model\Admin; 
use Jenssegers\Agent\Agent;

class LoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var admin 用户模型
     */
    protected $admin;
     
    /**
     * @var Agent Agent对象
     */
    protected $agent;
     
    /**
     * @var string IP地址
     */
    protected $ip;
     
    /**
     * @var int 登录时间戳
     */
    protected $timestamp;

    /**
     * 实例化事件时传递这些信息
     */
    public function __construct($admin, $agent, $ip, $timestamp)
    {
        $this->admin = $admin;
        $this->agent = $agent;
        $this->ip = $ip;
        $this->timestamp = $timestamp;
    }
    public function getUser()
    {
        return $this->admin;
    }
     
    public function getAgent()
    {
        return $this->agent;
    }
     
    public function getIp()
    {
        return $this->ip;
    }
     
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
