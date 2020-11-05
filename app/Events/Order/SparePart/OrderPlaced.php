<?php

namespace App\Events\Order\SparePart;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\SparePartOrder;
use Helper;
class OrderPlaced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order;
    public $admin_contact_email;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SparePartOrder $order)
    {
        $this->order=$order;
        $this->admin_contact_email=Helper::get_admin_contact_mail();
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
