<?php
/*********************************************************/
# Class name     : LabourController                         #
# Methods  :                                              #
#    1. list ,                                            #
#    2. create,                                           #
#    3. store                                             #
#    4. show                                              #
#    5. edit                                              #
#    6. update                                            #
#    7. delete                                            #
#    8. change_status                                     #
# Created Date   : 18-11-2020                             #
# Purpose        : LabourController management            #
/*********************************************************/

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{User,Role,LabourLeave,LeaveDates};
use Carbon\Carbon;
use App\Events\User\UserCreated;
use App\Http\Requests\Admin\labour\{CreateLabourRequest,UpdateLabourRequest,CreateLabourLeaveRequest};

class LabourController extends Controller
{
    //defining the view path
    private $view_path='admin.labour';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for Labour list and datatable ajax response         #
    # Function name    : list                                                #
    # Created Date     : 18-11-2020                                          #
    # Modified date    : 18-11-2020                                          #
    # Purpose          : For Labour list and returning Datatables              #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Labour List';

        $current_user=auth()->guard('admin')->user();

        if($request->ajax()){

            $users=User::with(['role'])
            ->where('id','!=',$current_user->id)
            ->where('role_id','=','5')
            ->whereHas('role')
            ->where(function($q)use ($current_user){
                //if logged in user is not super admin then fetch only those useers which are crated by logged in user
                if($current_user->role->user_type->slug!='super-admin'){
                    $q->whereCreatedBy($current_user->id);
                }
            })
            ->when($request->role_id,function($query) use($request){
                $query->where('role_id',$request->role_id);
            })
            ->select('users.*');
            return Datatables::of($users)
            ->editColumn('created_at', function ($user) {
                return $user->created_at ? with(new Carbon($user->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($user)use ($current_user){

                $disabled=(!$current_user->hasAllPermission(['user-status-change']))?'disabled':'';

                if($user->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the user" href="javascript:change_status('."'".route('admin.labour.change_status',$user->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the user" href="javascript:change_status('."'".route('admin.labour.change_status',$user->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })

            ->addColumn('action',function($user) use ($current_user){
                $delete_url=route('admin.labour.delete',$user->id);
                $details_url=route('admin.labour.show',$user->id);
                $edit_url=route('admin.labour.edit',$user->id);
                $action_buttons='';
                
               //need to check permissions later
               if(true){
                $action_buttons=$action_buttons.'<a title="Labour Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
            }
            //need to check permissions later
            if(true){
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Labour" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
            }
            //need to check permissions later
            if(true){
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Labour" href="javascript:delete_user('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
            }

            return $action_buttons;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        $roles=Role::whereHas('user_type')
        ->whereStatus('A')
        ->where(function($q)use ($current_user){
            //if logged in user is not super admin then fetch only roles  which are crated by logged in user
            if($current_user->role->user_type->slug!='super-admin'){
                $q->whereCreatedBy($current_user->id);
            }
        })
        ->orderBy('id','ASC')->get(); 
        $this->data['roles']=$roles;
        return view($this->view_path.'.list',$this->data);
    }

   

    /************************************************************************/
    # Function to load role create view page                                 #
    # Function name    : create                                              #
    # Created Date     : 18-11-2020                                          #
    # Modified date    : 18-11-2020                                          #
    # Purpose          : To load role create view page                       #
    public function create(){
        $this->data['page_title']='Create Labour';

        $current_user=auth()->guard('admin')->user();

        $roles=Role::whereHas('user_type')
        ->whereStatus('A')
        ->where(function($q)use ($current_user){
            //if logged in user is not super admin then fetch only roles  which are crated by logged in user
            if($current_user->role->user_type->slug!='super-admin'){
                $q->whereCreatedBy($current_user->id);
            }
        })
        ->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store user data                                                    #
    # Function name    : store                                                       #
    # Created Date     : 18-11-2020                                                  #
    # Modified date    : 18-11-2020                                                  #
    # Purpose          : store user data                                             #
    # Param            : CreateLaboureRequest $request                                  #

    public function store(CreateLabourRequest $request){

        $user=User::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>strtolower($request->email),
            'password'=>$request->password,
            'phone'=>$request->phone,
            'weekly_off'=>$request->weekly_off,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'role_id'=>5,
            'status'=>'A',
            'created_by'=>auth()->guard('admin')->id(),
            'updated_by'=>auth()->guard('admin')->id()
        ]);
        $user->load('role');
        // event(new UserCreated($user,$request->password));

        return redirect()->route('admin.labour.list')->with('success','Labour successfully created.');


    }

    /************************************************************************/
    # Function to show/load details page for user                            #
    # Function name    : show                                                #
    # Created Date     : 18-11-2020                                          #
    # Modified date    : 18-11-2020                                          #
    # Purpose          : show/load details page for user                     #
    # Param            : id                                                  #

    public function show($id){
        $user=User::findOrFail($id);
        $this->data['page_title']='Labour Details';
        $this->data['user']=$user;
        return view($this->view_path.'.show',$this->data);

    }

    /************************************************************************/
    # Function to load user edit page                            #
    # Function name    : edit                                                #
    # Created Date     : 18-11-2020                                          #
    # Modified date    : 18-11-2020                                          #
    # Purpose          : to load user edit page                  #
    # Param            : id                                                  #
    public function edit($id){
        $user=User::findOrFail($id);
        //policy is defined in App\Policies\UserPolicy
        $this->authorize('update',$user);
        $this->data['page_title']='Edit Laboure';
        $this->data['user']=$user;
        $current_user=auth()->guard('admin')->user();
        $roles=Role::whereHas('user_type')
        ->whereStatus('A')
        ->where(function($q)use ($current_user){
            //if logged in user is not super admin then fetch only roles  which are crated by logged in user
            if($current_user->role->user_type->slug!='super-admin'){
                $q->whereCreatedBy($current_user->id);
            }
        })
        ->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update user data                                           #
    # Function name    : update                                                          #
    # Created Date     : 18-11-2020                                                      #
    # Modified date    : 18-11-2020                                                      #
    # Purpose          : to update user data                                 #
    # Param            : UpdateLabourRequest $request,id                                   #
    public function update(UpdateLabourRequest $request,$id){

        $user=User::findOrFail($id);
        //policy is defined in App\Policies\UserPolicy
        $this->authorize('update',$user);
        $update_data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>strtolower($request->email),
            'phone'=>$request->phone,
            'weekly_off'=>$request->weekly_off,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'updated_by'=>auth()->guard('admin')->id()
        ];

        if($request->password){
            $update_data['password']=$request->password;
        }

        $user->update($update_data);

        //event(new ServiceProviderCreated($user,$request->password));

        return redirect()->route('admin.labour.list')->with('success','labour successfully updated.');

    }

    /************************************************************************/
    # Function to delete user                                                #
    # Function name    : delete                                              #
    # Created Date     : 18-11-2020                                          #
    # Modified date    : 18-11-2020                                          #
    # Purpose          : to delete user                                      #
    # Param            : id                                                  #
    public function delete($id){
        $user=User::findOrFail($id);
        //policy is defined in App\Policies\UserPolicy
        $this->authorize('delete',$user);
        $user->update([
            'email'=>$user->email.'(deleted at-'.Carbon::now().')',
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $user->delete();
        return response()->json(['message'=>'labour successfully deleted.']);

    }

    /************************************************************************/
    # Function to change status of USER                                      #
    # Function name    : change_status                                       #
    # Created Date     : 11-11-2020                                          #
    # Modified date    : 11-11-2020                                          #
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
        return response()->json(['message'=>'labour successfully '.$message.'.']);
    }


    
    /************************************************************************/
    # Function for Labour Leave list         #
    # Function name    : leaveList                                                #
    # Created Date     : 26-11-2020                                          #
    # Modified date    : 26-11-2020                                          #
    # Purpose          : For Labour Leave list and returning Datatables              #
    # ajax response                                                          #

    public function leaveList(Request $request){
        $this->data['page_title']='Labour Leave List';

        $current_user=auth()->guard('admin')->user();

        if($request->ajax()){

            $leaveData=LabourLeave::with(['userDetails'])
            ->whereNull('deleted_at')
            ->whereCreatedBy($current_user->id)
            ->where(function($q)use ($current_user){
                //if logged in user is not super admin then fetch only those useers which are crated by logged in user
                if($current_user->role->user_type->slug!='super-admin'){
                    $q->whereCreatedBy($current_user->id);
                }
            })
            
            ->select('labour_leaves.*');
            return Datatables::of($leaveData)
            ->editColumn('created_at', function ($leaveData) {
                return $leaveData->created_at ? with(new Carbon($leaveData->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($leaveData)use ($current_user){

                $disabled=(!$current_user->hasAllPermission(['user-status-change']))?'disabled':'';

                if($leaveData->status=='Approved'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the user" href="javascript:change_status('."'".route('admin.labour.change_status',$leaveData->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Approved</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the user" href="javascript:change_status('."'".route('admin.labour.change_status',$leaveData->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Declined</a>';
                }
            })

            ->addColumn('action',function($leaveData) use ($current_user){
                $delete_url=route('admin.labour.delete',$leaveData->id);
                $details_url=route('admin.labour.showLeave',$leaveData->id);
                $edit_url=route('admin.labour.editLeave',$leaveData->id);
                $action_buttons='';
                
               //need to check permissions later
               if(true){
                $action_buttons=$action_buttons.'<a title="Labour Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
            }
            //need to check permissions later
            if(true){
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Labour" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
            }
            //need to check permissions later
            if(true){
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Labour" href="javascript:delete_user('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
            }

            return $action_buttons;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        return view($this->view_path.'.leave-list',$this->data);
    }

    /************************************************************************/
    # Function to show/load details page for labour leave                            #
    # Function name    : show                                                #
    # Created Date     : 26-11-2020                                          #
    # Modified date    : 26-11-2020                                          #
    # Purpose          : show/load details page for labour leave                     #
    # Param            : id                                                  #

    public function showLeave($id){
        //$user=User::findOrFail($id);

        $leaveData=LabourLeave::with(['userDetails'])->whereId($id)->first();
        $this->data['page_title']='Labour Leave Details';
        $this->data['leaveData']=$leaveData;
        return view($this->view_path.'.show-leave',$this->data);

    }


    
    /************************************************************************/
    # Function to load labour leave edit page                            #
    # Function name    : edit                                                #
    # Created Date     : 18-11-2020                                          #
    # Modified date    : 18-11-2020                                          #
    # Purpose          : to load labour leave edit page                  #
    # Param            : id                                                  #
    
    public function editLeave($id){
        $leaveData=LabourLeave::findOrFail($id);
        //policy is defined in App\Policies\UserPolicy
        $this->authorize('update',$leaveData);
        $this->data['page_title']='Edit Laboure';
        $this->data['leaveData']=$leaveData;
        $current_user=auth()->guard('admin')->user();
        
        return view($this->view_path.'.leave-edit',$this->data);
    }


    /************************************************************************/
    # Function to load role create view page                                 #
    # Function name    : createLeave                                              #
    # Created Date     : 18-11-2020                                          #
    # Modified date    : 18-11-2020                                          #
    # Purpose          : To load role create view page                       #
    public function createLeave(){
        $this->data['page_title']='Create Labour Leave';

        $current_user=auth()->guard('admin')->user();
        $this->data['labourList'] = User::whereCreatedBy($current_user->id)->get();
        return view($this->view_path.'.create-leave',$this->data);
    }

    

    /********************************************************************************/
    # Function to store user data                                                    #
    # Function name    : storeLeave                                                       #
    # Created Date     : 27-11-2020                                                  #
    # Modified date    : 27-11-2020                                                  #
    # Purpose          : store labour leave data                                             #
    # Param            : CreateLabourLeaveRequest $request                                  #

    public function storeLeave(CreateLabourLeaveRequest $request){

        //dd($request->all());

        $rangeDate = (explode("-",$request->date_range));     
                $start_date = \Carbon\Carbon::parse($rangeDate['0']);
                $end_date = \Carbon\Carbon::parse($rangeDate['1']);

                $date_from = strtotime($start_date);
                $date_to = strtotime($end_date);

                $array_all_days = array();
                $day_passed = ($date_to - $date_from); //seconds
                $day_passed = ($day_passed/86400); //days
                $arr_days=  array();
                $counter = 1;
                $day_to_display = $date_from;

                while($counter <= $day_passed){
                    $day_to_display += 86400;
                    //echo date("F j, Y \n", $day_to_display);
                    $checkLeave = LeaveDates::whereLeaveDate(date('o-m-d',$day_to_display))->first();
                    if(!$checkLeave)
                    {
                        $arr_days[] = date('o-m-d',$day_to_display);
                        $counter++;
                    }
                    
                    else
                    {
                        empty($arr_days);
                        return redirect()->route('admin.labour.createLeave')->with('error','Already Leave adde for this user on '.$checkLeave->leave_date);
                    }
                }

                if(count($arr_days)>0)
                {

                    
                    $storeLeave=LabourLeave::create([
                        'labour_id'=>$request->labour_id,
                        'leave_start'=>date('Y-m-d',$date_from),
                        'leave_end'=>date('Y-m-d',$date_to),
                        'leave_reason' => $request->leave_reason,
                        'created_by'=>auth()->guard('admin')->id(),
                    ]);

                    foreach ($arr_days as $approvedDatesValue) {
                      
                       $user=LeaveDates::create([
                            'leave_id'=>$storeLeave->id,
                            'leave_date'=>$approvedDatesValue,
                            
                        ]);
                    }
                }

        
       

        return redirect()->route('admin.labour.leaveList')->with('success','Labour Leave successfully created.');


    }

}
