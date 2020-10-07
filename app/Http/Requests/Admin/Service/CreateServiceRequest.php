<?php

namespace App\Http\Requests\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequest extends FormRequest
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
    public function rules()
    {
        return [
            'service_name'=>'required|min:3|max:50|unique:services',
            'description'=>'required|max:255',
        ];
    }
    public function messages(){
        return [
            'service_name.required' => 'Service name is required',
            'service_name.min'=>'Service name should have 3 characters',
            'service_name.max'=>'Service name should not be more then 50 characters',
            'service_name.unique'=>'Service name alredy exist. Enter different name',
            'description.min'=>'Description should have 3 characters',
            'role_description.max'=>'Description should not more then 255 characters',
            'description.required'  => 'Description is required',
        ];
    }
}
