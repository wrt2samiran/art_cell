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
        if($request->ajax()){
            $notifications=Notification::where('user_id',$current_user->id)
            ->select('notifications.*');
            return Datatables::of($notifications)
            ->editColumn('created_at', function ($notification) {
                return $notifications->created_at ? with(new Carbon($notifications->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view($this->view_path.'.list',$this->data);
    }
}
