<?php
/*******************************************************************/
# Class name     : UserPropertyController                 			#
# Methods  :                                              			#
#    1. list ,                                            			#
#    2. show                                              			#
# Created Date   : 21-10-2020                             			#
# Purpose        : Properties in which logged in user connected     #
/*******************************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Carbon;
use Yajra\Datatables\Datatables;
use App\Models\{Country,State,City,User,PropertyType,PropertyAttachment};
use Illuminate\Support\Str;
use File;
class UserPropertyController extends Controller
{
    //defining the view path
    private $view_path='admin.user_properties';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for property list and datatable ajax response                 #
    # Function name    : list                                                #
    # Created Date     : 12-10-2020                                          #
    # Modified date    : 12-10-2020                                          #
    # Purpose          : For property list and returning Datatables          #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Property List';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $properties=Property::whereHas('city')
            ->whereHas('property_type')
            ->whereHas('owner_details')
            ->whereHas('manager_details')
            ->with(['city','property_type','owner_details','manager_details'])
            ->where(function($q) use ($current_user){
                //if logged in user is the property_owner of the property 
                $q->where('property_owner',$current_user->id)
                //OR if logged in user is the property_manager of the property 
                ->orWhere('property_manager',$current_user->id)
                 //OR if the property has contracts and logged in user is one of them i.e service_provider/customer 
                ->orWhereHas('contracts',function($q1) use ($current_user){
                    $q1->where('service_provider_id',$current_user->id)->orWhere('customer_id',$current_user->id);
                });
            })
            ->select('properties.*');
            return Datatables::of($properties)
            ->editColumn('created_at', function ($property) {
                return $property->created_at ? with(new Carbon($property->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($property)use ($current_user){

                $disabled=(!$current_user->hasAllPermission(['property-status-change']))?'disabled':'';
                if($property->is_active){
                   $message='deactivate';
                   return '<a title="Click to deactivate the property" href="javascript:void(0)" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the property" href="javascript:void(0)" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })
            ->addColumn('action',function($property)use ($current_user){

                $details_url=route('admin.user_properties.show',$property->id);
      
                $action_buttons='';


                $action_buttons=$action_buttons.'<a title="View Proprty Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
               
   

                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }     
        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function to show/load details page for property                        #
    # Function name    : show                                                #
    # Created Date     : 21-10-2020                                          #
    # Modified date    : 21-10-2020                                          #
    # Purpose          : show/load details page for property                 #
    # Param            : id                                                  #

    public function show($id){
        $property=Property::whereHas('city')
            ->whereHas('property_type')
            ->whereHas('owner_details')
            ->whereHas('manager_details')
            ->with(['city','property_type','owner_details','manager_details','contracts'])
            ->findOrFail($id);

        $current_user=auth()->guard('admin')->user();
        //check if the logged in user authorize to view the property
        /* if logged in user is the property_owner/property_manager of this property or he is the service_provider/customer of the contracts related to this property then he can view thre property details */

        if(count($property->contracts) && $property->property_owner!=$current_user->id && $property->property_manager!=$current_user->id && $property->contracts[0]->customer_id!=$current_user->id && $property->contracts[0]->service_provider_id!=$current_user->id){

            abort(403,'You do not have permission to access this page'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }
        elseif($property->property_owner!=$current_user->id && $property->property_manager!=$current_user->id){
            abort(403,'You do not have permission to access this page'.'<a href="'.route('admin.dashboard').'" class="btn btn-success">Back to Dashboard</a>');
        }

        $this->data['page_title']='Property Details';
        $this->data['property']=$property;
        return view($this->view_path.'.show',$this->data);

    }

  
}
