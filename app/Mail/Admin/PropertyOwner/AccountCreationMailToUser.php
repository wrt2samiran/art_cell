<?php

namespace App\Mail\Admin\PropertyOwner;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCreationMailToUser extends Mailable
{
    use Queueable, SerializesModels;
    public $mail_content;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data=$data; 
        $this->mail_content="<div>Hello {$data['user']['first_name']},</div><br><div>You have been added as a Property Owner in SMMS. Please use below credentials to login into the system and make sure to change your password after login.</div><div><p>Email : {$data['user']['email']}</p><p>Password : {$data['user_password']}</p></div><div>Thanks & Regards<br> SMMS Team</div>";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->data['from_email'], $this->data['from_name'])
                    ->replyTo($this->data['from_email'], $this->data['from_name'])
                    ->subject($this->data['subject'])
                    ->view('emails.admin.property_owner.account_creation_mail_to_user',[
                        'mail_content'=>$this->mail_content
                    ]);

    }
}
