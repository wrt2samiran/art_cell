<?php

namespace App\Http\Requests\Admin\Property;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
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
            'property_name'=>'required|min:2|max:100',
            'description'=>'required|max:1000',
            'no_of_units'=>'required|numeric',
            'city_id'=>'required',
            'address'=>'required|max:255',
            'location'=>'required|max:255',
            'property_owner'=>'required',
            'contact_number'=>'required|regex:/[0-9]{8,20}/',
            'contact_email'=>'required|email|max:100',
            'property_files.*' => [
                'mimetypes:application/pdf,image/jpeg,image/png,text/plain,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword',
                'max:1024',
            ],
        ];
    }
    public function messages(){
        return [
            'property_name.required' => 'Property name is required',
            'property_name.min'=>'Property name should have 2 characters',
            'property_name.max'=>'Property name should not be more then 100 characters',
            'property_type_id.required'=>'Select property type',
            'description.required' => 'Description is required',
            'description.max'=>'Description should not be more then 1000 characters',
            'city_id.required'=>'Select city',
            'address.required' => 'Address is required',
            'address.max'=>'Address should not be more then 255 characters',
            'location.required' => 'Location is required',
            'location.max'=>'Location should not be more then 255 characters',
            'property_owner.required'=>'Select property owner',
            'contact_number.required'=>'Contact number is required',
            'contact_number.regex'=>'Only number allowed'
        ];
    }
}
