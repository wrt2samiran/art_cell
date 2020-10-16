<?php
/*********************************************************/
# Class name     : ServiceProviderController              #
# Methods  :                                              #
#    1. list ,                                            #
#    2. create,                                           #
#    3. store                                             #
#    4. show                                              #
#    5. edit                                              #
#    6. update                                            #
#    7. delete                                            #
#    8. change_status                                     #
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
            ->whereNull('deleted_at')
            ->select('users.*');
            return Datatables::of($service_providers)
            ->editColumn('created_at', function ($service_provider) {
                return $service_provider->created_at ? with(new Carbon($service_provider->created_at))->format('m/d/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($service_provider) use($current_user){

                $disabled=(!$current_user->hasAllPermission(['service-provider-status-change']))?'disabled':'';
                if($service_provider->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the service provider" href="javascript:change_status('."'".route('admin.service_providers.change_status',$service_provider->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the service provider" href="javascript:change_status('."'".route('admin.service_providers.change_status',$service_provider->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })

            ->addColumn('action',function($service_provider)use($current_user){
                $delete_url=route('admin.service_providers.delete',$service_provider->id);
                $details_url=route('admin.service_providers.show',$service_provider->id);
                $edit_url=route('admin.service_providers.edit',$service_provider->id);
                $action_buttons='';
               

                $has_details_permission=($current_user->hasAllPermission(['service-provider-details']))?true:false;

                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Servide Provider Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }

                $has_edit_permission=($current_user->hasAllPermission(['service-provider-edit']))?true:false;

                if($has_edit_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Servide Provider" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }

                $has_delete_permission=($current_user->hasAllPermission(['service-provider-delete']))?true:false;
                if($has_delete_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete service provider" href="javascript:delete_service_provider('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }
                return $action_buttons;
            })
            ->rawColumns(['action','status'])
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
        $this->data['page_title']='Create Service Provider';
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

        $service_provider_role=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','service-provider')->first();

    	$user=User::create([
    		'first_name'=>$request->first_name,
    		'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
    		'email'=>$request->email,
            'password'=>$request->password,
            'phone'=>$request->phone,
            'role_id'=>$service_provider_role->id,
            'status'=>'A',
            'created_form'=>'B',
            'created_by'=>auth()->guard('admin')->id(),
            'updated_by'=>auth()->guard('admin')->id()
    	]);
        $user->load('role');
        event(new UserCreated($user,$request->password));

        return redirect()->route('admin.service_providers.list')->with('success','Service provider successfully created.');


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

    /************************************************************************/
    # Function to load service provider edit page                            #
    # Function name    : edit                                                #
    # Created Date     : 15-05-2020                                          #
    # Modified date    : 15-05-2020                                          #
    # Purpose          : to load service provider edit page                  #
    # Param            : id                                                  #
    public function edit($id){
        $service_provider=User::findOrFail($id);
        $this->data['page_title']='Edit Service Provider';
        $this->data['service_provider']=$service_provider;
        $roles=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','service-provider')->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update service provider data                                           #
    # Function name    : update                                                          #
    # Created Date     : 06-10-2020                                                      #
    # Modified date    : 07-10-2020                                                      #
    # Purpose          : to update service provider data                                 #
    # Param            : UpdateServiceProviderRequest $request,id                        #
    public function update(UpdateServiceProviderRequest $request,$id){

        $service_provider_role=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','service-provider')->first();
        $service_provider=User::findOrFail($id);
        $update_data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role_id'=>$service_provider_role->id,
            'updated_by'=>auth()->guard('admin')->id()
        ];

        if($request->password){
            $update_data['password']=$request->password;
        }

        $service_provider->update($update_data);


        return redirect()->route('admin.service_providers.list')->with('success','Service provider successfully updated.');

    }

    /************************************************************************/
    # Function to delete service provider                                    #
    # Function name    : delete                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : to delete service provider                          #
    # Param            : id                                                  #
    public function delete($id){
        $user=User::findOrFail($id);
        $user->update([
            'email'=>$user->email.'(deleted at-'.Carbon::now().')',
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
        $service_provider=User::findOrFail($id);
        $change_status_to=($service_provider->status=='A')?'I':'A';
        $message=($service_provider->status=='A')?'deactivated':'activated';
         //updating service provider status
        $service_provider->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Service provider successfully '.$message.'.']);
    }


}
