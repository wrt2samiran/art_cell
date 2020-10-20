<?php

namespace App\Http\Requests\Admin\Contract;

use Illuminate\Foundation\Http\FormRequest;

class CreateContractRequest extends FormRequest
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
        request()->session()->flash('number_of_installment', count(request('amount')));
        return [
            'description'=>'required|max:1000',
            'property'=>'required',
            'service_provider'=>'required',
            'property_owner'=>'required',
            'start_date'=>['required','date_format:d/m/Y'],
            'end_date'=>['required','date_format:d/m/Y'],
            'contract_price'=>'required',
            'services'=>'required',
            'contract_files.*' => [
                'mimetypes:application/pdf,image/jpeg,image/png,text/plain,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword',
                'max:1024',
            ],
        ];
    }
    public function messages(){
        return [
            'description.required' => 'Contract info is required',
            'description.max'=>'Info should not be more then 1000 characters',
            'property.required'=>'Select property',
            'property_owner.required' => 'Please select property owner',
            'service_provider.required'=>'Please select service provider',
            'contract_price.required' => 'Enter contract price',
            'services.required'=>'Select services required for the contract',
            'start_date.required'=>'Start date is required',
            'end_date.required'=>'End date is required'
        ];
    }
}
