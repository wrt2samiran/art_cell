<?php

namespace App\Http\Requests\Admin\PropertyOwner;

use Illuminate\Foundation\Http\FormRequest;

class CreatePropertyOwnerRequest extends FormRequest
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
            'first_name'=>'required|min:2|max:100',
            'last_name'=>'required|min:2|max:100',
            'email'=>'required|email|max:100|unique:users',
            'password'=>'required|min:6|max:100',
            'phone'=>'required|regex:/[0-9]{8,20}/'
        ];
    }
    public function messages(){
        return [
            'first_name.required' => 'First name is required',
            'first_name.min'=>'First name should have 2 characters',
            'first_name.max'=>'First name should not be more then 100 characters',
            'last_name.required' => 'Last name is required',
            'last_name.min'=>'Last name should have 2 characters',
            'last_name.max'=>'Last name should not be more then 100 characters',
            'email.required'=>'Email is required',
            'email.email'=>'Please enter valid email address',
            'email.unique'=>'Email alredy exist. Try with different email',
            'email.max'=>'Email should not be more then 100 characters',
            'password.required' => 'Password is required',
            'password.min'=>'Password should have 6 characters',
            'password.max'=>'Password should not be more then 100 characters',
            'phone.required' => 'Phone/Contact number is required',
            'phone.regex'=>'Phone/Contact number should be a valid number of size 8 to 20 characters'
        ];
    }
}
