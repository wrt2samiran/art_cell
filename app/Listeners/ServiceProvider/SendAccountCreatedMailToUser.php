<?php

namespace App\Listeners\ServiceProvider;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ServiceProvider\ServiceProviderCreated;
use Mail;
use App\Mail\Admin\ServiceProvider\AccountCreationMailToUser;
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
    public function handle(ServiceProviderCreated $event)
    {

        $data=[
            'user'=>$event->user,
            'user_password'=>$event->user_password,
            'from_name'=>env('MAIL_FROM_NAME','SMMS'),
            'from_email'=>env('MAIL_FROM_ADDRESS'),
            'subject'=>'Service Provider Account Creation'
        ];
        Mail::to($event->user['email'])->send(new AccountCreationMailToUser($data));  

     
    }
}
