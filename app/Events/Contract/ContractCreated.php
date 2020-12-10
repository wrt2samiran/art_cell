<?php

namespace App\Events\Contract;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contract;
class ContractCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $contract;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Contract $contract)
    {
        $this->contract=$contract;
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
