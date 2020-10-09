<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
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
            'role_name'=>'required|min:3|max:50|unique:roles',
            'role_description'=>'required|max:255',
            'parent_role'=>'required',
            'functionalities'=>'required'
        ];
    }
    public function messages(){
        return [
            'role_name.required' => 'Group/Role name is required',
            'role_name.min'=>'Group/Role name should have 3 characters',
            'role_name.max'=>'Group/Role name should not be more then 50 characters',
            'role_name.unique'=>'Group/Role name alredy exist. Enter different name',
            'role_description.min'=>'Group/Role description should have 3 characters',
            'role_description.max'=>'Group/Role description should not more then 255 characters',
            'role_description.required'  => 'Group/Role description is required',
            'parent_role.required'=>'Select base group/role under which you want to create new group/role',
            'functionalities.required'=>'Select atleast one permission'
        ];
    }
}
