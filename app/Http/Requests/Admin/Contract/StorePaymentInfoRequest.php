<?php

namespace App\Http\Requests\Admin\Contract;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules_array=[
            'contract_price'=>'required|numeric'
        ];
        if(request()->in_installment){
            $rules_array['notify_installment_before_days']='required|numeric';
            $rules_array['amount.*']='required|numeric';
            $rules_array['due_date.*']=['required','date_format:d/m/Y'];
        }
        return $rules_array;
    }
}
