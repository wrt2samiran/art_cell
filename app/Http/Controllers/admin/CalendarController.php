<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User,Country,State,City,TaskLists, TaskDetails, ServiceAllocationManagement, Property};

use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use DB;

class CalendarController extends Controller
{

    private $view_path='admin.calendar';

    /*****************************************************/
    # CalendarController
    # Function name : Calendar
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Showing Task Calendar
    # Params        : Request $request
    /*****************************************************/
       
    public function calendardata(Request $request){
        //dd($request->all());
        $this->data['page_title']='Calendar';
        $logedInUser = \Auth::guard('admin')->user()->id;
        $logedInUserRole = \Auth::guard('admin')->user()->role_id;


        $sqlService=ServiceAllocationManagement::with('service')->with('contract')->whereStatus('A')->where('work_status', '<>','2')->whereServiceProviderId($logedInUser)->get();

        $labour_list= User::whereStatus('A')->whereRoleId('5')->whereCreatedBy($logedInUser)->get();
        $this->data['service_list'] = $sqlService;
        $this->data['labour_list']  = $labour_list;

        if ($request->has('search')) {
            
            $sqlCalendar = TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('contract_services')->where(function ($q) use ($request) {
                
                if ($request->has('user_labour_id')) {
                   
                    $q->where(function ($que) use ($request) {
                        $que->where('user_id', $request->user_labour_id);
                       
                     });                   

                    }

                if ($request->task_status!='') {
                   
                    if($request->task_status ==3)
                    {
                        $request->task_status = '0';
                    }
                    
                    $q->where(function ($que) use ($request) {
                        $que->where('status', $request->task_status);
                       
                     });                   

                    }    

            })->get();

        } else {
                $sqlTask=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('contract_services')->whereCreatedBy($logedInUser)->orWhere('user_id',$logedInUser)->orderBy('id','Desc')->get();
        }

        //dd($sqlCalendar);
        $this->data['tasks_list']  = $sqlTask;
        $this->data['request'] = $request;

        return view($this->view_path.'.calendar',$this->data);
    }


    /*****************************************************/
    # CalendarController
    # Function name : List
    # Author        :
    # Created Date  : 20-10-2020
    # Purpose       : Showing Task List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){

        $this->data['page_title']='Task Management List';
        $logedInUser = \Auth::guard('admin')->user()->id;
        if($request->ajax()){

            $tasks=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->where('created_by', $logedInUser)->orWhere('user_id', $logedInUser)->orderBy('id','Desc');
            
            return Datatables::of($tasks)
            ->editColumn('created_at', function ($tasks) {
                return $tasks->created_at ? with(new Carbon($tasks->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($tasks){
                if($tasks->status=='0'){
                   $message='Pending';
                    return '<a href="" class="btn btn-block btn-outline-warning btn-sm">Pending</a>';
                    
                }elseif($tasks->status=='1'){
                   $message='Overdue';
                   return '<a  href="" class="btn btn-block btn-outline-success btn-sm">Overdue</a>';
                   
                }
                else{
                    $message='Completed';
                    return '<a href="" class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($tasks)use ($logedInUser){
                $action_buttons='';
               
             
                if($logedInUser==$tasks->service_provider_id){
                     $add_url=route('admin.task_management.list',$tasks->id);

                     $action_buttons =$action_buttons.'<a title="Add Task" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>';
                }
                
                if($logedInUser==$tasks->created_by){
                    $details_url = route('admin.task_management.show',$tasks->id);
                    $action_buttons=$action_buttons.'<a title="View Service Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }

                if($logedInUser==$tasks->created_by and isset($tasks->tasks_list)==''){
                    $edit_url = route('admin.task_management.edit',$tasks->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit service" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }
                if($logedInUser==$tasks->created_by and isset($tasks->tasks_list)==''){
                    $delete_url=route('admin.task_management.delete',$tasks->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete contract" href="javascript:delete_service('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
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

   /*****************************************************/
    # CalendarController
    # Function name : calendardataAdd
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Adding new Task from Calendar
    # Params        : Request $request
    /*****************************************************/
    public function calendardataAdd(Request $request) {

       // dd($request->all());

        $this->data['page_title']     = 'Add Task';

        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'task_title'    => 'required|min:2|max:255',
                    'service_id'    => 'required',
                    'property_id'   => 'required',
                    'country_id'    => 'required',
                    'state_id'      => 'required',
                    'city_id'       => 'required',
                    'labour_id'     => 'required',
                );
                $validationMessages = array(
                    'task_title.required'   => 'Please enter Task title',
                    'task_title.min'        => 'Task title should be should be at least 2 characters',
                    'task_title.max'        => 'Task title should not be more than 255 characters',
                    'service_id.required'   => 'Please select service',
                    'property_id.required'  => 'Please select property',
                    'country_id.required'   => 'Please select country',
                    'state_id.required'     => 'Please select state',
                    'city_id.required'      => 'Please select city',
                    'labour_id.required'    => 'Please select user',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {

                    return redirect()->route('admin.calendar.calendardata')->withErrors($Validator)->withInput();
                    
                } else {
                    
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
                        $checkFreeDate = TaskDetails::whereTaskDate(date('o-m-d',$day_to_display))->whereServiceId($request->service_id)->whereUserId($request->labour_id)->first();
                        if(!$checkFreeDate)
                        {
                            $arr_days[] = date('o-m-d',$day_to_display);
                            $counter++;
                        }
                        
                        else
                        {
                            return redirect()->route('admin.calendar.calendardata')->with('error', 'Task already been added for this user on '.date("o-m-d",$day_to_display).' for this Service.');
                        }
                         
                    }

                    $sqlServiceData = ServiceAllocationManagement::find($request->service_id);

                    $addTask=TaskLists::create([
                        'service_allocation_id' => $request->service_id,
                        'service_id'            => $sqlServiceData->service_name,
                        'property_id'           => $request->property_id,
                        'country_id'            => $request->country_id,
                        'state_id'              => $request->state_id,
                        'city_id'               => $request->city_id,
                        'user_id'               => $request->labour_id,
                        'task_title'            => $request->task_title,
                        'task_desc'             => $request->task_desc,
                        'start_date'            => date("Y-m-d", strtotime($rangeDate[0])),
                        'end_date'              => date("Y-m-d", strtotime($rangeDate[1])),
                        'status'                => '0',
                        'created_by'            => auth()->guard('admin')->id(),
                        'updated_by'            => auth()->guard('admin')->id(),
                    ]);

                    
                    //$arr_days []= date('o-m-d',$date_from);
                    
                    $addTaskDetails = TaskDetails::create([
                        'service_allocation_id' => $request->service_id,
                        'service_id'            => $sqlServiceData->service_name,
                        'task_id'               => $addTask->id,
                        'user_id'               => $request->labour_id,
                        'task_date'             => date('o-m-d',$date_from),
                        'created_by'            => auth()->guard('admin')->id(),
                    ]);

                    $array_all_days = array();
                    $day_passed = ($date_to - $date_from); //seconds
                    $day_passed = ($day_passed/86400); //days

                    $counter = 1;
                    $day_to_display = $date_from;

                    $task_details_data_array=[];

                    foreach ($arr_days as $key => $value) {

                        $task_details_data_array[]=[
                            'service_allocation_id' => $request->service_id,
                            'service_id'            => $sqlServiceData->service_name,
                            'task_id'               => $addTask->id,
                            'user_id'               => $request->labour_id,
                            'task_date'             => $value,
                            'created_by'            => auth()->guard('admin')->id(),
                        ];

                    }

                    TaskDetails::insert($task_details_data_array);
                    $request->session()->flash('alert-success', 'Task has been added successfully');
                    return redirect()->route('admin.calendar.calendardata');
                    
                }
            }

            return view($this->view_path.'.add',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.calendar.calendardata')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CalendarController
    # Function name : edit
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Editing task
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $this->data['page_title']     = 'Edit City';
        $this->data['panel_title']    = 'Edit City';

        try
        {           
            $details = TaskLists::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {

                
                if ($id == null) {
                    return redirect()->route('admin.service_management.calendar');
                }
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:' .(new City)->getTable().',name,'.$id.'',
                    'country_id'    => 'required',
                    'state_id'      => 'required',
                );
                $validationMessages = array(
                    'name.required'         => 'Please enter name',
                    'name.min'              => 'Name should be should be at least 2 characters',
                    'name.max'              => 'Name should not be more than 255 characters',
                    'country_id.required'   => 'Please select country',
                    'state_id.required'     => 'Please select state',

                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->name        = trim($request->name, ' ');
                    $details->country_id  = $request->country_id;
                    $details->state_id    = $request->state_id;
                    $details->updated_at  = date('Y-m-d H:i:s');
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'City has been updated successfully');
                        return redirect()->route('admin.service_management.calendar');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the city');
                        return redirect()->back();
                    }
                }
            }
            
            $country_list=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            $state_list=State::whereIsActive('1')->whereCountryId($details->country_id)->orderBy('id','ASC')->get();
            $this->data['country_list']=$country_list;
            $this->data['state_list']=$state_list;
            return view($this->view_path.'.edit',$this->data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.service_management.calendar')->with('error', $e->getMessage());
        }
    }

 

    # CalendarController
    # Function name : getCities
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Get State wise City List
    # Params        : Request $request
    /*****************************************************/

    public function getCities(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'state_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allCities = City::whereIsActive('1')->where('state_id', $request->state_id)->get();
        return response()->json(['status'=>true, 'allCities'=>$allCities,],200);
    }


    /*****************************************************/
    # CalendarController
    # Function name : getData
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Get Task Related Data List
    # Params        : Request $request
    /*****************************************************/

   

    public function getData(Request $request)
    {
        $logedInUser = \Auth::guard('admin')->user()->id;
         $validator = Validator::make($request->all(), [ 
            'service_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }
        // $sqlProperty = DB::table('task_lists')
        // ->join('contracts', 'contracts.id', '=', 'task_lists.contract_id')
        // ->join('properties', 'properties.id', '=', 'contracts.property_id')
        // ->where('task_lists.service_provider_id', $logedInUser)
        // ->where('task_lists.id', $request->service_id)
        // ->first();

        $sqlProperty = ServiceAllocationManagement::with('property')->whereId($request->service_id)->first();


        $sqlCity    = City::whereId($sqlProperty->property->city_id)->whereIsActive('1')->first();
        $sqlState   = State::whereIsActive('1')->where('id', $sqlProperty->property->state_id)->first();
        $sqlCountry = Country::whereIsActive('1')->where('id', $sqlProperty->property->country_id)->first();
        
        return response()->json(['status'=>true, 'sqlProperty'=>$sqlProperty, 'sqlCity'=>$sqlCity, 'sqlState'=>$sqlState, 'sqlCountry'=>$sqlCountry],200);
    }


    /*****************************************************/
    # CalendarController
    # Function name : updateTask
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Update Task Data
    # Params        : Request $request
    /*****************************************************/


    
    public function updateTask(Request $request)
    {
       // dd($request->task_id);
        
        $logedInUser = \Auth::guard('admin')->user()->id;
        $logedInUserRole = \Auth::guard('admin')->user()->role_id;
        $validator = Validator::make($request->all(), [ 
            'task_id' => 'required',
            ]);

        if ($validator->fails()) { 
          return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
         }

        if($logedInUserRole==4)
        {
            $sqlTask =  TaskLists::whereId($request->task_id)->first();
            $start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
            $end_date    = date('Y-m-d h:i:s', strtotime($request->modified_end_date));

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
                $checkFreeDate = TaskDetails::whereTaskDate(date('o-m-d',$day_to_display))->whereServiceId($sqlTask->service_allocation_id)->whereUserId($sqlTask->user_id)->first();
                if(!$checkFreeDate)
                {
                    $arr_days[] = date('o-m-d',$day_to_display);
                    $counter++;
                }
                
                else
                {
                    session()->flash('error', 'Task already been added for this user on '.date("o-m-d",$day_to_display).' for this Service.');
                    return response()->json(['status'=>false],200);
                }
                
            }
            

            if($sqlTask->created_by == $logedInUser)
            { 

            $sqlTask->start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
            $sqlTask->end_date    = date('Y-m-d h:i:s', strtotime($request->modified_end_date));
            $sqlTask->updated_at  = date('Y-m-d H:i:s');
            $sqlTask->updated_by  = $logedInUser;
            $save = $sqlTask->save(); 

            $sqlTaskDetails = TaskDetails::whereServiceId($sqlTask->service_allocation_id)->whereTaskId($request->task_id)->whereUserId($sqlTask->user_id)->delete();

            $tempTaskDetails = new TaskDetails;

            $tempTaskDetails->service_id = $sqlTask->service_allocation_id;
            $tempTaskDetails->task_id = $request->task_id;
            $tempTaskDetails->user_id = $sqlTask->user_id;
            $tempTaskDetails->task_date = date('o-m-d',$date_from);
            $tempTaskDetails->created_by = auth()->guard('admin')->id();
            $tempTaskDetails->save();

            $array_all_days = array();
            $day_passed = ($date_to - $date_from); //seconds
            $day_passed = ($day_passed/86400); //days

            $counter = 1;
            $day_to_display = $date_from;

                foreach ($arr_days as $key => $value) {

                    $tempTaskDetails = new TaskDetails;
                    $tempTaskDetails->service_id = $sqlTask->service_allocation_id;
                    $tempTaskDetails->task_id = $request->task_id;
                    $tempTaskDetails->user_id = $sqlTask->user_id;
                    $tempTaskDetails->task_date = $value;
                    $tempTaskDetails->created_by = auth()->guard('admin')->id();
                    $tempTaskDetails->save();
                }

                return response()->json(['status'=>true],200);
            }
            
            else
            {
                $request->session()->flash('error', 'You are not the authorised person to modified this task!');
                return response()->json(['status'=>true],200);
            }    
        }

        else
        {
            $sqlTask =  TaskLists::whereId($request->task_id)->first();   

            $sqlTaskDetails = TaskDetails::whereTaskId($request->task_id)->first();    

            if(!$sqlTaskDetails)  
            {
                $start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
                $end_date    = date('Y-m-d h:i:s', strtotime($request->modified_end_date));

                $sqlTask->start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
                $sqlTask->end_date    = date('Y-m-d h:i:s', strtotime($request->modified_end_date));
                $sqlTask->updated_at  = date('Y-m-d H:i:s');
                $sqlTask->updated_by  = $logedInUser;
                $save = $sqlTask->save();
                
                $request->session()->flash('success-message', 'Task has been modified successfully');
                return response()->json(['status'=>true],200);
            }
            else
            {
                $request->session()->flash('error', 'Task has already been assigned to labour! Can not be modified now!');
                return response()->json(['status'=>false],200);
            }
        }

        
    }
}
