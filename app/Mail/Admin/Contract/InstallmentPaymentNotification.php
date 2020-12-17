<?php

namespace App\Mail\Admin\Contract;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Helper;
class InstallmentPaymentNotification extends Mailable
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
        $payment_with_currency=Helper::getSiteCurrency().$data['payment_info']->price;

        $this->mail_content='<div>
        <p>Hello '.$data['user']->name.',</p>
        <p>This is a soft reminder for your payment.</p>
        <p>You have a new upcomming payment of '.$payment_with_currency.' on '.Carbon::parse($data['payment_info']->due_date)->format('d/m/Y').' for contract code '.$data['contract']->code.'</p>
        <p>Thanks and Regards <br>'.env('APP_NAME','OSOOL').' teams</p>
        </div>';
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
                    ->view('emails.admin.contract.installment_payment_notification',[
                        'mail_content'=>$this->mail_content
                    ]);
    }
}
