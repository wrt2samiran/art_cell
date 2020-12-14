<?php

namespace App\Listeners\Complaint;

use App\Events\Complaint\StatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\{User,Notification};
use Carbon\Carbon;
class StoreStatusUpdateNotifications
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
     * @param  StatusUpdated  $event
     * @return void
     */
    public function handle(StatusUpdated $event)
    {

        $complaint=$event->complaint;
        $new_status=$event->new_status;

        $current_user=auth()->guard('admin')->user();
        $user_type=$current_user->role->user_type->slug;
        $contract=$complaint->contract;

        $super_admins=User::whereHas('role')->whereHas('role.user_type',function($q){
            $q->where('slug','super-admin');
        })->where('status','A')->get();

        $notification_data=[];

        if($user_type=='property-owner'){
            $status_updated_by=($current_user->created_by_admin)?'Owner':'Manager';
        }elseif ($user_type=='super-admin') {
            $status_updated_by='Help Desk';
        }elseif ($user_type=='service-provider') {
            $status_updated_by='Service Provider';
        }else{
            $status_updated_by=$current_user->role->user_type->name;
        }

        if($contract){

            //if super admin not updating status then send notification to super admins
            if($user_type!='super-admin'){
                if(count($super_admins)){

                    $notification_message='Complaint status updated by '.$status_updated_by.' for complaint ID :'.$complaint->id;

                    $redirect_path=route('admin.complaints.show',['id'=>$complaint->id],false);
                    foreach ($super_admins as $super_admin) {
                        $notification_data[]=[
                            'notificable_id'=>$complaint->id,
                            'notificable_type'=>'App\Models\Complaint',
                            'user_id'=>$super_admin->id,
                            'message'=>$notification_message,
                            'redirect_path'=>$redirect_path,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ];
                    }
                }
            }

            if($user_type!='service-provider' && $contract->service_provider ){
            //if service provider not updating status then send notification to service provider
                              
                $notification_message='Complaint status updated by '.$status_updated_by.' for complaint ID :'.$complaint->id;

                $redirect_path=route('admin.complaints.show',['id'=>$complaint->id],false);

                $notification_data[]=[
                    'notificable_id'=>$complaint->id,
                    'notificable_type'=>'App\Models\Complaint',
                    'user_id'=>$contract->service_provider_id,
                    'message'=>$notification_message,
                    'redirect_path'=>$redirect_path,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ];
               

            }


            if($user_type!='property-owner' && $contract->property ){
            //if property owner not updating status then send notification to property owner and manager
                              
                $notification_message='Complaint status updated by '.$status_updated_by.' for complaint ID :'.$complaint->id;

                $redirect_path=route('admin.complaints.show',['id'=>$complaint->id],false);

                $notification_data[]=[
                    'notificable_id'=>$complaint->id,
                    'notificable_type'=>'App\Models\Complaint',
                    'user_id'=>$contract->property->property_owner,
                    'message'=>$notification_message,
                    'redirect_path'=>$redirect_path,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ];

                //if property has property manager
                if($contract->property->property_manager){

                    $notification_data[]=[
                        'notificable_id'=>$complaint->id,
                        'notificable_type'=>'App\Models\Complaint',
                        'user_id'=>$contract->property->property_manager,
                        'message'=>$notification_message,
                        'redirect_path'=>$redirect_path,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now()
                    ];

                }


            }


        }

        if(count($notification_data)){
            Notification::insert($notification_data);
        }
  



    }
}
