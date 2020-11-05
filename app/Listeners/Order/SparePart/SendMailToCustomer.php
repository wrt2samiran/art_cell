<?php

namespace App\Listeners\Order\SparePart;

use App\Events\Order\SparePart\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\Order\SparePart\OrderPlaceMailToCustomer;
use Mail;
class SendMailToCustomer
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
            'subject'=>'Order Confirmation'
        ];

        Mail::to($event->order->user->email)->send(new OrderPlaceMailToCustomer($data)); 
    }
}
