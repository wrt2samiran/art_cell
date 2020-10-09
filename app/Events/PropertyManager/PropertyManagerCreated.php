<?php

namespace App\Events\PropertyManager;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PropertyManagerCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $user_password;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$user_password)
    {
        $this->user=$user;
        $this->user_password=$user_password;
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
