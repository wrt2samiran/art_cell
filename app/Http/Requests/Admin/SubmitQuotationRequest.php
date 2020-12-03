<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuotationRequest extends FormRequest
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
        return [
            'first_name'        => 'required|min:2|max:255',
            'last_name'         => 'required|min:2|max:255',
            'email'             => 'email',
            'contact_number'    => 'required',
            'state_id'          => 'required',
            'city_id'           => 'required',
            'landmark'          => 'required',
            'contract_duration' => 'required',
            'property_type_id' => 'required',
            'property_type_id.*'=>'required|max:250',
            'service_id.*'=>'required'
        ];
    }
    public function messages(){
        return [
            'first_name.required'    => 'First name is required',
            'first_name.min'         => 'First name should have 2 characters',
            'first_name.max'         => 'First name should not be more then 100 characters',
            'last_name.required'     => 'Last name is required',
            'last_name.min'          => 'Last name should have 2 characters',
            'last_name.max'          => 'Last name should not be more then 100 characters',
            'contact_number.required'=> 'Contact Number is required',
            // 'country_id.required'    => 'Country is required',
            'state_id.required'      => 'State is required',
            'city_id.required'       => 'City is required',
            'landmark.required'      => 'Landmark is required',
            'contract_duration.required' => 'Contract duration',
            'property_type_id.required'=>'Select proeprty type'
        ];
    }
}
