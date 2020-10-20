<?php
/*********************************************************/
# Class name     : PropertyOwnerController                #
# Methods  :                                              #
#    1. list ,                                            #
#    2. show                                              #

# Created Date   : 09-10-2020                             #
# Purpose        : Property owner management              #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{User,Role};
use Carbon\Carbon;
use App\Http\Requests\Admin\PropertyOwner\{CreatePropertyOwnerRequest,UpdatePropertyOwnerRequest};
use App\Events\User\UserCreated;
class PropertyOwnerController extends Controller
{
    //defining the view path
    private $view_path='admin.property_owners';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for property owner list and datatable ajax response           #
    # Function name    : list                                                #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : For property owner list and returning Datatables    #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Property Owner List';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $property_owners=User::with(['role'])
            ->whereHas('role',function($q){
            	$q->where('slug','property-owner');
            })
            ->select('users.*');
            return Datatables::of($property_owners)
            ->editColumn('created_at', function ($property_owner) {
                return $property_owner->created_at ? with(new Carbon($property_owner->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            // ->addColumn('status',function($property_owner) use ($current_user){

            
            //     $disabled=(!$current_user->hasAllPermission(['property-owner-status-change']))?'disabled':'';
            //     if($property_owner->status=='A'){
            //        $message='deactivate';
            //        return '<a title="Click to deactivate the property owner" href="javascript:change_status('."'".route('admin.property_owners.change_status',$property_owner->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
            //     }else{
            //        $message='activate';
            //        return '<a title="Click to activate the property owner" href="javascript:change_status('."'".route('admin.property_owners.change_status',$property_owner->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
            //     }
            // })
            ->addColumn('action',function($property_owner)use ($current_user){

                $details_url=route('admin.property_owners.show',$property_owner->id);
           
                $action_buttons='';

                $has_details_permission=($current_user->hasAllPermission(['property-owner-details']))?true:false;
                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Proprty Owner Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }


                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }     
        return view($this->view_path.'.list',$this->data);
    }

   

    /************************************************************************/
    # Function to show/load details page for property owner                #
    # Function name    : show                                                #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : show/load details page for property owner         #
    # Param            : id                                                  #

    public function show($id){
        $property_owner=User::findOrFail($id);
        $this->data['page_title']='Property Owner Details';
        $this->data['property_owner']=$property_owner;
        return view($this->view_path.'.show',$this->data);

    }

 

}
