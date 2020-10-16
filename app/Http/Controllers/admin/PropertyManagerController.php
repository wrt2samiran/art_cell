<?php
/*********************************************************/
# Class name     : PropertyManagerController              #
# Methods  :                                              #
#    1. list ,                                            #
#    2. create,                                           #
#    3. store                                             #
#    4. show                                              #
#    5. edit                                              #
#    6. update                                            #
#    7. delete                                            #
#    8. change_status                                     #
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
            ->whereNull('deleted_at')
            ->select('users.*');
            return Datatables::of($property_managers)
            ->editColumn('created_at', function ($property_manager) {
                return $property_manager->created_at ? with(new Carbon($property_manager->created_at))->format('m/d/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($property_manager)use ($current_user){

                $disabled=(!$current_user->hasAllPermission(['property-manager-status-change']))?'disabled':'';
                if($property_manager->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the property manager" href="javascript:change_status('."'".route('admin.property_managers.change_status',$property_manager->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the property manager" href="javascript:change_status('."'".route('admin.property_managers.change_status',$property_manager->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })
            ->addColumn('action',function($property_manager)use ($current_user){
                $delete_url=route('admin.property_managers.delete',$property_manager->id);
                $details_url=route('admin.property_managers.show',$property_manager->id);
                $edit_url=route('admin.property_managers.edit',$property_manager->id);
                $action_buttons='';

                $has_details_permission=($current_user->hasAllPermission(['property-manager-details']))?true:false;
                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Property Manager Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }

                $has_edit_permission=($current_user->hasAllPermission(['property-manager-edit']))?true:false;
                if($has_edit_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Property Manager" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }

                $has_delete_permission=($current_user->hasAllPermission(['property-manager-delete']))?true:false;
                if($has_delete_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Property Manager" href="javascript:delete_property_manager('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }
                return $action_buttons;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }     
        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function to load property manager create view page                     #
    # Function name    : create                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : To load property manager  create view page          #
    public function create(){
        $this->data['page_title']='Create Property manager ';
        $roles=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','property-manager')->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store property manager data                                        #
    # Function name    : store                                                       #
    # Created Date     : 09-10-2020                                                  #
    # Modified date    : 09-10-2020                                                  #
    # Purpose          : store property manager data                                 #
    # Param            : CreatePropertyManagerRequest $request                       #

    public function store(CreatePropertyManagerRequest $request){

        $property_manager_role=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','property-manager')->first();

        $user=User::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>$request->email,
            'password'=>$request->password,
            'phone'=>$request->phone,
            'role_id'=>$property_manager_role->id,
            'status'=>'A',
            'created_form'=>'B',
            'created_by'=>auth()->guard('admin')->id(),
            'updated_by'=>auth()->guard('admin')->id()
        ]);
        $user->load('role');
        event(new UserCreated($user,$request->password));

        return redirect()->route('admin.property_managers.list')->with('success','Property manager successfully created.');


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

    /************************************************************************/
    # Function to load property manager edit page                            #
    # Function name    : edit                                                #
    # Created Date     : 15-05-2020                                          #
    # Modified date    : 15-05-2020                                          #
    # Purpose          : to load property manager edit page                  #
    # Param            : id                                                  #
    public function edit($id){
        $property_manager=User::findOrFail($id);
        $this->data['page_title']='Edit Property manager';
        $this->data['property_manager']=$property_manager;
        $roles=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','property-manager')->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update property manager                                                #
    # Function name    : update                                                          #
    # Created Date     : 06-10-2020                                                      #
    # Modified date    : 07-10-2020                                                      #
    # Purpose          : to update property manager data                                 #
    # Param            : UpdatePropertyManagerRequest $request,id                        #
    public function update(UpdatePropertyManagerRequest $request,$id){

        $property_manager_role=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','property-manager')->first();
        $property_manager=User::findOrFail($id);
        $update_data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role_id'=>$property_manager_role->id,
            'updated_by'=>auth()->guard('admin')->id()
        ];

        if($request->password){
            $update_data['password']=$request->password;
        }

        $property_manager->update($update_data);

        

        return redirect()->route('admin.property_managers.list')->with('success','Property manager successfully updated.');

    }

    /************************************************************************/
    # Function to delete property manager                                    #
    # Function name    : delete                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : to delete property manager                          #
    # Param            : id                                                  #
    public function delete($id){
        $user=User::findOrFail($id);
        $user->update([
            'email'=>$user->email.'(deleted at-'.Carbon::now().')',
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $user->delete();
        return response()->json(['message'=>'Property manager successfully deleted.']);

  
    }

    /************************************************************************/
    # Function to change status of property manager                          #
    # Function name    : change_status                                       #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 06-10-2020                                          #
    # Purpose          : to change status of property manager                #
    # Param            : id                                                  #
    public function change_status($id){
        $property_manager=User::findOrFail($id);
        $change_status_to=($property_manager->status=='A')?'I':'A';
        $message=($property_manager->status=='A')?'deactivated':'activated';
         //updating property manager status
        $property_manager->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Property manager successfully '.$message.'.']);
    }


}
