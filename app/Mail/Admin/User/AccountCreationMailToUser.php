<?php

namespace App\Mail\Admin\User;

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
        $slug = 'registration-successfully';
        $variable_value=[
            '##USERNAME##'=>"{$data['user']['first_name']}",
            '##Address##'=>"{$data['user']['email']}",
            '##Employ-code##'=>"{$data['user_password']}",
        ]; 
        $this->mail_content=\Helper::emailTemplateMail($slug,$variable_value);
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
                    ->view('emails.admin.users.account_creation_mail_to_user',[
                        'mail_content'=>$this->mail_content
                    ]);

    }
}
