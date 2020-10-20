<?php
/*********************************************************/
# Class name     : ServiceProviderController              #
# Methods  :                                              #
#    1. list ,                                            #
#    2. show                                              #
# Created Date   : 08-10-2020                             #
# Purpose        : Servide provider management            #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{User,Role};
use Carbon\Carbon;
use App\Http\Requests\Admin\ServiceProvider\{CreateServiceProviderRequest,UpdateServiceProviderRequest};
use App\Events\User\UserCreated;
class ServiceProviderController extends Controller
{
    //defining the view path
    private $view_path='admin.service_providers';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for service provider list and datatable ajax response         #
    # Function name    : list                                                #
    # Created Date     : 08-10-2020                                          #
    # Modified date    : 08-10-2020                                          #
    # Purpose          : For service provider list and returning Datatables  #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Service Provider List';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $service_providers=User::with(['role'])
            ->whereHas('role',function($q){
            	$q->where('slug','service-provider');
            })
            ->select('users.*');
            return Datatables::of($service_providers)
            ->editColumn('created_at', function ($service_provider) {
                return $service_provider->created_at ? with(new Carbon($service_provider->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            // ->addColumn('status',function($service_provider) use($current_user){

            //     $disabled=(!$current_user->hasAllPermission(['service-provider-status-change']))?'disabled':'';
            //     if($service_provider->status=='A'){
            //        $message='deactivate';
            //        return '<a title="Click to deactivate the service provider" href="javascript:change_status('."'".route('admin.service_providers.change_status',$service_provider->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
            //     }else{
            //        $message='activate';
            //        return '<a title="Click to activate the service provider" href="javascript:change_status('."'".route('admin.service_providers.change_status',$service_provider->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
            //     }
            // })

            ->addColumn('action',function($service_provider)use($current_user){

                $details_url=route('admin.service_providers.show',$service_provider->id);
                $action_buttons='';
               

                $has_details_permission=($current_user->hasAllPermission(['service-provider-details']))?true:false;

                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Servide Provider Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
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
    # Function to show/load details page for service provider                #
    # Function name    : show                                                #
    # Created Date     : 08-10-2020                                          #
    # Modified date    : 08-10-2020                                          #
    # Purpose          : show/load details page for service provider         #
    # Param            : id                                                  #

    public function show($id){
        $service_provider=User::findOrFail($id);
        $this->data['page_title']='Service Provider Details';
        $this->data['service_provider']=$service_provider;
        return view($this->view_path.'.show',$this->data);

    }

    


}
