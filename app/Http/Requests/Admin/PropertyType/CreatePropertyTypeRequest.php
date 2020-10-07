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
            'type_name'=>'required|min:3|max:50|unique:property_types',
            'description'=>'required|max:255',
        ];
    }
    public function messages(){
        return [
            'type_name.required' => 'Type name is required',
            'type_name.min'=>'Type name should have 3 characters',
            'type_name.max'=>'Type name should not be more then 50 characters',
            'type_name.unique'=>'Type name alredy exist. Enter different name',
            'description.min'=>'Description should have 3 characters',
            'role_description.max'=>'Description should not more then 255 characters',
            'description.required'  => 'Description is required',
        ];
    }
}
