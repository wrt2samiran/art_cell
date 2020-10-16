<?php
/*********************************************************/
# Class name     : UserController                         #
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
# Purpose        : User management                        #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{User,Role};
use Carbon\Carbon;
use App\Http\Requests\Admin\User\{CreateUserRequest,UpdateUserRequest};
use App\Events\User\UserCreated;
class UserController extends Controller
{
    //defining the view path
    private $view_path='admin.users';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for user list and datatable ajax response         #
    # Function name    : list                                                #
    # Created Date     : 08-10-2020                                          #
    # Modified date    : 08-10-2020                                          #
    # Purpose          : For User list and returning Datatables              #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='User List';

        $current_user=auth()->guard('admin')->user();

        $hide_user_role_array=[];
        //if user don't have service-provider-list permission then we will not fetch service providers from users table
        if(!$current_user->hasAllPermission(['service-provider-list'])){
             $hide_user_role_array[]='service-provider';
        }
        //if user don't have property-owner-list permission then we will not fetch property owners from users table
        if(!$current_user->hasAllPermission(['property-owner-list'])){
             $hide_user_role_array[]='property-owner';
        }
        //if user don't have property-manager-list permission then we will not fetch property manager from users table
        if(!$current_user->hasAllPermission(['property-manager-list'])){
             $hide_user_role_array[]='property-manager';
        }

        if($request->ajax()){

            $users=User::with(['role'])
            ->where('id','!=',$current_user->id)
            ->whereHas('role',function($q) use ($hide_user_role_array){
                if(count($hide_user_role_array)){
                    $q->whereNotIn('slug',$hide_user_role_array);
                } 
            })
            ->when($request->role_id,function($query) use($request){
                $query->whereHas('role',function($sub_query)use ($request){
                    $sub_query->where('roles.id',$request->role_id);
                });
            })
            ->select('users.*');
            return Datatables::of($users)
            ->editColumn('created_at', function ($user) {
                return $user->created_at ? with(new Carbon($user->created_at))->format('m/d/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($user)use ($current_user){

                
                if($user->role->slug=='service-provider'){
                    $disabled=(!$current_user->hasAllPermission(['property-owne-status-change']))?'disabled':'';
                }elseif ($user->role->slug=='property-owner') {
                    $disabled=(!$current_user->hasAllPermission(['service-provider-status-change']))?'disabled':'';
                
                }elseif ($user->role->slug=='property-manager') {

                    $disabled=(!$current_user->hasAllPermission(['property-manager-status-change']))?'disabled':'';
                }else{
                    $disabled=(!$current_user->hasAllPermission(['user-status-change']))?'disabled':'';
                }


                if($user->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the user" href="javascript:change_status('."'".route('admin.users.change_status',$user->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the user" href="javascript:change_status('."'".route('admin.users.change_status',$user->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })

            ->addColumn('action',function($user) use ($current_user){
                $delete_url=route('admin.users.delete',$user->id);
                $details_url=route('admin.users.show',$user->id);
                $edit_url=route('admin.users.edit',$user->id);
                $action_buttons='';
                
                if($user->role->slug=='service-provider'){
                    $has_details_permission=($current_user->hasAllPermission(['service-provider-details']))?true:false;
                    $has_edit_permission=($current_user->id!=$user->id && $current_user->hasAllPermission(['service-provider-edit']))?true:false;

                    $has_delete_permission=($current_user->id!=$user->id && $current_user->hasAllPermission(['service-provider-delete']))?true:false;

                }elseif ($user->role->slug=='property-owner') {
                    $has_details_permission=($current_user->hasAllPermission(['property-owner-details']))?true:false;
                    $has_edit_permission=($current_user->id!=$user->id && $current_user->hasAllPermission(['property-owner-edit']))?true:false;

                    $has_delete_permission=($current_user->id!=$user->id && $current_user->hasAllPermission(['property-owner-delete']))?true:false;
                
                }elseif ($user->role->slug=='property-manager') {
                    $has_details_permission=($current_user->hasAllPermission(['property-manager-details']))?true:false;
                    $has_edit_permission=($current_user->id!=$user->id && $current_user->hasAllPermission(['property-manager-edit']))?true:false;

                    $has_delete_permission=($current_user->id!=$user->id && $current_user->hasAllPermission(['property-manager-delete']))?true:false;

                }else{
                    $has_details_permission=($current_user->hasAllPermission(['user-details']))?true:false;
                    $has_edit_permission=($current_user->id!=$user->id && $current_user->hasAllPermission(['user-edit']))?true:false;
                    $has_delete_permission=($current_user->id!=$user->id && $current_user->hasAllPermission(['user-delete']))?true:false;
                }


                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Servide Provider Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }


                if($has_edit_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Servide Provider" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }


                if($has_delete_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete user" href="javascript:delete_user('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }
                return $action_buttons;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        $roles=Role::whereStatus('A')->where(function($q)use($hide_user_role_array){
            if(count($hide_user_role_array)){
                $q->whereNotIn('slug',$hide_user_role_array);
            } 
        })->orderBy('id','ASC')->get(); 
        $this->data['roles']=$roles;
        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function to load role create view page                                 #
    # Function name    : create                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : To load role create view page                       #
    public function create(){
        $this->data['page_title']='Create user';

        $current_user=auth()->guard('admin')->user();

        $hide_user_role_array=[];
        //if user don't have service-provider-list permission then we will not fetch service providers role
        if(!$current_user->hasAllPermission(['service-provider-list'])){
             $hide_user_role_array[]='service-provider';
        }
        //if user don't have property-owner-list permission then we will not fetch property owners role
        if(!$current_user->hasAllPermission(['property-owner-list'])){
             $hide_user_role_array[]='property-owner';
        }
        //if user don't have property-manager-list permission then we will not fetch property manager role
        if(!$current_user->hasAllPermission(['property-manager-list'])){
             $hide_user_role_array[]='property-manager';
        }


        $roles=Role::whereStatus('A')->where(function($q)use($hide_user_role_array){
            if(count($hide_user_role_array)){
                $q->whereNotIn('slug',$hide_user_role_array);
            } 
        })->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store user data                                                    #
    # Function name    : store                                                       #
    # Created Date     : 08-10-2020                                                  #
    # Modified date    : 08-10-2020                                                  #
    # Purpose          : store user data                                             #
    # Param            : CreateUserRequest $request                                  #

    public function store(CreateUserRequest $request){


        $user=User::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>$request->email,
            'password'=>$request->password,
            'phone'=>$request->phone,
            'role_id'=>$request->role_id,
            'status'=>'A',
            'created_form'=>'B',
            'created_by'=>auth()->guard('admin')->id(),
            'updated_by'=>auth()->guard('admin')->id()
        ]);
        $user->load('role');
        event(new UserCreated($user,$request->password));

        return redirect()->route('admin.users.list')->with('success','User successfully created.');


    }

    /************************************************************************/
    # Function to show/load details page for user                            #
    # Function name    : show                                                #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : show/load details page for user                     #
    # Param            : id                                                  #

    public function show($id){
        $user=User::findOrFail($id);
        $this->data['page_title']='User Details';
        $this->data['user']=$user;
        return view($this->view_path.'.show',$this->data);

    }

    /************************************************************************/
    # Function to load user edit page                            #
    # Function name    : edit                                                #
    # Created Date     : 15-05-2020                                          #
    # Modified date    : 15-05-2020                                          #
    # Purpose          : to load user edit page                  #
    # Param            : id                                                  #
    public function edit($id){
        $user=User::findOrFail($id);
        $this->data['page_title']='Edit User';
        $this->data['user']=$user;
        $current_user=auth()->guard('admin')->user();
        $hide_user_role_array=[];
        //if user don't have service-provider-list permission then we will not fetch service providers role
        if(!$current_user->hasAllPermission(['service-provider-list'])){
             $hide_user_role_array[]='service-provider';
        }
        //if user don't have property-owner-list permission then we will not fetch property owners role
        if(!$current_user->hasAllPermission(['property-owner-list'])){
             $hide_user_role_array[]='property-owner';
        }
        //if user don't have property-manager-list permission then we will not fetch property manager role
        if(!$current_user->hasAllPermission(['property-manager-list'])){
             $hide_user_role_array[]='property-manager';
        }


        $roles=Role::whereStatus('A')->where(function($q)use($hide_user_role_array){
            if(count($hide_user_role_array)){
                $q->whereNotIn('slug',$hide_user_role_array);
            } 
        })->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update user data                                           #
    # Function name    : update                                                          #
    # Created Date     : 06-10-2020                                                      #
    # Modified date    : 07-10-2020                                                      #
    # Purpose          : to update user data                                 #
    # Param            : UpdateUserRequest $request,id                                   #
    public function update(UpdateUserRequest $request,$id){

        $user=User::findOrFail($id);
        $update_data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role_id'=>$request->role_id,
            'updated_by'=>auth()->guard('admin')->id()
        ];

        if($request->password){
            $update_data['password']=$request->password;
        }

        $user->update($update_data);

        //event(new ServiceProviderCreated($user,$request->password));

        return redirect()->route('admin.users.list')->with('success','User successfully updated.');

    }

    /************************************************************************/
    # Function to delete user                                                #
    # Function name    : delete                                              #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 07-10-2020                                          #
    # Purpose          : to delete user                                      #
    # Param            : id                                                  #
    public function delete($id){
        $user=User::findOrFail($id);
        $user->update([
            'email'=>$user->email.'(deleted at-'.Carbon::now().')',
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $user->delete();
        return response()->json(['message'=>'User successfully deleted.']);

    }

    /************************************************************************/
    # Function to change status of USER                                      #
    # Function name    : change_status                                       #
    # Created Date     : 06-10-2020                                          #
    # Modified date    : 06-10-2020                                          #
    # Purpose          : to change status of user                            #
    # Param            : id                                                  #
    public function change_status($id){
        $user=User::findOrFail($id);
        $change_status_to=($user->status=='A')?'I':'A';
        $message=($user->status=='A')?'deactivated':'activated';
         //updating user status
        $user->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'User successfully '.$message.'.']);
    }


}
