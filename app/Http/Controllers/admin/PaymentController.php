<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ContractInstallment,Contract,Payment};
use Carbon\Carbon;
class PaymentController extends Controller
{
    public function pay_contract_installment($installment_id){
    	$contract_installment=ContractInstallment::findOrFail($installment_id);
    	if(!$contract_installment->is_paid){
    		$contract_installment->update([
    			'is_paid'=>true,
    			'paid_on'=>Carbon::now()->format('Y-m-d')
    		]);

    		Payment::create([
                'user_id'=>auth()->guard('admin')->id(),
    			'payment_for'=>'contract-installment',
    			'contract_id'=>$contract_installment->contract_id,
    			'contract_installment_id'=>$contract_installment->id,
    			'amount'=>$contract_installment->price,
    			'payment_on'=>Carbon::now()
    		]);

    		return redirect()->back()->with('success','Installment successfully paid');
    	}

    	return redirect()->back()->with('error','This installment already paid');

    }

    public function pay_contract_amount($contract_id){
        $contract=Contract::findOrFail($contract_id);

        $contract->update([
            'is_paid'=>true,
            'paid_on'=>Carbon::now()->format('Y-m-d')
        ]);

        Payment::create([
            'user_id'=>auth()->guard('admin')->id(),
            'payment_for'=>'contract',
            'contract_id'=>$contract->id,
            'amount'=>$contract->contract_price,
            'payment_on'=>Carbon::now()
        ]);
        return redirect()->back()->with('success','Contract amount successfully paid');

    }
}
