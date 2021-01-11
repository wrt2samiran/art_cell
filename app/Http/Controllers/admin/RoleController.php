<?php
/*********************************************************/
# Class name     : RoleController                         #
# Methods  :                                              #
#    1. list ,                                            #
#    2. create,                                           #
#    3. store                                             #
#    4. show                                              #
#    5. edit                                              #
#    6. update                                            #
#    7. delete                                            #
#    8. change_status                                     #
#    9. ajax_check_role_name_unique                       #
# Created Date   : 15-05-2020                             #
# Purpose        : User group management                  #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Role\{CreateRoleRequest,EditRoleRequest};
use App\Models\{Module,User,Role,RolePermission,ModuleFunctionality,UserType};

class RoleController extends Controller
{
    //defining the view path
    private $view_path='admin.roles';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for role list and datatable ajax response to display record   #
    # in datatable                                                           #
    # Function name    : list                                                #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 22-10-2020                                          #
    # Purpose          : For role list and returning datatable ajax response #

    public function list(Request $request){
        $this->data['page_title']='Group List';
        $current_user=auth()->guard('admin')->user();

        if($request->ajax()){
            $roles=Role::with(['user_type','creator'])->whereHas('creator')->where(function($q)use($current_user){
                //if logged in user is not super admin then fetch only the roles which is created by logged in user
                if($current_user->role->user_type->slug!='super-admin'){
                    $q->whereCreatedBy($current_user->id);
                }
            })
            ->when($request->user_type_id,function($query) use($request){
                $query->where('user_type_id',$request->user_type_id);
            })
            ->when($request->status,function($query) use($request){
                if($request->status=='active'){
                    $query->where('status','A');
                }elseif ($request->status=='inactive') {
                    $query->where('status','I');
                }
                
            })
            ->select('roles.*');
            return Datatables::of($roles)
            ->editColumn('created_at', function ($role) {
                return $role->created_at ? with(new Carbon($role->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('creator.name', function ($role)use($current_user) {
                return ($role->created_by==$current_user->id)?$role->creator->name.' (you)':$role->creator->name;
            })
            ->addColumn('status',function($role)use($current_user){
                
                $disabled=(!$current_user->hasAllPermission(['group-status-change']))?'disabled':'';
                if($role->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the group" href="javascript:change_status('."'".route('admin.roles.change_status',$role->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the group" href="javascript:change_status('."'".route('admin.roles.change_status',$role->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })
            ->addColumn('action',function($role)use($current_user){
               
                $delete_url=route('admin.roles.delete',$role->id);
                $details_url=route('admin.roles.show',$role->id);
                $edit_url=route('admin.roles.edit',$role->id);

                $action_buttons='';

                $has_details_permission=($current_user->hasAllPermission(['group-details']))?true:false;
                               
                if($role->slug=='super-admin' || $role->id==$current_user->role_id || !$current_user->hasAllPermission(['group-edit'])){
                    $has_edit_permission=false;
                }else{
                    $has_edit_permission=true;
                }

                if($role->id==$current_user->role_id || !$current_user->hasAllPermission(['group-delete']) ||  $role->is_main_role==true){
                    $has_delete_permission=false;
                }else{
                    $has_delete_permission=true;
                }
                
                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Group Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }
                if($has_edit_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Group" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';                  
                }    

                if($has_delete_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Group" href="javascript:delete_role('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';   
                }

                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                }            
                return $action_buttons;
                
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        $this->data['user_types']=UserType::whereIsActive(true)->orderBy('id','asc')->get();
        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function to load role create view page                                 #
    # Function name    : create                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 22-10-2020                                          #
    # Purpose          : To load role create view page                       #
    public function create(){
        $this->data['page_title']='Create Group';
        $current_user=auth()->guard('admin')->user();
        $this->data['user_types']=UserType::whereIsActive(true)->orderBy('id','asc')->get();

        $modules=Module::where(function($q)use($current_user){
            if($current_user->role->user_type->slug!='super-admin'){
                //if logged in user is not super admin then fetch the modules related to logged in users role 
                //and not consider user to create a group with such a module which belongs to only admin i.e role-management,user-management
                $q->whereIn('slug',$current_user->module_access_slug_array())
                ->whereNotIn('slug',['role-management','user-management']);
            }
        })->with(['functionalities'=>function($q)use($current_user){
                //if logged in user is not super admin then fetch the permission related to logged in users role
                if($current_user->role->user_type->slug!='super-admin'){

                    $permissions_array=$current_user->permisions_slug_array();

                    $q->whereIn('slug',$permissions_array)
                    ->where('slug','!=','property-create'); //if non admin is creating  group he can not give property creation permission to any group under him
                } 
                
        }])->orderBy('created_at','ASC')->get();
        $this->data['modules']=$modules;
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store role data                                                    #
    # Function name    : store                                                       #
    # Created Date     : 06-10-2020                                                  #
    # Modified date    : 22-10-2020                                                  #
    # Purpose          : to store role data                                          #
    # Param            : CreateRoleRequest $request                                  #

    public function store(CreateRoleRequest $request){

        $current_user=auth()->guard('admin')->user();
        $user_type_id=($current_user->can_select_user_type_during_group_creation())?$request->user_type_id:$current_user->role->user_type->id;

        $new_role=Role::create([
            'user_type_id'=>$user_type_id,
            'role_name'=>$request->role_name,
            'role_description'=>$request->role_description,
            'slug'=>Str::slug($request->role_name, '-'),
            'status'=>'A',
            'created_at'=>\Carbon\Carbon::now(),
            'created_by'=>$current_user->id,
            'updated_by'=>$current_user->id
        ]);

        $module_functionalities=ModuleFunctionality::whereIn('id',$request->functionalities)->get();

        $role_permission_data_array=[];
        
        if(count($module_functionalities)){
            foreach ($module_functionalities as $module_functionality) {
                $role_permission_data_array[]=[
                    'role_id'=>$new_role->id,
                    'module_id'=>$module_functionality->module_id,
                    'module_functionality_id'=>$module_functionality->id,
                    'status'=>'A',
                    'created_by'=>$current_user->id,
                    'updated_by'=>$current_user->id,
                    'created_at'=>\Carbon\Carbon::now(),
                    'updated_at'=>\Carbon\Carbon::now()
                ];
            }
        }
        //insert new records
        RolePermission::insert($role_permission_data_array);

        return redirect()->route('admin.roles.list')->with('success',__('group_manage_module.create_success_message'));
    }

    /************************************************************************/
    # Function to show/load details page for role                            #
    # Function name    : show                                                #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : show/load details page for role                     #
    # Param            : id                                                  #

    public function show($id){
        $role=Role::with(['creator','user_type'])->whereHas('creator')->whereHas('user_type')->findOrFail($id);
        //policy is defined in App\Policies\RolePolicy
        $this->authorize('view',$role);
        $this->data['page_title']='Group Details';
        $this->data['role']=$role;

        $modules_id_array=RolePermission::where('role_id',$role->id)->pluck('module_id')->toArray();
        $modules_id_array=array_unique($modules_id_array);

        $functionalities_id_array=RolePermission::where('role_id',$role->id)->pluck('module_functionality_id')->toArray();

        $modules=Module::whereIn('id',$modules_id_array)
        ->with(['functionalities'=>function($q)use($functionalities_id_array) {
            $q->whereIn('id',$functionalities_id_array);
        }])->orderBy('created_at','ASC')->get();
        $this->data['modules']=$modules;
        return view($this->view_path.'.show',$this->data);
    }

    /************************************************************************/
    # Function to load role edit page                                        #
    # Function name    : edit                                                #
    # Created Date     : 15-05-2020                                          #
    # Modified date    : 15-05-2020                                          #
    # Purpose          : to load role edit page                              #
    # Param            : id                                                  #
    public function edit($id){
        $current_user=auth()->guard('admin')->user();
        $role=Role::whereHas('creator')->findOrFail($id);

        //policy is defined in App\Policies\RolePolicy
        $this->authorize('update',$role);

        $this->data['page_title']='Group Edit';
        $this->data['role']=$role;

        $this->data['user_types']=UserType::whereIsActive(true)->orderBy('id','asc')->get();
     
        $this->data['current_functionalities_id_array']=RolePermission::where('role_id',$role->id)->pluck('module_functionality_id')->toArray();

        $modules=Module::where(function($q)use($role){
            if($role->creator->role->user_type->slug!='super-admin'){
                $q->whereIn('slug',$role->creator->module_access_slug_array())
                ->whereNotIn('slug',['role-management','user-management']);
            }
                
        })->with(['functionalities'=>function($q)use($role){
            if($role->creator->role->user_type->slug!='super-admin'){
                
               $q->whereIn('slug',$role->creator->permisions_slug_array())
                    ->where('slug','!=','property-create'); //if non admin is creating  group he can not give property creation permission to any group under him
            }
               
        }])->orderBy('created_at','ASC')->get();

        $this->data['modules']=$modules;
        $this->data['current_user']=$current_user;
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update role data with module permissions                               #
    # Function name    : update                                                          #
    # Created Date     : 06-10-2020                                                      #
    # Modified date    : 07-10-2020                                                      #
    # Purpose          : to update role data with module permissions                     #
    # Param            : EditRoleRequest $request,id                                     #
    public function update(EditRoleRequest $request,$id){
        $role=Role::whereHas('creator')->findOrFail($id);
        //policy is defined in App\Policies\RolePolicy
        $this->authorize('update',$role);
        $current_user=auth()->guard('admin')->user();

        $update_data=[
            'role_name'=>$request->role_name,
            'role_description'=>$request->role_description,
            'slug'=>Str::slug($request->role_name, '-'),
            'updated_by'=>$current_user->id
        ];

        if($current_user->can_select_user_type_during_group_creation()  && $request->user_type_id && !$role->is_main_role ){
            $update_data['user_type_id']=$request->user_type_id;
        }
       
        $role->update($update_data);

        $module_functionalities=ModuleFunctionality::whereIn('id',$request->functionalities)->get();

        $role_permission_data_array=[];
        if(count($module_functionalities)){
            foreach ($module_functionalities as $module_functionality) {
                $role_permission_data_array[]=[
                    'role_id'=>$role->id,
                    'module_id'=>$module_functionality->module_id,
                    'module_functionality_id'=>$module_functionality->id,
                    'status'=>'A',
                    'created_by'=>$current_user->id,
                    'updated_by'=>$current_user->id,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ];
            }
        }

        //delete old records
        RolePermission::where('role_id',$role->id)->delete();

        //insert new records
        RolePermission::insert($role_permission_data_array);
        return redirect()->route('admin.roles.list')->with('success',__('group_manage_module.edit_success_message'));
    }

    /************************************************************************/
    # Function to delete role                                                #
    # Function name    : delete                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : to delete role                                      #
    # Param            : id                                                  #
    public function delete($id){
         $role=Role::findOrFail($id);
         //policy is defined in App\Policies\RolePolicy
         $this->authorize('delete',$role);
         $users=User::where('role_id',$id)->whereStatus('A')->first();

         if($users){
            return response()->json(['message'=>'There are active users with this group. You can not delete this group. To delete this group assign the users to other group and try again.'],400);
         }else{
            $role->update([
                'deleted_by'=>auth()->guard('admin')->id()
            ]);
            $role->delete();
            return response()->json(['message'=>'Group successfully deleted.']);

         }
    }

    /************************************************************************/
    # Function to change status of role                                      #
    # Function name    : change_status                                       #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 06-10-2020                                          #
    # Purpose          : to change status of role                            #
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
        return response()->json(['message'=>'Group successfully '.$message.'.']);
    }

    /************************************************************************/
    # Function to check role name unique. Will call from jquery validator    #
    # when adding remote rule to check role_name field in client validation  # 
    # Function name    : ajax_check_role_name_unique                         #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 06-10-2020                                          #
    # Purpose          : to check role name unique                           #
    # Param            : Request $request,$role_id(mandatory during update)  #
    public function ajax_check_role_name_unique(Request $request,$role_id=null){
        if($role_id){
             $role= Role::where('id','!=',$role_id)
             ->where('role_name',$request->role_name)->first();
        }else{
             $role= Role::where('role_name',$request->role_name)->first();
        }
     
        if($role){
            echo "false";
        }else{
            echo "true";
        }
    }





}


