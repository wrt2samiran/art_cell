<?php

namespace App\Listeners\PropertyOwner;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PropertyOwner\PropertyOwnerCreated;
use Mail;
use App\Mail\Admin\PropertyOwner\AccountCreationMailToUser;
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
    public function handle(PropertyOwnerCreated $event)
    {
        $data=[
            'user'=>$event->user,
            'user_password'=>$event->user_password,
            'from_name'=>env('MAIL_FROM_NAME','SMMS'),
            'from_email'=>env('MAIL_FROM_ADDRESS'),
            'subject'=>'Property Owner Account Creation'
        ];
        Mail::to($event->user['email'])->send(new AccountCreationMailToUser($data)); 
    }
}
