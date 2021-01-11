<?php

namespace App\Mail\Quotation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreationMailToAdmin extends Mailable
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
        $slug = 'quotation-mail-admin';
        $variable_value=[
            '##CUSTOMER_NAME##'=>$data['customer_name'],
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
                    ->view('emails.quotation.creation_mail_to_admin',[
                        'mail_content'=>$this->mail_content
                    ]);
    }
}
