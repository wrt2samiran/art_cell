<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\RolePermission;
use App\Models\ModuleFunctionality;
use Redirect;

class CheckFunctionPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $roleId = Auth::guard('admin')->user()->role_id;
        $currentRouteName = Route::getFacadeRoot()->current()->getName();
        $currentRouteName=str_replace("admin.en.",'',$currentRouteName );

       if ($roleId != 1 && $currentRouteName != 'dashboard') {
           $functionObj = ModuleFunctionality::select('id')->where('slug', $currentRouteName)->where('status', 'A')->first();
           if(!empty($functionObj)){
            $functionId = $functionObj->id;
            $checkPermissionCount = RolePermission::where(['role_id' => $roleId, 'module_functionality_id' => $functionId, 'is_deleted' => 'N', 'status' => 'A'])->count();
 
            if ($checkPermissionCount == 0) {
                return Redirect::Route('admin.dashboard')->with('error', 'Access Denied!');
            }
           }
          
       }
        return $next($request);
    }
}
