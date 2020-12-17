<?php

namespace App\Mail\Admin\LabourFeedback;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LabourFeedbackMailToServiceProvider extends Mailable
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
        $slug = 'labour-feedback-mail-to-service-provider';
        $variable_value=[
            '##USERNAME##'=>$data['user'],
            '##LABOURNAME##'=>$data['labour'],
            '##WORK_ORDER##'=>$data['work_order'],
            '##TASK_TITLE##'=>$data['task_title'],
            '##TASK_DATE##'=>$data['task_date'],
            '##DETAILS##'=>$data['details'],
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
                    ->view('emails.admin.labour_feedback.labour-feedback-mail-to-service-provider',[
                        'mail_content'=>$this->mail_content
                    ]);
    }
}
