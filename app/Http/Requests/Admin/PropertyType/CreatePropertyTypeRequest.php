<?php

namespace App\Http\Requests\Admin\PropertyType;

use Illuminate\Foundation\Http\FormRequest;

class CreatePropertyTypeRequest extends FormRequest
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
            'en_type_name'=>'required|min:3|max:50',
            'en_description'=>'required|max:255',
            'ar_type_name'=>'required|min:3|max:50',
            'ar_description'=>'required|max:255',
        ];
    }
    public function messages(){
        return [
            'en_type_name.required' => 'Type name is required',
            'en_type_name.min'=>'Type name should have 3 characters',
            'en_type_name.max'=>'Type name should not be more then 50 characters',
            'en_type_name.unique'=>'Type name alredy exist. Enter different name',
            'en_description.min'=>'Description should have 3 characters',
            'en_description.max'=>'Description should not more then 255 characters',
            'en_description.required'  => 'Description is required',

            'ar_type_name.required' => 'Type name is required',
            'ar_type_name.min'=>'Type name should have 3 characters',
            'ar_type_name.max'=>'Type name should not be more then 50 characters',
            'ar_type_name.unique'=>'Type name alredy exist. Enter different name',
            'ar_description.min'=>'Description should have 3 characters',
            'ar_description.max'=>'Description should not more then 255 characters',
            'ar_description.required'  => 'Description is required',
        ];
    }
}
