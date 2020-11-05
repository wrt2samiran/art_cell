<?php

namespace App\Listeners\Order\SharedService;

use App\Events\Order\SharedService\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\Order\SharedService\OrderPlaceMailToAdmin;
use Mail;
class SendMailToAdmin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $data=[
            'order'=>$event->order,
            'from_name'=>env('MAIL_FROM_NAME','SMMS'),
            'from_email'=>env('MAIL_FROM_ADDRESS'),
            'subject'=>'Shared Service Orders'
        ];
        Mail::to($event->admin_contact_email)->send(new OrderPlaceMailToAdmin($data)); 
    }
}
