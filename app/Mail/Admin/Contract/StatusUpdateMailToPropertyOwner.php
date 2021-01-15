<?php

namespace App\Mail\Admin\Contract;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Helper;
class StatusUpdateMailToPropertyOwner extends Mailable
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
        $slug = 'contract-status-update-mail-to-property-owner';
        $variable_value=[
            '##USERNAME##'=>$data['user']->name,
            '##CONTRACT_CODE##'=>$data['contract']->code,
            '##CONTRACT_NAME##'=>$data['contract']->title,
            '##CONTRACT_STATUS##'=>$data['contract']->contract_status->status_name,
        ]; 
        $this->mail_content=Helper::emailTemplateMail($slug,$variable_value);
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
                    ->view('emails.admin.contract.status_update_mail_to_property_owner',[
                        'mail_content'=>$this->mail_content
                    ]);
    }
}
