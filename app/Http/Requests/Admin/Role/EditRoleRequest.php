<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Role;
class EditRoleRequest extends FormRequest
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
        $rules_array=[
            'role_name'=>'required|min:3|max:50|unique:roles,role_name,'.request()->route('id'),
            'role_description'=>'required|max:255',
            'functionalities'=>'required'
        ];

        $role=Role::find(request()->route('id'));
        if($role && $role->parrent_id!=null){
            $rules_array['parent_role']='required';
        }

        return $rules_array;
    }
    public function messages(){
        return [
            'role_name.required' => 'Role name is required',
            'role_name.min'=>'Role name should have 3 characters',
            'role_name.max'=>'Role name should not be more then 50 characters',
            'role_name.unique'=>'Role name alredy exist. Enter different name',
            'role_description.min'=>'Role description should have 3 characters',
            'role_description.max'=>'Role description should not more then 255 characters',
            'role_description.required'  => 'Role description is required',
            'parent_role.required'=>'Select the group for which you want  to create the role',
            'functionalities.required'=>'Select atleast one permission'
        ];
    }
}
