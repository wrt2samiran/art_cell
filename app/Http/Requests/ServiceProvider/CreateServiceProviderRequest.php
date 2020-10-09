<?php

namespace App\Http\Requests\ServiceProvider;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceProviderRequest extends FormRequest
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
            'phone'=>'required|numeric|min:8|max:20'
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
            'phone.required' => 'Last name is required',
            'phone.min'=>'Phone/Contact number should have 8 characters',
            'phone.max'=>'Phone/Contact number should not be more then 20 characters',
            'phone.numeric'=>'Only number allowed'
        ];
    }
}
