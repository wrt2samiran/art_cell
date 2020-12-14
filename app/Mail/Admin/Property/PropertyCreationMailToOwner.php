<?php

namespace App\Mail\Admin\Property;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropertyCreationMailToOwner extends Mailable
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
        $slug = 'property-creation-mail-to-property-owner';
        $variable_value=[
            '##USERNAME##'=>$data['user']->name,
            '##PROPERTY_CODE##'=>$data['property']->code,
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
                    ->view('emails.admin.property.creation_mail_to_owner',[
                        'mail_content'=>$this->mail_content
                    ]);
    }
}
