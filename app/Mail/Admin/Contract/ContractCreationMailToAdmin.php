<?php

namespace App\Mail\Admin\Contract;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
class ContractCreationMailToAdmin extends Mailable
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
        $slug = 'contract-creation-mail-to-admin';
        $variable_value=[
            '##CONTRACT_CODE##'=>$data['contract']->code,
            '##PROPERTY_NAME##'=>$data['contract']->property->property_name,
            '##PROPERTY_LOCATION##'=>$data['contract']->property->address,
            '##PROPERTY_OWNER_NAME##'=>$data['contract']->property->owner_details->name,
            '##SERVICE_PROVIDER_NAME##'=>$data['contract']->service_provider->name,
            '##START_DATE##'=>Carbon::parse($data['contract']->start_date)->format('d/m/Y'),
            '##END_DATE##'=>Carbon::parse($data['contract']->end_date)->format('d/m/Y')
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
                    ->view('emails.admin.contract.creation_mail_to_admin',[
                        'mail_content'=>$this->mail_content
                    ]);
    }
}
