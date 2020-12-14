<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\Models\{Notification};
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    //defining the view path
    private $view_path='admin.notifications';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for notification list and datatable ajax response             #
    # Function name    : list                                                #
    # Created Date     : 23-11-2020                                          #
    # Modified date    : 23-11-2020                                          #
    # Purpose          : For notification list and returning Datatables      #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Notifications';
        $current_user=auth()->guard('admin')->user();
        Notification::where('user_id',$current_user->id)->update([
            'is_read'=>true
        ]);

        if($request->ajax()){
            $notifications=Notification::where('user_id',$current_user->id)
            ->select('notifications.*');
            return Datatables::of($notifications)
            ->editColumn('created_at', function ($notification) {
                return $notification->created_at ? with(new Carbon($notification->created_at))->diffForHumans() : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('message',function($notification){
                return '<a href="'.route('admin.notifications.details',$notification->id).'"><div>'.$notification->message.'</div></a>';
            })
            ->rawColumns(['action','message'])
            ->make(true);
        }
        return view($this->view_path.'.list',$this->data);
    }


    /************************************************************************/
    # Function for notification details                                      #
    # Function name    : details                                             #
    # Created Date     : 10-12-2020                                          #
    # Modified date    : 10-12-2020                                          #
    # Purpose          : For for notification details                        #
    # Param            : notification_id                                     #
    public function details($notification_id){
        $notofication=Notification::findOrFail($notification_id);
        $current_user=auth()->guard('admin')->user();

        if($notofication->user_id!=$current_user->id){
        abort(403,'Notofication not belongs to you'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }

        $notofication->update([
            'is_read'=>true
        ]);

        return redirect($notofication->redirect_path);
        
    }
}
