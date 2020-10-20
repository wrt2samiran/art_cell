<?php
/*********************************************************/
# Class name     : PropertyManagerController              #
# Methods  :                                              #
#    1. list ,                                            #
#    2. show                                              #

# Created Date   : 09-10-2020                             #
# Purpose        : Servide provider management            #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{User,Role};
use Carbon\Carbon;
use App\Http\Requests\Admin\PropertyManager\{CreatePropertyManagerRequest,UpdatePropertyManagerRequest};
use App\Events\User\UserCreated;
class PropertyManagerController extends Controller
{
    //defining the view path
    private $view_path='admin.property_managers';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for property manager list and datatable ajax response         #
    # Function name    : list                                                #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : For property manager list and returning Datatables  #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Property Manager List';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $property_managers=User::with(['role'])
            ->whereHas('role',function($q){
                $q->where('slug','property-manager');
            })
            ->select('users.*');
            return Datatables::of($property_managers)
            ->editColumn('created_at', function ($property_manager) {
                return $property_manager->created_at ? with(new Carbon($property_manager->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            // ->addColumn('status',function($property_manager)use ($current_user){

            //     $disabled=(!$current_user->hasAllPermission(['property-manager-status-change']))?'disabled':'';
            //     if($property_manager->status=='A'){
            //        $message='deactivate';
            //        return '<a title="Click to deactivate the property manager" href="javascript:change_status('."'".route('admin.property_managers.change_status',$property_manager->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
            //     }else{
            //        $message='activate';
            //        return '<a title="Click to activate the property manager" href="javascript:change_status('."'".route('admin.property_managers.change_status',$property_manager->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
            //     }
            // })
            ->addColumn('action',function($property_manager)use ($current_user){

                $details_url=route('admin.property_managers.show',$property_manager->id);
                $action_buttons='';

                $has_details_permission=($current_user->hasAllPermission(['property-manager-details']))?true:false;
                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Property Manager Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }

                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
        }     
        return view($this->view_path.'.list',$this->data);
    }

    

    /************************************************************************/
    # Function to show/load details page for property manager                #
    # Function name    : show                                                #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : show/load details page for property manager         #
    # Param            : id                                                  #

    public function show($id){
        $property_manager=User::findOrFail($id);
        $this->data['page_title']='Property Manager Details';
        $this->data['property_manager']=$property_manager;
        return view($this->view_path.'.show',$this->data);

    }


}
