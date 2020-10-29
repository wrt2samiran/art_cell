<?php

namespace App\Http\Requests\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;

class EditServiceRequest extends FormRequest
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
            'en_service_name'=>'required|min:3|max:50',
            'en_description'=>'required|max:255',
            'ar_service_name'=>'required|min:3|max:50',
            'ar_description'=>'required|max:255',
            'price'=>'required|numeric'
        ];
    }
    public function messages(){
        return [
            'en_service_name.required' => 'Service name is required',
            'en_service_name.min'=>'Service name should have 3 characters',
            'en_service_name.max'=>'Service name should not be more then 50 characters',
            'en_service_name.unique'=>'Service name alredy exist. Enter different name',
            'en_description.min'=>'Description should have 3 characters',
            'en_description.max'=>'Description should not more then 255 characters',
            'en_description.required'  => 'Description is required',

            'ar_service_name.required' => 'Service name is required',
            'ar_service_name.min'=>'Service name should have 3 characters',
            'ar_service_name.max'=>'Service name should not be more then 50 characters',
            'ar_service_name.unique'=>'Service name alredy exist. Enter different name',
            'ar_description.min'=>'Description should have 3 characters',
            'ar_description.max'=>'Description should not more then 255 characters',
            'ar_description.required'  => 'Description is required',
        ];
    }
}
