<?php

namespace App\Http\Requests\Admin\Complaint;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplaintRequest extends FormRequest
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
            'contract_id'=>'required',
            'details'=>'required|max:1000',
            'file.*' => [
                'mimetypes:application/pdf,image/jpeg,image/jpg,image/png,text/plain,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword',
                'max:1024',
            ],

        ];
    }
    public function messages(){
        return [
            'contract_id.required' => 'Select contract',
            'description.required' => 'Description is required',
            'description.max'=>'Description should not be more then 1000 characters',
        ];
    }
}
