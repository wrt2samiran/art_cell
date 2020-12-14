<?php

namespace App\Listeners\Contract;

use App\Events\Contract\ContractCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\{User,Notification};
use Carbon\Carbon;
class StoreContractCreatedNotifications
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ContractCreated  $event
     * @return void
     */
    public function handle(ContractCreated $event)
    {
        $contract=$event->contract;
        $notification_data=[];

        if($contract->service_provider){
            //send notification to service provider

            $notification_message='You have been added to contract : '.$contract->code;

            if($contract->service_provider->hasAnyPermission(['users-contract-list','users-contract-details'])){

                if($contract->service_provider->hasAllPermission(['users-contract-details'])){
                    $redirect_path=route('admin.user_contracts.show',['id'=>$contract->id],false);
                }else{
                    $redirect_path=route('admin.user_contracts.list',['contract_id'=>$contract->id],false);
                }
                
            }else{
                $redirect_path=route('admin.dashboard',[],false);
            }
            
            $notification_data[]=[
                'notificable_id'=>$contract->id,
                'notificable_type'=>'App\Models\Contract',
                'user_id'=>$contract->service_provider_id,
                'message'=>$notification_message,
                'redirect_path'=>$redirect_path,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ];

        }

        if($contract->property){
            //send notification to property owner and manager

            $property=$contract->property;

            if($property->owner_details){

                $notification_message='A new contract created for your property : '.$property->code;

                if($property->owner_details->hasAnyPermission(['users-contract-list','users-contract-details'])){

                    if($property->owner_details->hasAllPermission(['users-contract-details'])){
                        $redirect_path=route('admin.user_contracts.show',['id'=>$contract->id],false);
                    }else{
                        $redirect_path=route('admin.user_contracts.list',['contract_id'=>$contract->id],false);
                    }
                    
                }else{
                    $redirect_path=route('admin.dashboard',[],false);
                }
                
                $notification_data[]=[
                    'notificable_id'=>$contract->id,
                    'notificable_type'=>'App\Models\Contract',
                    'user_id'=>$property->owner_details->id,
                    'message'=>$notification_message,
                    'redirect_path'=>$redirect_path,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ];
            }


            if($property->manager_details){

                $notification_message='A new contract created for the property : '.$property->code;

                if($property->manager_details->hasAnyPermission(['users-contract-list','users-contract-details'])){

                    if($property->manager_details->hasAllPermission(['users-contract-details'])){
                        $redirect_path=route('admin.user_contracts.show',['id'=>$contract->id],false);
                    }else{
                        $redirect_path=route('admin.user_contracts.list',['contract_id'=>$contract->id],false);
                    }
                    
                }else{
                    $redirect_path=route('admin.dashboard',[],false);
                }
                
                $notification_data[]=[
                    'notificable_id'=>$contract->id,
                    'notificable_type'=>'App\Models\Contract',
                    'user_id'=>$property->manager_details->id,
                    'message'=>$notification_message,
                    'redirect_path'=>$redirect_path,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ];
            }


        }


        if(count($notification_data)){
            Notification::insert($notification_data);
        }


    }
}
