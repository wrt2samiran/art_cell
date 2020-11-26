<?php

namespace App\Http\Requests\Admin\Unit;

use Illuminate\Foundation\Http\FormRequest;

class CreateUnitRequest extends FormRequest
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
            'unit_name'=>'required|min:2|max:255',
        ];
    }
    public function messages(){
        return [
            'unit_name.required' => 'Unit name is required',
            'unit_name.min'=>'Unit name should have 2 characters',
            'unit_name.max'=>'Unit name should not be more then 100 characters',
        ];
    }
}