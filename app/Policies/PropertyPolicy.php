<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any properties.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function view(User $user, Property $property)
    {
        if($user->role->user_type->slug == 'super-admin'){
            return Response::allow(); 
        }else{
            if($property->property_owner!=$user->id && $property->property_manager!=$user->id){
                return  Response::deny('You are not authorize to edit this property. '.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
            }else{
                return Response::allow();
            }
        }
    }

    /**
     * Determine whether the user can create properties.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function update(User $user, Property $property)
    {
        if($user->role->user_type->slug == 'super-admin'){
            return Response::allow(); 
        }else{
            if($property->property_owner!=$user->id && $property->property_manager!=$user->id){
                return  Response::deny('You are not authorize to edit this property. '.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
            }else{
                return Response::allow();
            }  
        } 
    }

    /**
     * Determine whether the user can delete the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function delete(User $user, Property $property)
    {
        //
    }

    /**
     * Determine whether the user can restore the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function restore(User $user, Property $property)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the property.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Property  $property
     * @return mixed
     */
    public function forceDelete(User $user, Property $property)
    {
        //
    }

    public function view_user_connected_property(User $user, Property $property)
    {
        //check if the logged in user authorize to view the property
        /* if logged in user is the property_owner/property_manager of this property or he is the service_provider the contracts related to this property then he can view thre property details */
        $current_user=$user;
        if(count($property->contracts)){

            if($property->property_owner!=$current_user->id && $property->property_manager!=$current_user->id && $property->contracts[0]->service_provider_id!=$current_user->id){

                return  Response::deny('You do not have permission to access this page. '.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');

            }


        }else{
            if($property->property_owner!=$current_user->id && $property->property_manager!=$current_user->id){
                return  Response::deny('You do not have permission to access this page. '.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
            }
        }

        return Response::allow();

    }

}
