<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\ContractInstallment;
use App\Mail\Admin\Contract\InstallmentPaymentNotification;
use Mail;
class SendContractInstallmentPaymentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contrat:installmentPaymentNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send contract installment payment notification to customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today_date=Carbon::now()->format('Y-m-d');
        $notify_payments=ContractInstallment::whereHas('contract')
        ->whereHas('contract.property')
        ->where('pre_notification_date',$today_date)->get();

        if (count($notify_payments)) {
            foreach ($notify_payments as $notify_payment) {
            
                if($user=$notify_payment->contract->property->owner_details){
                    $data=[
                        'user'=>$user,
                        'contract'=>$notify_payment->contract,
                        'payment_info'=>$notify_payment,
                        'from_name'=>env('MAIL_FROM_NAME','SMMS'),
                        'from_email'=>env('MAIL_FROM_ADDRESS'),
                        'subject'=>'Payment notification'
                    ];
                    Mail::to($user->email)->send(new InstallmentPaymentNotification($data));
                } 

            }
        }
    }
}
