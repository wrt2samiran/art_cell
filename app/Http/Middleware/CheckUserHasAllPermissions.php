<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserHasAllPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,...$permissions)
    {
        if(!auth()->guard('admin')->user()->hasAllPermission($permissions)){
          abort(403,'You do not have permission to access this page'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return $next($request);
    }
}
