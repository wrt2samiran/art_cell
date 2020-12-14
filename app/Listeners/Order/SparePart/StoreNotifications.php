<?php

namespace App\Listeners\Order\SparePart;

use App\Events\Order\SparePart\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\{User,Notification};
use Carbon\Carbon;
class StoreNotifications
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
     * @param  OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        $super_admins=User::whereHas('role')->whereHas('role.user_type',function($q){
            $q->where('slug','super-admin');
        })->where('status','A')->get();

        $notification_message=$event->order->user->name.' placed a new spare part order.';

        $redirect_path=route('admin.spare_part_orders.order_details',['order_id'=>$event->order->id],false);

        $notification_data=[];
        if(count($super_admins)){
            foreach ($super_admins as $super_admin) {
                if($super_admin->hasAllPermission(['spare-part-order-management'])){
                    $notification_data[]=[
                        'notificable_id'=>$event->order->id,
                        'notificable_type'=>'App\Models\SparePartOrder',
                        'user_id'=>$super_admin->id,
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
