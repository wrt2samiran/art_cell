<?php

namespace App\Http\Requests\Admin\labour;

use Illuminate\Foundation\Http\FormRequest;

class CreateLabourLeaveRequest extends FormRequest
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
            'labour_id'=>'required',
            'date_range'=>'required',
        ];
    }
    public function messages(){
        return [
            'labour_id.required' => 'Please select Labour',
            'date_range.required'=>'Please select Date',
        ];
    }
}
