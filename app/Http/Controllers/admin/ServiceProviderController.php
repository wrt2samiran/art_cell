<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{User,Role};
use Carbon\Carbon;
use App\Http\Requests\ServiceProvider\{CreateServiceProviderRequest,UpdateServiceProviderRequest};
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
        if($request->ajax()){

            $quotations=User::with(['role'])
            ->whereHas('role',function($q){
            	$q->where('slug','service-provider');
            })
            ->select('users.*');
            return Datatables::of($quotations)
            ->editColumn('created_at', function ($service_provider) {
                return $service_provider->created_at ? with(new Carbon($service_provider->created_at))->format('m/d/Y') : '';
            })

            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('first_name', function ($service_provider) {
                return $service_provider->first_name.' '.$service_provider->last_name ;
            })
            ->filterColumn('first_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(first_name,last_name) like ?", ["%$keyword%"]);
            })
            ->addColumn('action',function($service_provider){
                $delete_url=route('admin.service_providers.delete',$service_provider->id);
                $details_url=route('admin.service_providers.show',$service_provider->id);
                $action_buttons='';
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'<a title="View Servide Provider Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete service provider" href="javascript:delete_service_provider('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }
                return $action_buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
        }     
        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function to load role create view page                                 #
    # Function name    : create                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : To load role create view page                       #
    public function create(){
        $this->data['page_title']='Role Create';
        $roles=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','service-provider')->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store service provider data                                        #
    # Function name    : store                                                       #
    # Created Date     : 08-10-2020                                                  #
    # Modified date    : 08-10-2020                                                  #
    # Purpose          : store service provider data                                 #
    # Param            : CreateServiceProviderRequest $request                                  #

    public function store(CreateServiceProviderRequest $request){

    	// User::create([
    	// 	'first_name'=>$request->first_name,
    	// 	'last_name'=>$request->last_name,
    	// 	'email'=>$request->email,
    	// ]);

    }

    /************************************************************************/
    # Function to show/load details page for service provider                #
    # Function name    : show                                                #
    # Created Date     : 08-10-2020                                          #
    # Modified date    : 08-10-2020                                          #
    # Purpose          : show/load details page for service provider         #
    # Param            : id                                                  #

    public function show($id){

    }

    /************************************************************************/
    # Function to load role edit page                                        #
    # Function name    : edit                                                #
    # Created Date     : 15-05-2020                                          #
    # Modified date    : 15-05-2020                                          #
    # Purpose          : to load role edit page                              #
    # Param            : id                                                  #
    public function edit($id){

    }

    /************************************************************************************/
    # Function to update role data with module permissions                               #
    # Function name    : update                                                          #
    # Created Date     : 06-10-2020                                                      #
    # Modified date    : 07-10-2020                                                      #
    # Purpose          : to update role data with module permissions                     #
    # Param            : EditRoleRequest $request,id                                     #
    public function update(Request $request,$id){

    }

    /************************************************************************/
    # Function to delete role                                                #
    # Function name    : delete                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : to delete role                                      #
    # Param            : id                                                  #
    public function delete($id){
        $user=User::findOrFail($id);
        $user->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $user->delete();
        return response()->json(['message'=>'Service provider successfully deleted.']);

  
    }

    /************************************************************************/
    # Function to change status of service provider                          #
    # Function name    : change_status                                       #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 06-10-2020                                          #
    # Purpose          : to change status of service provider                #
    # Param            : id                                                  #
    public function change_status($id){
        $role=Role::findOrFail($id);
        $change_status_to=($role->status=='A')?'I':'A';
        $message=($role->status=='A')?'deactivated':'activated';

         //updating gallery status
        $role->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Service provider successfully '.$message.'.']);
    }


}
