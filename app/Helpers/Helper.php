<?php

use App\Models\Module;
use App\Models\RolePermission;


if (!function_exists('checkModulePermission')) {

    function checkModulePermission($slug = '')
    {
        $roleId = Auth::guard('admin')->user()->role_id;
        if ($roleId != 1) {
            $moduleObj = Module::select('id', 'slug')->where('slug', $slug)->where('status', 'A')->first();

            if (!empty($moduleObj)) {
                $moduleId = $moduleObj->id;
                $permissionCount = RolePermission::where(['module_id' => $moduleId, 'role_id' => $roleId, 'status' => 'A', 'is_deleted' => 'N'])->count();
                if ($permissionCount > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }

    }
}

if (!function_exists('checkFunctionPermission')) {

    function checkFunctionPermission($slug = '')
    {
        $roleId = \Auth::guard('admin')->user()->role_id;
        if ($roleId != 1) {
            $functionObj = \App\Models\ModuleFunctionality::select('id', 'slug')->where('slug', $slug)->where('status','A')->first();
            if (!empty($functionObj)) {
                $functionId = $functionObj->id;
                $permissionCount = \App\Models\RolePermission::where(['module_functionality_id' => $functionId, 'role_id' => $roleId, 'status' => 'A', 'is_deleted' => 'N'])->count();

                if ($permissionCount > 0) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }

    }
}


    




 

    
    