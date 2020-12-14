<?php

namespace App\Listeners\Complaint;

use App\Events\Complaint\NoteAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\{User,Notification};
use Carbon\Carbon;
class StoreComplaintNoteAddedNotifications
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
     * @param  NoteAdded  $event
     * @return void
     */
    public function handle(NoteAdded $event)
    {
        $complaint=$event->complaint;
        $complaint_note=$event->complaint_note;

        $current_user=auth()->guard('admin')->user();
        $user_type=$current_user->role->user_type->slug;
        $contract=$complaint->contract;

        $super_admins=User::whereHas('role')->whereHas('role.user_type',function($q){
            $q->where('slug','super-admin');
        })->where('status','A')->get();

        $notification_data=[];

        $visible_to_array=explode(',', $complaint_note->visible_to);

        if($user_type=='property-owner'){
            $note_added_by=($current_user->created_by_admin)?'Owner':'Manager';
        }elseif ($user_type=='super-admin') {
            $note_added_by='Help Desk';
        }elseif ($user_type=='service-provider') {
            $note_added_by='Service Provider';
        }else{
            $note_added_by=$current_user->role->user_type->name;
        }

        if($contract){

            //if super admin not complaining then send notification to super admins
            if($user_type!='super-admin'){
                if(count($super_admins)){

                    $notification_message='New note added by '.$note_added_by.' for complaint ID :'.$complaint->id;

                    $redirect_path=route('admin.complaints.show',['id'=>$complaint->id],false);
                    foreach ($super_admins as $super_admin) {
                        $notification_data[]=[
                            'notificable_id'=>$complaint_note->id,
                            'notificable_type'=>'App\Models\ComplaintNote',
                            'user_id'=>$super_admin->id,
                            'message'=>$notification_message,
                            'redirect_path'=>$redirect_path,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ];
                    }
                }
            }

            if($user_type!='service-provider' && $contract->service_provider && in_array('service provider', $visible_to_array)){
            //if service provider not complaining then send notification to service provider
                              
                $notification_message='New note added by '.$note_added_by.' for complaint ID :'.$complaint->id;

                $redirect_path=route('admin.complaints.show',['id'=>$complaint->id],false);

                $notification_data[]=[
                    'notificable_id'=>$complaint_note->id,
                    'notificable_type'=>'App\Models\ComplaintNote',
                    'user_id'=>$contract->service_provider_id,
                    'message'=>$notification_message,
                    'redirect_path'=>$redirect_path,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ];
               

            }


            if($user_type!='property-owner' && $contract->property && in_array('property owner & manager', $visible_to_array)){
            //if property owner not complaining then send notification to property owner and manager
                              
                $notification_message='New note added by '.$note_added_by.' for complaint ID :'.$complaint->id;

                $redirect_path=route('admin.complaints.show',['id'=>$complaint->id],false);

                $notification_data[]=[
                    'notificable_id'=>$complaint_note->id,
                    'notificable_type'=>'App\Models\ComplaintNote',
                    'user_id'=>$contract->property->property_owner,
                    'message'=>$notification_message,
                    'redirect_path'=>$redirect_path,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ];

                //if property has property manager
                if($contract->property->property_manager){

                    $notification_data[]=[
                        'notificable_id'=>$complaint_note->id,
                        'notificable_type'=>'App\Models\ComplaintNote',
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
