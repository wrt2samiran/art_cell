<?php

namespace App\Listeners\PropertyManager;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PropertyManager\PropertyManagerCreated;
use Mail;
use App\Mail\Admin\PropertyManager\AccountCreationMailToUser;
class SendAccountCreatedMailToUser
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
     * @param  object  $event
     * @return void
     */
    public function handle(PropertyManagerCreated $event)
    {
        $data=[
            'user'=>$event->user,
            'user_password'=>$event->user_password,
            'from_name'=>env('MAIL_FROM_NAME','SMMS'),
            'from_email'=>env('MAIL_FROM_ADDRESS'),
            'subject'=>'Property Manager Account Creation'
        ];
        Mail::to($event->user['email'])->send(new AccountCreationMailToUser($data)); 
    }
}
