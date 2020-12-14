<?php

namespace App\Listeners\Property;

use App\Events\Property\PropertyCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\{User,Notification};
use Carbon\Carbon;
class StorePropertyCreatedNotifications
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
     * @param  PropertyCreated  $event
     * @return void
     */
    public function handle(PropertyCreated $event)
    {

        /* store property creation notification for proeprty owner if proeprty created by property owner then store notification for admin */
        $property=$event->property;
        $current_user=auth()->guard('admin')->user();
        $user_type=$current_user->role->user_type->slug;

        $notification_data=[];

        if($user_type=='super-admin'){
            //if super admin creating property then send notification to property owner
            $property_owner_user=User::find($property->property_owner);
            if($property_owner_user && $property_owner_user->hasAnyPermission(['property-details','users-property-details'])){

                    $notification_message=env('APP_NAME','SITE').' created a property for you';

                    if($property_owner_user->hasAllPermission(['property-details'])){
                        $redirect_path=route('admin.properties.show',['id'=>$property->id],false);
                    }else{
                        $redirect_path=route('admin.user_properties.show',['id'=>$property->id],false);
                    }
                    
                    $notification_data[]=[
                        'notificable_id'=>$property->id,
                        'notificable_type'=>'App\Models\Property',
                        'user_id'=>$property->property_owner,
                        'message'=>$notification_message,
                        'redirect_path'=>$redirect_path,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now()
                    ];

            }

        }elseif($user_type=='property-owner'){

            //send notification to super admins

            $super_admins=User::whereHas('role')->whereHas('role.user_type',function($q){
                $q->where('slug','super-admin');
            })->where('status','A')->get();

            $notification_message=$property->owner_details->name.' added a new property.';

            $redirect_path=route('admin.properties.show',['id'=>$property->id],false);

            if(count($super_admins)){
                foreach ($super_admins as $super_admin) {
                    if($super_admin->hasAllPermission(['property-details'])){
                        $notification_data[]=[
                            'notificable_id'=>$property->id,
                            'notificable_type'=>'App\Models\Property',
                            'user_id'=>$super_admin->id,
                            'message'=>$notification_message,
                            'redirect_path'=>$redirect_path,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ];
                    }
                }
            }


            //if property owner added a manager to the property then send notification to the manager
            if($property->property_manager){

                $property_manager_user=User::find($property->property_manager);
                if($property_manager_user && $property_manager_user->hasAnyPermission(['property-details','users-property-details'])){

                        $notification_message=$property->owner_details->name.' added you as a property manager.';

                        if($property_manager_user->hasAllPermission(['property-details'])){
                            $redirect_path=route('admin.properties.show',['id'=>$property->id],false);
                        }else{
                            $redirect_path=route('admin.user_properties.show',['id'=>$property->id],false);
                        }
                        
                        $notification_data[]=[
                            'notificable_id'=>$property->id,
                            'notificable_type'=>'App\Models\Property',
                            'user_id'=>$property->property_manager,
                            'message'=>$notification_message,
                            'redirect_path'=>$redirect_path,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ];

                }

            }


        }else{

        }

        if(count($notification_data)){
            Notification::insert($notification_data);
        }

    }
}
