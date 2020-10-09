<?php

namespace App\Listeners\User;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use App\Mail\Admin\User\AccountCreationMailToUser;
use App\Events\User\UserCreated;
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
    public function handle(UserCreated $event)
    {

        $data=[
            'user'=>$event->user,
            'user_password'=>$event->user_password,
            'from_name'=>env('MAIL_FROM_NAME','SMMS'),
            'from_email'=>env('MAIL_FROM_ADDRESS'),
            'subject'=>'User Account Creation'
        ];
        Mail::to($event->user['email'])->send(new AccountCreationMailToUser($data));  

     
    }
}
