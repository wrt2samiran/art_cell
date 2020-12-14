<?php

namespace App\Mail\Order\SparePart;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaceMailToCustomer extends Mailable
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

        $delivery_address_details=view('emails.admin.order.partials.spare_part.delivery_address_details',$data)->render();
        $order_item_details=view('emails.admin.order.partials.spare_part.order_item_details',$data)->render();
        
        $slug = 'spare-part-order-confirmation-to-customer';
        $variable_value=[
            '##USERNAME##'=>$data['order']->user->name,
            '##ORDER_ID##'=>$data['order']->id,
            '##ORDER_DATE##'=>$data['order']->created_at->format('d/m/Y'),
            '##DELIVERY_ADDRESS_DETAIL##'=>$delivery_address_details,
            '##ORDER_ITEM_DETAIL##'=>$order_item_details
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
                    ->view('emails.admin.order.order_placed',[
                        'mail_content'=>$this->mail_content
                    ]);

    }
}
