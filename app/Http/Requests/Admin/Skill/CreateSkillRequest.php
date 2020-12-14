<?php

namespace App\Http\Requests\Admin\Skill;

use Illuminate\Foundation\Http\FormRequest;

class CreateSkillRequest extends FormRequest
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
            'en_skill_title'=>'required|min:3|max:50',
            'ar_skill_title'=>'required|min:3|max:50',
            'role_id'=>'required'
        ];
    }
    public function messages(){
        return [
            'en_skill_title.required' => 'Skill name is required',
            'en_skill_title.min'=>'Skill name should have 3 characters',
            'en_skill_title.max'=>'Skill name should not be more then 50 characters',
            'en_skill_title.unique'=>'Skill name alredy exist. Enter different name',

            'ar_skill_title.required' => 'Skill name is required',
            'ar_skill_title.min'=>'Skill name should have 3 characters',
            'ar_skill_title.max'=>'Skill name should not be more then 50 characters',
            'ar_skill_title.unique'=>'Skill name alredy exist. Enter different name',

            'role_id.required' => 'Please select role',
            
        ];
    }
}
