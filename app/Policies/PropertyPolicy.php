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
        if($property->created_by!=$user->id){
           return  Response::deny('You are not authorize to view this property.'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();
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
        if($property->created_by!=$user->id){
           return  Response::deny('You are not authorize to edit this property. '.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();
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
        /* if logged in user is the property_owner/property_manager of this property or he is the service_provider/customer of the contracts related to this property then he can view thre property details */
        $current_user=$user;
        // if(count($property->contracts) && $property->property_owner!=$current_user->id && $property->contracts[0]->property_manager_id!=$current_user->id && $property->contracts[0]->customer_id!=$current_user->id && $property->contracts[0]->service_provider_id!=$current_user->id){

        //     return  Response::deny('You do not have permission to access this page. '.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        // }
        // elseif($property->property_owner!=$current_user->id){
        //     return  Response::deny('You do not have permission to access this page. '.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        // }
        return Response::allow();

    }

}
