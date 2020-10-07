<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Role\{CreateRoleRequest,EditRoleRequest};

use App\Models\{Module,User,Role,RolePermission,ModuleFunctionality};

class RoleController extends Controller
{
    private $view_path='admin.roles';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Role List';
        if($request->ajax()){
            $roles=Role::select('roles.*');
            return Datatables::of($roles)
            ->editColumn('created_at', function ($role) {
                return $role->created_at ? with(new Carbon($role->created_at))->format('m/d/Y') : '';
            })
            ->editColumn('role_description', function ($role) {
                return Str::limit($role->role_description,100);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($role){

                $disabled=($role->parrent_id==null)?'disabled':'';
                if($role->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the role" href="javascript:change_status('."'".route('admin.roles.change_status',$role->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the role" href="javascript:change_status('."'".route('admin.roles.change_status',$role->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })
            ->addColumn('action',function($role){
                $delete_url=route('admin.roles.delete',$role->id);
                $details_url=route('admin.roles.show',$role->id);
                $edit_url=route('admin.roles.edit',$role->id);

                $action_buttons='';

                if(true){
                    $action_buttons=$action_buttons.'<a title="View Role Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }

                if(!$role->parrent_id==null){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Role" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }

                if(!$role->parrent_id==null){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete role" href="javascript:delete_role('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }

                return $action_buttons;
                
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        return view($this->view_path.'.list',$this->data);
    }

    public function create(){
        $this->data['page_title']='Role Create';
        $parent_roles=Role::whereStatus('A')->whereNull('parrent_id')->orderBy('id','ASC')->get();
        $this->data['parent_roles']=$parent_roles;
        return view($this->view_path.'.create',$this->data);
    }

    public function store(CreateRoleRequest $request){
        $parent_role_details=Role::findOrFail($request->parent_role);
        $current_user=auth()->guard('admin')->user();
        $new_role=Role::create([
            'parrent_id'=>$parent_role_details->id,
            'role_type'=>$parent_role_details->role_type,
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
        return redirect()->route('admin.roles.list')->with('success','Role/Group successfully created.');
    }
    public function show($id){
        $role=Role::findOrFail($id);
        $this->data['page_title']='Role Details';
        $this->data['role']=$role;
        return view($this->view_path.'.show',$this->data);
    }
    public function edit($id){
        $role=Role::findOrFail($id);
        $this->data['page_title']='Role Edit';
        $this->data['role']=$role;


        $parent_roles=Role::whereStatus('A')->whereNull('parrent_id')->orderBy('id','ASC')->get();
        $this->data['parent_roles']=$parent_roles;

        if($role->parrent_id==null){
            $modules_id_array=RolePermission::where('role_id',$role->id)->pluck('module_id')->toArray();
            $modules_id_array=array_unique($modules_id_array);

            $functionalities_id_array=RolePermission::where('role_id',$role->id)->pluck('module_functionality_id')->toArray();

            $modules=Module::with('functionalities')->orderBy('created_at','ASC')->get();
        }else{

            $parent_role=$role->parent;

            $modules_id_array=RolePermission::where('role_id',$parent_role->id)->pluck('module_id')->toArray();
            $modules_id_array=array_unique($modules_id_array);

            $functionalities_id_array=RolePermission::where('role_id',$parent_role->id)->pluck('module_functionality_id')->toArray();

            $modules=Module::whereIn('id',$modules_id_array)
            ->with(['functionalities'=>function($q)use($functionalities_id_array) {
                $q->whereIn('id',$functionalities_id_array);
            }])->orderBy('created_at','ASC')->get();

        }


        $this->data['current_functionalities_id_array']=RolePermission::where('role_id',$role->id)->pluck('module_functionality_id')->toArray();

        $this->data['modules']=$modules;
        return view($this->view_path.'.edit',$this->data);
    }
    public function update(EditRoleRequest $request,$id){
        $role=Role::findOrFail($id);
        $current_user=auth()->guard('admin')->user();
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
                    'created_at'=>\Carbon\Carbon::now(),
                    'updated_at'=>\Carbon\Carbon::now()
                ];
            }
        }

        //delete old records
        RolePermission::where('role_id',$role->id)->delete();

        //insert new records
        RolePermission::insert($role_permission_data_array);

        return redirect()->route('admin.roles.list')->with('success','Role/group successfully updated');
    }
    public function delete($id){
         $role=Role::findOrFail($id);
         $users=User::where('role_id',$id)->whereStatus('A')->first();

         if($users){
            return response()->json(['message'=>'There are active users with this role/group. You can not delete this role/group. To delete this group assign the users to other group and try again.'],400);
         }else{
            $role->updated([
                'deleted_by'=>auth()->guard('admin')->id()
            ]);
            $role->delete();
            return response()->json(['message'=>'Role/Group successfully deleted.']);

         }
    }

    public function change_status($id){
        $role=Role::findOrFail($id);
        $change_status_to=($role->status=='A')?'I':'A';
        $message=($role->status=='A')?'deactivated':'activated';

         //updating gallery status
        $role->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Role successfully '.$message.'.']);
    }

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

    public function ajax_parent_module_permissions(Request $request,$role_id=null){

        $parent_role_id=$request->parent_role_id;
        $this->data['parent_role']=$parent_role=Role::find($parent_role_id);

        $modules_id_array=RolePermission::where('role_id',$parent_role->id)->pluck('module_id')->toArray();
        $modules_id_array=array_unique($modules_id_array);

        $functionalities_id_array=RolePermission::where('role_id',$parent_role->id)->pluck('module_functionality_id')->toArray();

        $modules=Module::whereIn('id',$modules_id_array)
        ->with(['functionalities'=>function($q)use($functionalities_id_array) {
            $q->whereIn('id',$functionalities_id_array);
        }])->orderBy('created_at','ASC')->get();

        $this->data['modules_id_array']=$modules_id_array;
        $this->data['functionalities_id_array']=$functionalities_id_array;


        if($role_id){
           $this->data['editing_role']=Role::find($role_id);
           $this->data['edit']=true; 
           $this->data['current_functionalities_id_array']=RolePermission::where('role_id',$role_id)->pluck('module_functionality_id')->toArray();
        }else{
           $this->data['edit']=false;  
           $this->data['editing_role']=null;

           $this->data['current_functionalities_id_array']=[];
        }

        $this->data['modules']=$modules;
        return view($this->view_path.'.ajax.module_permissions',$this->data)->render();

    }



}


