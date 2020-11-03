<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
class ContractPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any contracts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the contract.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contract  $contract
     * @return mixed
     */
    public function view(User $user, Contract $contract)
    {
        if($contract->created_by!=$user->id){
           return  Response::deny('You are not authorize to view this contract.'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can create contracts.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the contract.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contract  $contract
     * @return mixed
     */
    public function update(User $user, Contract $contract)
    {
        if($contract->created_by!=$user->id){
           return  Response::deny('You are not authorize to edit this contract.'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can delete the contract.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contract  $contract
     * @return mixed
     */
    public function delete(User $user, Contract $contract)
    {
        if($contract->created_by!=$user->id){
           return  Response::deny('You are not authorize to delete this contract.'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();
    }

    /**
     * Determine whether the user can restore the contract.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contract  $contract
     * @return mixed
     */
    public function restore(User $user, Contract $contract)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the contract.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contract  $contract
     * @return mixed
     */
    public function forceDelete(User $user, Contract $contract)
    {
        //
    }

    public function view_user_connected_contract(User $user, Contract $contract)
    {
        /* if logged in user is the custome/service_provider of this contract or he is the property_manager/property_owner of the property related to this contract then he can view thre contract details */
        $current_user=$user;

        if($contract->customer_id!=$current_user->id && $contract->service_provider_id!=$current_user->id && $contract->property_manager_id!=$current_user->id && $contract->property->property_owner!=$current_user->id){

            return  Response::deny('You do not have permission to access this page. '.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        return Response::allow();

    }
}
