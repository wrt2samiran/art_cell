<?php

namespace App\Mail\Order\SparePart;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaceMailToAdmin extends Mailable
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
        $content=view('emails.admin.order.partials.spare_part_order_content_admin',$data)->render();
        $this->mail_content=$content;
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
                    ->view('emails.admin.order.order_placed',[
                        'mail_content'=>$this->mail_content
                    ]);

    }
}
