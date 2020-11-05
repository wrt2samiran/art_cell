<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User,Country,State,City,TaskLists, TaskDetails, ServiceAllocationManagement, Property, Contract, ContractService};

use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use DB;

class TaskManagementController extends Controller
{

    private $view_path='admin.task_management';

    
    /*****************************************************/
    # TaskManagementController
    # Function name : taskCreate
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Adding new Task by property owner, property manager, super admin
    # Params        : Request $request
    /*****************************************************/
    public function taskCreate(Request $request) {

        $this->data['page_title']     = 'Create Task';
        $logedInUserRole = \Auth::guard('admin')->user()->role_id;
        $logedInUser = \Auth::guard('admin')->user()->id;
    
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

                    return redirect()->route('admin.task_management.calendar',$request->service_allocation_id)->withErrors($Validator)->withInput();
                    
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
                            return redirect()->route('admin.task_management.calendar',$request->service_allocation_id)->with('error', 'Task already been added for this user on '.date("o-m-d",$day_to_display).' for this Service.');
                        }
                         
                    }
                 
                    $new = new TaskLists;
                    $new->service_allocation_id = $request->service_allocation_id;
                    $new->service_id            = $request->service_id;
                    $new->property_id           = $request->property_id;
                    $new->country_id            = $request->country_id;
                    $new->state_id              = $request->state_id;
                    $new->city_id               = $request->city_id;
                    $new->user_id               = $request->labour_id;
                    $new->task_title            = $request->task_title;
                    $new->task_desc             = $request->task_desc;
                   
                    $new->start_date            = date("Y-m-d", strtotime($rangeDate[0]));
                    $new->end_date              = date("Y-m-d", strtotime($rangeDate[1]));
                    $new->status                = '0';
                    $new->created_by            = auth()->guard('admin')->id();
                    $new->updated_by            = auth()->guard('admin')->id();
                    $new->created_at            = date('Y-m-d H:i:s');
                    
                    $save = $new->save();

                    
                    $temp = new TaskDetails;
                    $temp->service_allocation_id = $request->service_allocation_id;
                    $temp->service_id = $request->service_id;
                    $temp->task_id = $new->id;
                    $temp->user_id =$request->labour_id;
                    $temp->task_date = date('o-m-d',$date_from);
                    $temp->created_by = auth()->guard('admin')->id();
                    $temp->save();

                    $array_all_days = array();
                    $day_passed = ($date_to - $date_from); //seconds
                    $day_passed = ($day_passed/86400); //days

                    $counter = 1;
                    $day_to_display = $date_from;

                    foreach ($arr_days as $key => $value) {

                        $temp = new TaskDetails;
                        $temp->service_id = $request->service_id;
                        $temp->service_allocation_id = $request->service_allocation_id;
                        $temp->task_id = $new->id;
                        $temp->user_id =$request->labour_id;
                        $temp->task_date = $value;
                        $temp->created_by = auth()->guard('admin')->id();
                        $temp->save();
                    }

                        $request->session()->flash('alert-success', 'Task has been added successfully');
                        return redirect()->route('admin.task_management.calendar',$request->service_allocation_id);
                    
                }
            }

            if($logedInUserRole==2)
            {
                $this->data['contract_list']=Contract::whereIsActive('1')->whereNull('deleted_at')->whereCustomerId($logedInUser)->orderBy('id','ASC')->get();
            }
            else
            {
                $this->data['contract_list']=Contract::whereIsActive('1')->whereNull('deleted_at')->orderBy('id','ASC')->get();
            }

            return view($this->view_path.'.add',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.task_management.calendar')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # TaskManagementController
    # Function name : getContractData
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Get Task Related Data List
    # Params        : Request $request
    /*****************************************************/

   

    public function getContractData(Request $request)
    {
        $logedInUser = \Auth::guard('admin')->user()->id;
         $validator = Validator::make($request->all(), [ 
            'contract_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }
        

        $sqlProperty = Contract::with('property')->whereId($request->contract_id)->whereIsActive('1')->whereNull('deleted_at')->first();

        $sqlService = ContractService::with('service')->whereContractId($request->contract_id)->get();
        $restContractService = array();
        foreach ($sqlService as $key => $serviceValue) {
            if(($serviceValue->service_type=='General' || $serviceValue->service_type=='Free') and ($serviceValue->number_of_times_already_used=='NULL' || $serviceValue->number_of_times_already_used == ''))
            {
                $restContractService[] = $serviceValue->id;
            }
            elseif($serviceValue->service_type=='Maintenance')
            {
                $sqlTaskData = TaskLists::whereContractId($serviceValue->contract_id)->whereServiceId($serviceValue->service_id)->whereNull('deleted_at')->first();
                if($sqlTaskData)
                {
                   // if(strtotime($sqlTaskData->start_date) < strtotime('-"'.$serviceValue->interval_days.'" days')) {
                    $addedOn = new \DateTime($sqlTaskData->start_date);
                    $now = new \DateTime();

                    if($addedOn->diff($now)->days > $serviceValue->interval_days) {
                        
                      
                        $restContractService[] = $serviceValue->id;
                    }
                }
                else
                {
                    $restContractService[] = $serviceValue->id;
                }
                

                //where('start_date', '<=', Carbon::now()->subDays($serviceValue->interval_days)->toDateTimeString())
            }
            elseif($serviceValue->service_type=='On Demand' and ($serviceValue->number_of_times_already_used=='NULL' || $serviceValue->number_of_times_already_used < $serviceValue->number_of_time_can_used))
            {
                $restContractService[] = $serviceValue->id;
            }
        }

        $sqlServiceFinal = ContractService::with('service')->whereContractId($request->contract_id)->whereIn('id', $restContractService)->get();

        $sqlCity    = City::whereId($sqlProperty->property->city_id)->whereIsActive('1')->first();
        $sqlState   = State::whereIsActive('1')->where('id', $sqlProperty->property->state_id)->first();
        $sqlCountry = Country::whereIsActive('1')->where('id', $sqlProperty->property->country_id)->first();
        
        return response()->json(['status'=>true, 'sqlProperty'=>$sqlProperty, 'sqlCity'=>$sqlCity, 'sqlState'=>$sqlState, 'sqlCountry'=>$sqlCountry, 'sqlService'=>$sqlServiceFinal],200);
    }

    

    /*****************************************************/
    # TaskManagementController
    # Function name : Calendar
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Showing Task Calendar
    # Params        : Request $request
    /*****************************************************/
       
    public function calendar(Request $request, $id){
        //dd($request->all());
        $this->data['page_title']='Task calendar';
        $logedInUser = \Auth::guard('admin')->user()->id;

        if($request->ajax()){

            $task_list=TaskLists::orderBy('id','Desc');
            return Datatables::of($task_list)
            ->editColumn('created_at', function ($task_list) {
                return $task_list->created_at ? with(new Carbon($task_list->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($task_list){
                if($task_list->status=='1'){
                   //$message='deactivate';
                   return '<span class="btn btn-block btn-outline-success btn-sm">Overdue</a>';
                    
                }else if($task_list->status=='0'){
                  // $message='activate';
                   return '<span class="btn btn-block btn-outline-success btn-sm">Pending</a>';
                }
                else
                {
                    return '<span class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($task_list){
                $delete_url=route('admin.task_management.delete',$task_list->id);
                $details_url=route('admin.task_management.show',$task_list->id);
                $add_url=route('admin.task_management.calendar',$task_list->id);

                return '<a title="View Task Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Add Task" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>&nbsp;&nbsp;<a title="Delete city" href="javascript:delete_city('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        $service_data=ServiceAllocationManagement::with('service')->whereStatus('A')->where('work_status', '<>','2')->whereServiceProviderId($logedInUser)->whereId($id)->first();

        $sqlProperty = DB::table('service_allocation_management')
        ->join('contracts', 'contracts.id', '=', 'service_allocation_management.contract_id')
        ->join('properties', 'properties.id', '=', 'contracts.property_id')
        ->where('service_allocation_management.service_provider_id', $logedInUser)
        ->where('service_allocation_management.id', $id)
        ->first();

        $sqlCity    = City::whereIsActive('1')->where('id', $sqlProperty->city_id)->first();
        $sqlState   = State::whereIsActive('1')->where('id', $sqlCity->state_id)->first();
        $sqlCountry = Country::whereIsActive('1')->where('id', $sqlState->country_id)->first();

        $labour_list= User::whereStatus('A')->whereRoleId('5')->whereCreatedBy($logedInUser)->get();


        $this->data['service_data']          = $service_data;
        $this->data['task_id']               = $id;
        $this->data['property_data']         = $sqlProperty;
        $this->data['city_data']             = $sqlCity;
        $this->data['state_data']            = $sqlState;
        $this->data['country_data']          = $sqlCountry;
        $this->data['service_allocation_id'] = $id;

        $this->data['labour_list']           = $labour_list;

        if ($request->has('search')) {
            
            $sqlCalendar = TaskLists::where(function ($q) use ($request) {
                
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
            $sqlTask=TaskLists::whereServiceAllocationId($id)->orderBy('id','Desc')->get();
        }

        $this->data['tasks_list']  = $sqlTask;
        $this->data['request'] = $request;

        return view($this->view_path.'.calendar',$this->data);
    }



    /*****************************************************/
    # TaskManagementController
    # Function name : List
    # Author        :
    # Created Date  : 20-10-2020
    # Purpose       : Showing Task List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){


        $this->data['page_title']='Task Management List';
        $logedInUser = \Auth::guard('admin')->user()->id;
        $logedInUserRole = \Auth::guard('admin')->user()->role_id;
        if($logedInUserRole !=5)
        {
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
               
             
                if($logedInUser==$tasks->user_id){
                     $add_url=route('admin.task_management.labourTaskList',$tasks->id);

                     $action_buttons =$action_buttons.'<a title="Labour Task List" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>';
                }
                
                if(\Auth::guard('admin')->user()->role_id==5){
                    $details_url = route('admin.task_management.dailyTask',$tasks->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Daily Task List" id="details_task" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                 }

                else{
                    $details_url = route('admin.task_management.show',$tasks->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Show Task" id="details_task" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                } 

               
                if($logedInUser==$tasks->created_by and $tasks->task_assigned=='N'){

                    // $edit_url=route('admin.task_management.edit',$tasks->id);
                    // $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Task" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';

                    $delete_url=route('admin.task_management.delete',$tasks->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete contract" href="javascript:delete_task('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
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
        else
        {
         return $this->labourTaskList($request, $logedInUser);
        }
    }

   /*****************************************************/
    # TaskManagementController
    # Function name : taskAdd
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Adding new Task
    # Params        : Request $request
    /*****************************************************/



    public function taskAdd(Request $request) {

       // dd($request->all());
        $logedInUser = \Auth::guard('admin')->user()->id;

        $this->data['page_title']     = 'Add Task';
    
        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'contract_id'   => 'required',
                    'service_id'    => 'required',
                    'property_id'   => 'required',
                    'country_id'    => 'required',
                    'state_id'      => 'required',
                    'city_id'       => 'required',
                    'date_range'    => 'required',
                    'task_title'    => 'required|min:2|max:255',
                );
                $validationMessages = array(
                    'contract_id.required'  => 'Please select contract',
                    'service_id.required'   => 'Please select service',
                    'property_id.required'  => 'Please select property',
                    'country_id.required'   => 'Please select country',
                    'state_id.required'     => 'Please select state',
                    'city_id.required'      => 'Please select city',
                    'date_range.required'   => 'Please set date range',
                    'task_title.required'   => 'Please enter Task title',
                    'task_title.min'        => 'Task title should be should be at least 2 characters',
                    'task_title.max'        => 'Task title should not be more than 255 characters',
                    
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {

                    return redirect()->route('admin.task_management.create',$request->service_allocation_id)->withErrors($Validator)->withInput();
                    
                } else {
                    
                    $rangeDate = (explode("-",$request->date_range));     
                    $start_date = \Carbon\Carbon::parse($rangeDate['0']);
                    $end_date = \Carbon\Carbon::parse($rangeDate['1']);

                    $date_from = strtotime($start_date);
                    $date_to = strtotime($end_date);

                    $sqlContract = Contract::findOrFail($request->contract_id);
                    $sqlContractService = ContractService::where('contract_id', $request->contract_id)->whereServiceId($request->service_id)->first();
                    
                    $task=TaskLists::create([
                        'contract_id'=>$request->contract_id,
                        'property_id'=>$request->property_id,
                        'service_id' => $request->service_id,
                        'contract_service_id' => $sqlContractService->id,
                        'country_id' =>$request->country_id,
                        'state_id' =>$request->state_id,
                        'city_id' =>$request->city_id,
                        'user_id'=>$sqlContract->service_provider_id,
                        'task_title'=>$request->task_title,
                        'task_desc' => $request->task_desc,
                        'start_date'=>date("Y-m-d", strtotime($start_date)),
                        'end_date'=>date("Y-m-d", strtotime($end_date)),
                        'created_by'=>$logedInUser,
                        'updated_by'=>$logedInUser
                    ]);


                    
                    if ($sqlContractService) {
                         if($sqlContractService->service_type=='General')  
                            {
                                $sqlContractService->number_of_times_already_used = '1';
                                $sqlContractService->updated_by = $logedInUser;
                                $sqlContractService->save();
                            }
                          else
                            {
                                $sqlContractService->number_of_times_already_used = ($sqlContractService->number_of_times_already_used+1);
                                $sqlContractService->updated_by = $logedInUser;
                                $sqlContractService->save();
                            }   
                    }

                        $request->session()->flash('alert-success', 'Task has been added successfully');
                        return redirect()->route('admin.task_management.list');
                    
                }
            }

            $country_list=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['country_list']=$country_list;
            return view($this->view_path.'.add',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.task_management.calendar')->with('error', $e->getMessage());
        }
    }    

    /*****************************************************/
    # TaskManagementController
    # Function name : taskEdit
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Editing task
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $this->data['page_title']     = 'Edit Task';
        $this->data['panel_title']    = 'Edit Task';

        $logedInUserRole = \Auth::guard('admin')->user()->role_id;
        $logedInUser = \Auth::guard('admin')->user()->id;

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
            
            
            if($logedInUserRole==2)
            {
                $this->data['contract_list']=Contract::whereIsActive('1')->whereNull('deleted_at')->whereCustomerId($logedInUser)->orderBy('id','ASC')->get();
            }
            else
            {
                $this->data['contract_list']=Contract::whereIsActive('1')->whereNull('deleted_at')->orderBy('id','ASC')->get();
            }



            $this->data['property_list']=Property::with('parent_user')->whereIsActive('1')->whereNull('deleted_at')->orderBy('id','ASC')->get();



            return view($this->view_path.'.edit',$this->data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.service_management.calendar')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # TaskManagementController
    # Function name : change_status
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Change task status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.task_management.daily-task');
            }
            $details = TaskDetails::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 0) {
                    
                    $details->status = '2';
                    $details->save();
                        
                    $request->session()->flash('alert-success', 'Status updated successfully');                 
                     } else if ($details->status == 0) {
                    $details->is_active = '1';
                    $details->save();
                    $request->session()->flash('alert-success', 'Status updated successfully');
                   
                } else {
                    $request->session()->flash('alert-danger', 'Something went wrong');
                    
                }
                return redirect()->back();
            } else {
                return redirect()->route('admin.service_management.calendar')->with('error', 'Invalid city');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.service_management.calendar')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # TaskManagementController
    # Function name : delete
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : delete task
    # Params        : Request $request
    /*****************************************************/
    public function delete($id)
    {
        $task=TaskLists::where('task_assigned', 'N')->whereId($id)->first();
        if($task)
        {
            $contractService=ContractService::whereId($task->contract_service_id)->first();

            if($contractService)
            {
                if($contractService->service_type=='General')
                {
                    $contractService->number_of_times_already_used = 'Null';
                }
                else
                {
                    if($contractService->number_of_times_already_used>1)
                    {
                        $contractService->number_of_times_already_used = ($contractService->number_of_times_already_used-1);
                    }
                    else
                    {
                        $contractService->number_of_times_already_used = 'Null';   
                    }
                    
                }
               
            }

            $task->update([
            'deleted_by'=>auth()->guard('admin')->id()
            ]);
            $task->delete();
            return response()->json(['message'=>'Task successfully deleted.']);
        }
        else
        {
            $task->update([
            'deleted_by'=>auth()->guard('admin')->id()
            ]);
            $task->delete();
            return response()->json(['message'=>'Task alreday been assigned, can not be deleted!']);
        }

    }
    

    /*****************************************************/
    # TaskManagementController
    # Function name : show
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Showing Task details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $tasks=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('contract_services')->whereId($id)->first();
        $this->data['page_title']='Task Details';
        $this->data['task_list']=$tasks;
        return view($this->view_path.'.show',$this->data);
    }
    // /*****************************************************/
    // # TaskManagementController
    // # Function name : getCities
    // # Author        :
    // # Created Date  : 16-10-2020
    // # Purpose       : Get State wise City List
    // # Params        : Request $request
    // /*****************************************************/

    // public function getCities(Request $request)
    // {
    //      $validator = Validator::make($request->all(), [ 
    //         'state_id' => 'required',
    //         ]);

    //        if ($validator->fails()) { 
    //           return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
    //         }

    //     $allCities = City::whereIsActive('1')->where('state_id', $request->state_id)->get();
    //     return response()->json(['status'=>true, 'allCities'=>$allCities,],200);
    // }

    /*****************************************************/
    # TaskManagementController
    # Function name : dailyTask
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Showing Labour Daily Task List
    # Params        : Request $request
    /*****************************************************/

    public function dailyTask(Request $request, $id){

        //dd($request->all());
        $this->data['page_title']='Daily Task List';
        

        if($request->ajax()){

            $task_detail_list=TaskDetails::with('task')->with('service')->where('task_id', $id)->orderBy('id','Desc');
            return Datatables::of($task_detail_list)
            ->editColumn('created_at', function ($task_detail_list) {
                return $task_detail_list->created_at ? with(new Carbon($task_detail_list->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($task_detail_list){
                if($task_detail_list->status=='1'){
                   //$message='deactivate';
                   return '<span class="btn btn-block btn-outline-denger btn-sm">Overdue</a>';
                    
                }else if($task_detail_list->status=='0'){
                   $message='complete';
                   if($task_detail_list->user_feedback==''){
                        return '<span class="btn btn-block btn-outline-warning btn-sm">Pending</a>';
                   }
                   else{
                        return '<a title="Click to Complete the daily task" href="javascript:change_status('."'".route('admin.task_management.change_status',$task_detail_list->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Pending</a>';
                   }
                   
                   
                }
                else
                {
                    return '<span class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })

            ->addColumn('action',function($task_detail_list){
                $logedInUser = \Auth::guard('admin')->user()->id;
                $details_url=route('admin.task_management.dailyTaskShow',$task_detail_list->id);

                if($task_detail_list->user_feedback==''){
                    if($task_detail_list->user_id==$logedInUser){
                        $today = date('Y-m-d');
                        if($today == $task_detail_list->task_date)
                        {
                            return '<a title="Add Task Feedback" href="javascript:void(0)" onclick="addFeedback('.$task_detail_list->id.')"><i class="fas fa-head-side-cough"></i></a>&nbsp;&nbsp;';
                        }
                        else
                        {
                            return 'Permission Denied';
                        }
                        
                    }
                    else{
                        return 'No Access';
                    }
                    
                }
                else{
                    // return '<a title="View Daily Task Update" href="javascript:void(0)" onclick="showFeedback('.$task_detail_list->id.')"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;';
                    return '<a title="View Daily Task Update" id="details_task_feedback" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }
                //return '<button data-id="'.$task_detail_list->id.'" data-toggle="modal"  class="btnModal"> Edit </button>';
                // 
                
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        $this->data['task_list_data']=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('userDetails')->findOrFail($id);
        $this->data['request'] = $request;
       
        return view($this->view_path.'.daily-task-list',$this->data);
    }

    /*****************************************************/
    # TaskManagementController
    # Function name : taskFeedback
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Labour Daily Task Feedback store
    # Params        : Request $request
    /*****************************************************/

    public function taskFeedback(Request $request) {

        //dd($request->all());
        $logedInUser = \Auth::guard('admin')->user()->id;
        $sqlTaskData = TaskDetails::findOrFail($request->task_details_id);
        $this->data['page_title']     = 'Add Task Feedback';
    
        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'user_feedback' => 'required|max:5000',
                );
                $validationMessages = array(
                    'user_feedback.required'    => 'Please enter your Feedback',
                    'user_feedback.max'         => 'Feedback should not be more than 5000 characters',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {

                    return redirect()->route('admin.task_management.dailyTask',$sqlTaskData->task_id)->withErrors($Validator)->withInput();
                    
                } else {
                    
                    $sqlTaskData->user_feedback  = $request->user_feedback;
                    $sqlTaskData->updated_at     = date('Y-m-d H:i:s');
                    $sqlTaskData->updated_by     = $logedInUser;
                    $save                        = $sqlTaskData->save(); 

                   
                    $request->session()->flash('alert-success', 'Task Feedback has been added successfully');
                    return redirect()->route('admin.task_management.labourTaskList',$sqlTaskData->task_id);
                    
                }
            }

           
        } catch (Exception $e) {
            return redirect()->route('admin.task_management.labourTaskList',$sqlTaskData->task_id)->with('error', $e->getMessage());
        }
    }


    /*****************************************************/
    # TaskManagementController
    # Function name : dailyTaskShow
    # Author        :
    # Created Date  : 29-10-2020
    # Purpose       : Showing Daily Task details
    # Params        : Request $request
    /*****************************************************/

    public function dailyTaskShow($id){
        $tasksDetails=TaskDetails::with('task')->with('userDetails')->whereId($id)->first();
        $this->data['page_title']='Task Details';
        $this->data['task_data']=$tasksDetails;
        return view($this->view_path.'.show-daily-task-feedback',$this->data);
    }


    /*****************************************************/
    # TaskManagementController
    # Function name : updateTask
    # Author        :
    # Created Date  : 29-10-2020
    # Purpose       : Update Task Data
    # Params        : Request $request
    /*****************************************************/

    
    public function updateTask(Request $request)
    {
        //$request->calendar_id;
        
        $logedInUser = \Auth::guard('admin')->user()->id;
        $validator = Validator::make($request->all(), [ 
            'task_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

         
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



    /*****************************************************/
    # TaskManagementController
    # Function name : labourTaskList
    # Author        :
    # Created Date  : 02-11-2020
    # Purpose       : Labour Task List
    # Params        : Request $request
    /*****************************************************/
    
    public function labourTaskList(Request $request, $id){

        //dd($request->all());
        $this->data['page_title']='Labour Task List';
        $logedInUser = \Auth::guard('admin')->user()->id;
        $logedInUserRole = \Auth::guard('admin')->user()->role_id;

        if($request->ajax()){
            if($logedInUserRole!=5){
                $task_detail_list=TaskDetails::with('task')->with('service')->with('userDetails')->where('task_id', $id)->orderBy('id','Desc');
            }
            else{
                $task_detail_list=TaskDetails::with('task')->with('service')->with('userDetails')->where('user_id', $logedInUser)->orderBy('id','Desc');
            }
            return Datatables::of($task_detail_list)
            ->editColumn('created_at', function ($task_detail_list) {
                return $task_detail_list->created_at ? with(new Carbon($task_detail_list->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($task_detail_list){
                if($task_detail_list->status=='1'){
                   //$message='deactivate';
                   return '<span class="btn btn-block btn-outline-denger btn-sm">Overdue</a>';
                    
                }else if($task_detail_list->status=='0'){
                   $message='complete';
                   if($task_detail_list->user_feedback==''){
                        return '<span class="btn btn-block btn-outline-warning btn-sm">Pending</a>';
                   }
                   else{
                        return '<a title="Click to Complete the daily task" href="javascript:change_status('."'".route('admin.task_management.change_status',$task_detail_list->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Pending</a>';
                   }
                   
                   
                }
                else
                {
                    return '<span class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($task_detail_list){
                $logedInUser = \Auth::guard('admin')->user()->id;
                $today = date('Y-m-d');
                $action_buttons='';
                
                if($logedInUser == $task_detail_list->user_id)
                {
                    if($task_detail_list->user_feedback==''){
                        if($task_detail_list->user_id==$logedInUser){
                            
                            if($today >= $task_detail_list->task_date)
                            {
                                return '<a title="Add Task Feedback" href="javascript:void(0)" onclick="addFeedback('.$task_detail_list->id.')"><i class="fas fa-head-side-cough"></i></a>&nbsp;&nbsp;';
                            }
                            else
                            {
                                return 'Permission Denied';
                            }
                            
                        }
                        else{
                            return 'No Access';
                        }
                    
                    }
                    else{
                         $details_url = route('admin.task_management.dailyTaskShow',$task_detail_list->id);
                        return '<a title="View Daily Task Update" id="details_task_feedback" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                    }
                }
                else
                {
                    
                    if($logedInUser==$task_detail_list->user_id){
                         $add_url=route('admin.task_management.labourTaskList',$task_detail_list->id);

                         $action_buttons =$action_buttons.'<a title="Labour Task List" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>';
                    }
                  
                    $details_url = route('admin.task_management.dailyTaskShow',$task_detail_list->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Show Task" id="details_task" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                  

                    if($logedInUser==$task_detail_list->created_by and $task_detail_list->task_date>$today){
                        $edit_url = route('admin.task_management.editDailyTask',$task_detail_list->id);
                        $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Task" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                   
                        $delete_url=route('admin.task_management.deleteLabourTask',$task_detail_list->id);
                        $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete contract" href="javascript:delete_task('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                    }

                    if($action_buttons==''){
                        $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                    } 
                    return $action_buttons;
                }
                
            })

            ->rawColumns(['action','status'])
            ->make(true);
        }

        $this->data['task_list_data']=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('userDetails')->findOrFail($id);
        $this->data['request'] = $request;
       if($logedInUserRole !=5)
       {
            return view($this->view_path.'.daily-task-list',$this->data);
       }
       else
       {
            return view($this->view_path.'.labour-task-list',$this->data);
       }
        
    }

    /*****************************************************/
    # TaskManagementController
    # Function name : labourTaskCreate
    # Author        :
    # Created Date  : 02-11-2020
    # Purpose       : Creating Labour Task
    # Params        : Request $request
    /*****************************************************/
       
    public function labourTaskCreate($id){
        //dd($request->all());

        $this->data['page_title']='Add Labour Task';
        $logedInUser = \Auth::guard('admin')->user()->id;

        $this->data['sqltaskData']=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->whereId($id)->whereIsDeleted('N')->first();

        $this->data['labour_list']= User::whereRoleId('5')->whereCreatedBy($logedInUser)->orderBy('name', 'Desc')->get();
        $labour_list= User::whereStatus('A')->whereRoleId('5')->whereCreatedBy($logedInUser)->get();
        return view($this->view_path.'.labour-task-add',$this->data);
    }


    
    /*****************************************************/
    # TaskManagementController
    # Function name : taskAssign
    # Author        :
    # Created Date  : 02-11-2020
    # Purpose       : Assigning new Task to Labour
    # Params        : Request $request
    /*****************************************************/
    public function taskAssign(Request $request) {

        $this->data['page_title']     = 'Assign Task';
        

        try
        {
            
            $validationCondition = array(
                'task_id'           => 'required',
                'service_id'        => 'required',
                'date_range'        => 'required',
                
                'user_id'           => 'required',
                'task_description'  => 'required|min:10|max:5000',
            );
            $validationMessages = array(
                'task_id.required'             => 'Please select Task',
                'service_id.required'          => 'Please select service',
                'user_id.required'             => 'Please select user',
                'date_range.required'          => 'Please select date',
                'task_description.required'    => 'Task Description is required',
                'task_description.min'         => 'Task Description should be should be at least 10 characters',
                'task_description.max'         => 'Task Description should not be more than 5000 characters',
            );

            $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {

                return redirect()->route('admin.task_management.labourTaskCreate', $request->task_id)->withErrors($Validator)->withInput();
                
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
                    $checkFreeDate = TaskDetails::whereTaskDate(date('o-m-d',$day_to_display))->whereServiceId($request->service_id)->whereUserId($request->user_id)->first();
                    if(!$checkFreeDate)
                    {
                        $arr_days[] = date('o-m-d',$day_to_display);
                        $counter++;
                    }
                    
                    else
                    {
                        return redirect()->route('admin.task_management.labourTaskList', $request->task_id)->with('error', 'Task already been added for this user on '.date("o-m-d",$day_to_display).' for this Service.');
                    }
                }
              
                
                $addTaskDetails = TaskDetails::create([
                    'service_id'            => $request->service_id,
                    'task_id'               => $request->task_id,
                    'user_id'               => $request->user_id,
                    'task_date'             => date('o-m-d',$date_from),
                    'task_description'      => $request->task_description,
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
                        'service_id'            => $request->service_id,
                        'task_id'               => $request->task_id,
                        'user_id'               => $request->user_id,
                        'task_date'             => $value,
                        'task_description'      => $request->task_description,
                        'created_by'            => auth()->guard('admin')->id(),
                    ];

                }

                $task = TaskLists::whereId($request->task_id)->update([
                'task_assigned'=>'Y'
                ]);

                TaskDetails::insert($task_details_data_array);
                $request->session()->flash('success', 'Task has been added successfully');
                return redirect()->route('admin.task_management.labourTaskList', $request->task_id);                
            }

        } catch (Exception $e) {
            return redirect()->route('admin.task_management.labourTaskList', $request->task_id)->with('error', $e->getMessage());
        }
    }

    

     /*****************************************************/
    # TaskManagementController
    # Function name : editDailyTask
    # Author        :
    # Created Date  : 02-11-2020
    # Purpose       : Editing Labour Daily Task
    # Params        : Request $request
    /*****************************************************/
    public function editDailyTask(Request $request, $id = null) {
       
        $this->data['page_title']     = 'Edit Assigned Task';
        $this->data['panel_title']    = 'Edit Assigned Task';

        $logedInUser = \Auth::guard('admin')->user()->id;

        try
        {          
            $data['id'] = $id; 
            $details = TaskDetails::with('task')->with('service')->whereId($id)->first();
            $this->data['labour_list']= User::whereRoleId('5')->whereCreatedBy($logedInUser)->orderBy('name', 'Desc')->get();

            if ($request->isMethod('POST')) {
                
                if ($id == null) {
                    return redirect()->route('admin.task_management.labourTaskList', $details->task_id);
                }
                $validationCondition = array(
                'task_id'           => 'required',
                'service_id'        => 'required',
                'date_range'        => 'required',
                
                'user_id'           => 'required',
                'task_description'  => 'required|min:10|max:5000',
                );
                $validationMessages = array(
                    'task_id.required'             => 'Please select Task',
                    'service_id.required'          => 'Please select service',
                    'user_id.required'             => 'Please select user',
                    'date_range.required'          => 'Please select date',
                    'task_description.required'    => 'Task Description is required',
                    'task_description.min'         => 'Task Description should be should be at least 10 characters',
                    'task_description.max'         => 'Task Description should not be more than 5000 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {

                    

                    $rangeDate = (explode("-",$request->date_range));     
                    $start_date = \Carbon\Carbon::parse($rangeDate['0']);
                    $end_date = \Carbon\Carbon::parse($rangeDate['1']);

                    $date_from = strtotime($start_date);
                    $date_to = strtotime($end_date);

                    $checkFreeDate = TaskDetails::whereTaskDate(date('o-m-d',$date_from))->whereServiceId($request->service_id)->whereUserId($request->user_id)->where('id', '<>',$id)->first();
                    if(!$checkFreeDate)
                    {
                        $deleteTask = TaskDetails::whereId($id)->delete();
                        $addTaskDetails = TaskDetails::create([
                        'service_id'            => $request->service_id,
                        'task_id'               => $request->task_id,
                        'user_id'               => $request->user_id,
                        'task_date'             => date('o-m-d',$date_from),
                        'task_description'      => $request->task_description,
                        'created_by'            => auth()->guard('admin')->id(),
                        ]);                       
                        if ($addTaskDetails) {
                            $request->session()->flash('success', 'Labour Task has been updated successfully');
                            return redirect()->route('admin.task_management.labourTaskList', $request->task_id);
                        } else {
                            $request->session()->flash('error', 'An error occurred while updating the Labour Task');
                            return redirect()->back();
                        }
                    }
                    
                    else
                    {
                        return redirect()->route('admin.task_management.labourTaskList', $request->task_id)->with('error', 'Task already been added for this user on '.date("o-m-d",$date_from).' for this Service.');
                    }                   
                }
            }
            
            return view($this->view_path.'.labour-task-edit',$this->data)->with(['sqltaskData' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.task_management.labourTaskList', $id)->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # TaskManagementController
    # Function name : deleteLabourTask
    # Author        :
    # Created Date  : 02-11-2020
    # Purpose       : Delete Labour Daily Task
    # Params        : Request $request
    /*****************************************************/
    public function deleteLabourTask($id)
    {

        $checkTaskDetails = TaskDetails::whereId($id)->first();
        $sqlTotalTask = TaskDetails::whereTaskId($checkTaskDetails->task_id)->get();
        if(count($sqlTotalTask)==1)
        {
            $task = TaskLists::whereId($checkTaskDetails->task_id)->update([
            'task_assigned'=>'N'
            ]);
            $task->save();
            $task=TaskDetails::whereId($id)->delete();
        }
        else
        {
           $task=TaskDetails::whereId($id)->delete();
        }
        
        return response()->json(['message'=>'Labour Task successfully deleted.']);

    }
}
