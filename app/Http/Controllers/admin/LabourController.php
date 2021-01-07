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
use App\Models\{User,Role,LabourLeave,LeaveDates,Country,State,City,Skills,UserSkills,LabourWeeklyOff};
use Carbon\Carbon;
use App\Events\User\UserCreated;
use App\Http\Requests\Admin\labour\{CreateLabourRequest,UpdateLabourRequest,CreateLabourLeaveRequest,UpdateLabourLeaveRequest};

class LabourController extends Controller
{
    //defining the view path
    private $view_path='admin.labour';
    private $view_path_leave='admin';
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
            ->select('users.*')->orderBy('id', 'Desc');
            return Datatables::of($users)
            ->editColumn('created_at', function ($user) {
                return $user->created_at ? with(new Carbon($user->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($user)use ($current_user){

                //$disabled=(!$current_user->hasAllPermission(['user-status-change']))?'disabled':'';

                if($user->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the user" href="javascript:change_status('."'".route('admin.labour.change_status',$user->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the user" href="javascript:change_status('."'".route('admin.labour.change_status',$user->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
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
        $this->data['country_list']= Country::whereIsActive(1)->get(); 
        $this->data['skill_list']= Skills::whereStatus(1)->where('is_deleted', 'N')->whereRoleId(5)->get(); 
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
            //'weekly_off'=>$request->weekly_off,
            'country_id'=>$request->country_id,
            'state_id'=>$request->state_id,
            'city_id'=>$request->city_id,
            'start_time' =>$request->start_time,
            'end_time' =>$request->end_time,
            'role_id'=>5,
            'status'=>'A',
            'created_by'=>auth()->guard('admin')->id(),
            'updated_by'=>auth()->guard('admin')->id()
        ]);

        foreach ($request->skills as $skill_data) {
                  
                $addSkillDetails = UserSkills::create([
                    'user_id'    => $user->id,
                    'skill_id'   => $skill_data,
                    'created_by' => auth()->guard('admin')->id(),
                ]);

        }


        foreach ($request->weekly_off as $weekly_off_data) {
                  
                $addWeeklyOff = LabourWeeklyOff::create([
                    'user_id'    => $user->id,
                    'day_name'   => $weekly_off_data,
                    'created_by' => auth()->guard('admin')->id(),
                ]);

        }

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
        $user=User::with('user_skills', 'user_skills.skill', 'country', 'state', 'city')->findOrFail($id);
        $this->data['weekly_off_list']= LabourWeeklyOff::whereStatus('1')->whereNull('deleted_at')->whereUserId($id)->get();
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
        $user=User::with('user_skills')->whereId($id)->first();
        //policy is defined in App\Policies\UserPolicy
        //$this->authorize('update',$user);
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
        $user_skill_list = array();
       // dd($user->user_skills);
        foreach ($user->user_skills as $key => $valueSkill) {
            $user_skill_list[] = $valueSkill->skill_id;
        }
       // dd($user_skill_list);
        $this->data['country_list']= Country::whereIsActive(1)->get();
        $this->data['state_list'] = State::whereIsActive('1')->where('country_id', $user->country_id)->get();
        $this->data['city_list'] = City::whereIsActive('1')->where('state_id', $user->state_id)->get();
        $this->data['skill_list']= Skills::whereStatus('1')->where('is_deleted', 'N')->whereRoleId(5)->get();  
        $this->data['user_skill_list']= $user_skill_list;
        $this->data['roles']=$roles;
        $this->data['weekly_off_list']= LabourWeeklyOff::whereStatus('1')->whereNull('deleted_at')->whereUserId($id)->pluck('day_name')->toArray();  
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

       // dd($request->all());
        $user=User::findOrFail($id);
        //policy is defined in App\Policies\UserPolicy
        //$this->authorize('update',$user);
        $update_data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>strtolower($request->email),
            'phone'=>$request->phone,
            //'weekly_off'=>$request->weekly_off,
            'country_id'=>$request->country_id,
            'state_id'=>$request->state_id,
            'city_id'=>$request->city_id,
            'start_time' =>$request->start_time,
            'end_time' =>$request->end_time,
            'updated_by'=>auth()->guard('admin')->id()
        ];

        if($request->password){
            $update_data['password']=$request->password;
        }

        $user->update($update_data);

        $deleteUserSkills = UserSkills::whereUserId($id)->delete();

        foreach ($request->skills as $skill_data) {
                  
                $addSkills = UserSkills::create([
                    'user_id'    => $user->id,
                    'skill_id'   => $skill_data,
                    'created_by' => auth()->guard('admin')->id(),
                ]);

        }

        $deleteUserWeeklyOff = LabourWeeklyOff::whereUserId($id)->delete();

        foreach ($request->weekly_off as $weekly_off_data) {
                  
                $addWeeklyOff = LabourWeeklyOff::create([
                    'user_id'    => $user->id,
                    'day_name'   => $weekly_off_data,
                    'created_by' => auth()->guard('admin')->id(),
                ]);

        }


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
        //$this->authorize('delete',$user);
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
    # Function to get country wise active States                             #
    # Function name    : getStateList                                        #
    # Created Date     : 10-12-2020                                          #
    # Modified date    : 10-12-2020                                          #
    # Purpose          : to get country wise active States                   #
    # Param            : id                                                  #
    public function getStateList(Request $request)
    {

        $allStates = State::whereIsActive('1')->where('country_id', $request->country_id)->get();
        return response()->json(['status'=>true, 'allStates'=>$allStates,],200);
    }


    /************************************************************************/
    # Function to get country and state wise active cities                   #
    # Function name    : getCityList                                         #
    # Created Date     : 10-12-2020                                          #
    # Modified date    : 10-12-2020                                          #
    # Purpose          : to get country and state wise active cities         #
    # Param            : id                                                  #
    public function getCityList(Request $request)
    {
    
        $allCity = City::whereIsActive('1')->where('state_id', $request->state_id)->get();
        return response()->json(['status'=>true, 'allCity'=>$allCity,],200);
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
            ->when($request->role_id,function($query) use($request){
                $query->where('role_id',$request->role_id);
            })
            ->select('labour_leaves.*')->orderBy('id', 'Desc');
            return Datatables::of($leaveData)
            ->editColumn('created_at', function ($leaveData) {
                return $leaveData->created_at ? with(new Carbon($leaveData->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('leave_reason', function ($query, $keyword) {
                $query->whereTranslationLike('leave_reason', "%{$keyword}%");
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%");
            })
            ->filterColumn('leave_start', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(leave_start,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('leave_end', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(leave_end,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($leaveData)use ($current_user){

                //$disabled=(!$current_user->hasAllPermission(['user-status-change']))?'disabled':'';

                if($leaveData->status=='Approved'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the user" href="javascript:change_leave_status('."'".route('admin.labour.change_leave_status',$leaveData->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm" >Approved</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the user" href="javascript:change_leave_status('."'".route('admin.labour.change_leave_status',$leaveData->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Waiting for Approval</a>';
                }
            })

            ->addColumn('action',function($leaveData) use ($current_user){
                $delete_url=route('admin.labour.deleteLeave',$leaveData->id);
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
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Labour" href="javascript:delete_user_leave('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
            }

            return $action_buttons;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        return view($this->view_path.'.leave-list',$this->data);

       // return view($this->view_path_leave.'.leave-list',$this->data);
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

        $leaveData=LabourLeave::with(['userDetails', 'leave_dates'])->whereId($id)->first();

        $this->data['page_title']='Labour Leave Details';
        $this->data['leaveData']=$leaveData;
        return view($this->view_path.'.show-leave',$this->data);

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
        $this->data['labourList'] = User::whereCreatedBy($current_user->id)->whereStatus('A')->whereIsDeleted('N')->get();
        return view($this->view_path.'.create-leave',$this->data);
    }


    
    /************************************************************************/
    # Function to load labour leave edit page                            #
    # Function name    : edit                                                #
    # Created Date     : 11-12-2020                                          #
    # Modified date    : 11-12-2020                                          #
    # Purpose          : to load labour leave edit page                  #
    # Param            : id                                                  #
    
    public function editLeave($id){
        $current_user=auth()->guard('admin')->user();
        $leaveData=LabourLeave::with('leave_dates')->findOrFail($id);
        //policy is defined in App\Policies\UserPolicy
        //$this->authorize('update',$leaveData);
        $this->data['page_title']='Edit Laboure';
        $this->data['leaveData']=$leaveData;
        $this->data['labourList'] = User::whereCreatedBy($current_user->id)->whereStatus('A')->whereIsDeleted('N')->get();
        $current_user=auth()->guard('admin')->user();
        
        return view($this->view_path.'.leave-edit',$this->data);
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
                $counter = 0;
                //$day_to_display = $date_from;
                $day_to_display = ($date_from-86400);
               // exit;
                while($counter <= $day_passed){
                    $day_to_display += 86400;
                    //echo date("F j, Y \n", $day_to_display);
                   // $checkLeave = LeaveDates::with('labour_leaves')->whereLeaveDate(date('o-m-d',$day_to_display))->where('labour_leaves.labour_id',$request->labour_id)->first();

                    $checkLeave = LeaveDates::join('labour_leaves', 'labour_leaves.id', '=', 'leave_dates.leave_id')->where('labour_leaves.labour_id', $request->labour_id)->where('leave_dates.leave_date', date('o-m-d',$day_to_display))->first();

                    //dd($checkLeave);
                    if(!$checkLeave)
                    {
                        $arr_days[] = date('o-m-d',$day_to_display);
                        $counter++;
                    }
                    
                    else
                    {
                        empty($arr_days);
                        return redirect()->route('admin.createLeave')->with('error','Already Leave adde for this user on '.$checkLeave->leave_date);
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

        
       

        return redirect()->route('admin.leave_management.leaveList')->with('success','Labour Leave successfully created.');


    }

    /************************************************************************/
    # Function to change status of User Leave                                     #
    # Function name    : change_status                                       #
    # Created Date     : 11-12-2020                                          #
    # Modified date    : 11-12-2020                                          #
    # Purpose          : to change status of user leave                            #
    # Param            : id                                                  #
    public function change_leave_status($id){
        $user=LabourLeave::findOrFail($id);
        $change_status_to=($user->status=='Approved')?'Waiting for Approval':'Approved';
        $message=($user->status=='Approved')?'deactivated':'activated';
         //updating user status
        $user->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'labour leave successfully '.$message.'.']);
    }


    /************************************************************************/
    # Function to delete user leave                                               #
    # Function name    : deleteLeave                                              #
    # Created Date     : 11-12-2020                                          #
    # Modified date    : 11-12-2020                                          #
    # Purpose          : to delete user leave                                      #
    # Param            : id                                                  #
    public function deleteLeave($id){
        $userLeave=LabourLeave::findOrFail($id);
        //policy is defined in App\Policies\UserPolicy
        //$this->authorize('delete',$userLeave);
        $userLeave->update([
            'status'=>'Declined',
            'deleted_by'=>auth()->guard('admin')->id(),
            'deleted_at' =>date('Y-m-d H:i:s'),
        ]);
        //$userLeave->delete();
        return response()->json(['message'=>'labour leave successfully deleted.']);

    }

    
    /************************************************************************************/
    # Function to update user data                                           #
    # Function name    : updateLeave                                                          #
    # Created Date     : 18-11-2020                                                      #
    # Modified date    : 18-11-2020                                                      #
    # Purpose          : to update user data                                 #
    # Param            : UpdateLabourRequest $request,id                                   #
    public function updateLeave(UpdateLabourLeaveRequest $request,$id){

        $leveData=LabourLeave::findOrFail($id);
        //policy is defined in App\Policies\UserPolicy
        $rangeDate = (explode("-",$request->date_range));     
        $start_date = \Carbon\Carbon::parse($rangeDate['0']);
        $end_date = \Carbon\Carbon::parse($rangeDate['1']);

        $date_from = strtotime($start_date);
        $date_to = strtotime($end_date);

        $array_all_days = array();
        $day_passed = ($date_to - $date_from); //seconds
        $day_passed = ($day_passed/86400); //days
        $arr_days=  array();
        $counter = 0;
        //$day_to_display = $date_from;
        $day_to_display = ($date_from-86400);
       // exit;
        while($counter <= $day_passed){
            $day_to_display += 86400;
            //echo date("F j, Y \n", $day_to_display);
           // $checkLeave = LeaveDates::with('labour_leaves')->whereLeaveDate(date('o-m-d',$day_to_display))->where('labour_leaves.labour_id',$request->labour_id)->first();

            if($request->labour_id==$leveData->labour_id)
            {
                $checkLeave = LeaveDates::join('labour_leaves', 'labour_leaves.id', '=', 'leave_dates.leave_id')->where('labour_leaves.labour_id', $request->labour_id)->where('leave_dates.leave_date', date('o-m-d',$day_to_display))->where('leave_dates.leave_id', '!=',$id)->first();
            }
            else
            {
                $checkLeave = LeaveDates::join('labour_leaves', 'labour_leaves.id', '=', 'leave_dates.leave_id')->where('labour_leaves.labour_id', $request->labour_id)->where('leave_dates.leave_date', date('o-m-d',$day_to_display))->first();
            }

            

            //dd($checkLeave);
            if(!$checkLeave)
            {
                $arr_days[] = date('o-m-d',$day_to_display);
                $counter++;
            }
            
            else
            {
                empty($arr_days);
                return redirect()->route('admin.leave_management.editLeave', $id)->with('error','Already Leave adde for this user on '.$checkLeave->leave_date);
            }
        }
        
        if(count($arr_days)>0)
        {

            $update_leave=[
            'labour_id'=>$request->labour_id,
            'leave_start'=>date('Y-m-d',$date_from),
            'leave_end'=>date('Y-m-d',$date_to),
            'leave_reason' => $request->leave_reason,
            'updated_by'=>auth()->guard('admin')->id()
            ];       

            $leveData->update($update_leave);

            $deleteUserSkills = LeaveDates::whereLeaveId($id)->delete();

            foreach ($arr_days as $approvedDatesValue) {
              
               $user=LeaveDates::create([
                    'leave_id'=>$id,
                    'leave_date'=>$approvedDatesValue,
                    
                ]);
            }
        }


        //event(new ServiceProviderCreated($user,$request->password));

        return redirect()->route('admin.leave_management.leaveList')->with('success','labour successfully updated.');

    }


}
