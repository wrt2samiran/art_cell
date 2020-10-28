<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any roles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        if($user->role->user_type->slug!='super-admin' && $role->created_by!=$user->id){
           return  Response::deny('You are not authorize to view this group.'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        if($user->role->user_type->slug!='super-admin' && $role->created_by!=$user->id){
           return  Response::deny('You are not authorize to edit this group.'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        if($user->role->user_type->slug!='super-admin' && $role->created_by!=$user->id){
           return  Response::deny('You are not authorize to delete this group.'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can restore the role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function restore(User $user, Role $role)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the role.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return mixed
     */
    public function forceDelete(User $user, Role $role)
    {
        //
    }
}
