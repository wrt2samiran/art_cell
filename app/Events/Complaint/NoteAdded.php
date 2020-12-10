<?php

namespace App\Events\Complaint;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Complaint;
use App\Models\ComplaintNote;
class NoteAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $complaint;
    public $complaint_note;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Complaint $complaint,ComplaintNote $complaint_note)
    {
        $this->complaint=$complaint;
        $this->complaint_note=$complaint_note;
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
