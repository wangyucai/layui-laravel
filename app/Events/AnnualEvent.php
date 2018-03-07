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

class AnnualEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var admin 用户模型
     */
    protected $admin;

    /**
     * @var int 提醒的时间戳
     */
    protected $timestamp;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($admin,$timestamp)
    {
        $this->admin = $admin;
        $this->timestamp = $timestamp;
    }
    public function getUser()
    {
        return $this->admin;
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
