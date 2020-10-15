<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'current_password'=>'required',
            'new_password'=>'required|min:8|max:100',
            'confirm_password'=>'required|same:new_password'
        ];
    }

    public function messages(){
        return [
            'current_password.required' => 'Enter current password',
            'new_password.required'=>'Enter new password',
            'new_password.min'=>'Password should be minimum 8 characters',
            'new_password.max'=>'Password should not be more than 100 characters',
            'confirm_password.required'=>'Confirm your new password',
            'confirm_password.same'=>'Confirm password should match your new password'
        ];
    }
}
