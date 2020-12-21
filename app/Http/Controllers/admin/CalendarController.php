<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User,Country,State,City, WorkOrderLists, TaskLists, TaskDetails, ServiceAllocationManagement, Property, Contract, Service, WorkOrderSlot, ContractServiceDate};
use Auth, Validator;
use Yajra\Datatables\Datatables;

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
        $logedInUserRole = \Auth::guard('admin')->user();
        $workOrder = array();
        $taskDetailsList = array();
       

        if($logedInUserRole->role->user_type->slug=='property-owner' || $logedInUserRole->role->user_type->slug=='property-manager')
        {
            $sqlContract=Contract::where(function($q) use ($logedInUser){
          
               
                //OR if logged in user is the property_owner or added as property manager of the property related to this contract 
                $q->whereHas('property',function($q1) use ($logedInUser){
                    $q1->where('property_owner',$logedInUser)
                    ->orWhere('property_manager',$logedInUser);
                });
            })

            ->when($request->contract_status_id,function($query) use($request){
                $query->where('contract_status_id',$request->contract_status_id);
            })->orderBy('id', 'Desc')->get();

            $this->data['sqlContract'] = $sqlContract;
     
       

            $sqlService=ServiceAllocationManagement::with('service')->with('contract')->whereStatus('A')->where('work_status', '<>','2')->whereServiceProviderId($logedInUser)->get();

            $labour_list= User::whereStatus('A')->whereRoleId('5')->whereCreatedBy($logedInUser)->get();
            $this->data['service_list'] = $sqlService;
            $this->data['labour_list']  = $labour_list;

            if ($request->has('search')) {
               
                $workOrder = WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'contract_service_dates', 'property.country', 'property.state', 'property.city'])->where(function ($q) use ($request) {
                    
                    if ($request->contract_id!='') {
                       
                        $q->where(function ($que) use ($request) {
                            $que->whereContractId( $request->contract_id);
                           
                         });                   

                    }

                    if ($request->work_order_id!='') {
                       
                        $q->where(function ($que) use ($request) {
                            $que->whereId( $request->work_order_id);
                           
                         });                   

                    }
                    

                    if ($request->contract_status!='') {
                        
                         if($request->contract_status ==3)
                            {
                                $contract_status = '0';
                            }
                         else
                            {
                                $contract_status = $request->contract_status;
                            }   

                        $q->where(function ($que) use ($contract_status) {
                            $que->where('status', $contract_status);
                           
                         });                   

                    } 

                    if ($request->contract_service!='') {
                        
                        $q->where(function ($que) use ($request) {
                            $que->where('service_id', $request->contract_service);
                           
                         });                   

                    }    

                })->get();

            } 
            else 
            {          
               // $sqlTask=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('contract_services')->whereCreatedBy($logedInUser)->orWhere('user_id',$logedInUser)->orderBy('id','Desc')->get();
                if($logedInUserRole ->role->user_type->slug=='property-owner' || $logedInUserRole ->role->user_type->slug=='property-manager')
                {
                    if(count($sqlContract)>0)
                    {
                        $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'contract_service_dates', 'property.country', 'property.state', 'property.city'])->whereContractId($sqlContract[0]->id)->get();
                    }
                    
                   // $this->data['work_order_all']  = $workOrderAll;
                }
                
            }

            if(count($workOrder)==0)
                {
                    $this->data['error'] = 'No data found!';
                }

            $this->data['serviceList'] = Service::whereIsActive(1)->get();

            //dd($sqlCalendar);
            $this->data['work_order_list']  = $workOrder;
            
            $this->data['slug'] = $logedInUserRole ->role->user_type->slug;
            $this->data['request'] = $request;

        
            return view($this->view_path.'.calendar',$this->data);
        }

        else if($logedInUserRole->role->user_type->slug=='service-provider')
        {
            $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'property.country', 'property.state', 'property.city'])->where('user_id',$logedInUser)->orderBy('id','Desc')->get();

            $taskList = TaskLists::with(['contract', 'task_details', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city'])->whereWorkOrderId($workOrder[0]->id)->get();

            $this->data['work_order_list'] = $workOrder;
            //$sqlService=ServiceAllocationManagement::with('service')->with('contract')->whereStatus('A')->where('work_status', '<>','2')->whereServiceProviderId($logedInUser)->get();

            $labour_list= User::whereStatus('A')->whereRoleId('5')->whereCreatedBy($logedInUser)->get();
            //$this->data['service_list'] = $sqlService;
            $this->data['labour_list']  = $labour_list;

            if ($request->has('search')) 
            {
               
                //$taskList = TaskLists::with(['contract', 'task_details', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city'])->where(function ($q) use ($request) {

                $taskList = TaskLists::with(['contract', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city'])->whereWorkOrderId($request->work_order_id)->where('status', '<>', 3)->orderBy('id', 'Desc')->get();
                //dd($taskList);
                if(count($taskList)>0)
                {   
                   
                    $taskDetailsList = TaskDetails::with('userDetails', 'task')->where(function ($q) use ($request, $taskList) {
                        
                        if ($request->task_id!='') {
                            
                            $q->where(function ($que) use ($request) {
                                $que->where('task_id', $request->task_id);
                               
                             });                   

                        } 
                        else
                        {
                            $q->where(function ($que) use ($taskList) {
                                $que->whereTaskId(@$taskList[0]->id);
                               
                             });

                            $request->task_id = @$taskList[0]->id;
                            
                        } 

                        if ($request->task_details_status!='') {
                            
                             if($request->task_details_status ==3)
                                {
                                    $contract_status = '0';
                                }
                             else
                                {
                                    $contract_status = $request->task_details_status;
                                }   

                            $q->where(function ($que) use ($contract_status) {
                                $que->where('status', $contract_status);
                               
                             });                   

                        } 

                        if ($request->contract_service!='') {
                            
                            $q->where(function ($que) use ($request) {
                                $que->where('service_id', $request->contract_service);
                               
                             });                   

                        } 

                        if ($request->labour_id!='') {
                            
                            $q->where(function ($que) use ($request) {
                                $que->where('user_id', $request->labour_id);
                               
                             });                   

                        }    

                    })->get();

                    if(count($taskDetailsList)==0)
                    {
                        $this->data['error'] = 'No data found!';
                    }
                }

                else
                {
                    $this->data['error'] = 'No data found!';
                }

            } 
            else 
            {
                    
                    $taskList = TaskLists::with(['contract', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city'])->whereWorkOrderId($workOrder[0]->id)->orderBy('id', 'Desc')->get();
                    
                    if(count($taskList)>0)
                    {
                        $taskDetailsList = TaskDetails::with('userDetails', 'task')->whereTaskId($taskList[0]->id)->get();
                    }
                    else
                    {
                        $this->data['error'] = 'No data found!';
                    }
                   
            }      

            $this->data['serviceList'] = Service::whereIsActive(1)->get();

            //dd($taskDetailsList);
            $this->data['task_list']  = $taskList;
            $this->data['task_details_list']  = $taskDetailsList;
            $this->data['slug'] = $logedInUserRole ->role->user_type->slug;
            $this->data['request'] = $request;
            return view($this->view_path.'.calendar-service-provider',$this->data);
        }  

        else if($logedInUserRole->role->user_type->slug=='super-admin' || $logedInUserRole->role->user_type->slug=='sub-admin') 
        {
            $this->data['sqlProperty'] = Property::whereIsActive(1)->whereNull('deleted_at')->orderBy('id', 'Desc')->get();   
            
            if($this->data['sqlProperty'])
            {
                $this->data['sqlContract'] = Contract::wherePropertyId($this->data['sqlProperty'][0]->id)->whereIsActive(1)->whereNull('deleted_at')->orderBy('id', 'Desc')->get();
                //$request->property_id =$this->data['sqlProperty'][0]->id;
            }


            $sqlService=ServiceAllocationManagement::with('service')->with('contract')->whereStatus('A')->where('work_status', '<>','2')->whereServiceProviderId($logedInUser)->get();

            $labour_list= User::whereStatus('A')->whereRoleId('5')->whereCreatedBy($logedInUser)->get();
            $this->data['service_list'] = $sqlService;
            $this->data['labour_list']  = $labour_list;

            if ($request->has('search')) {

                $this->data['sqlContract'] = Contract::wherePropertyId($request->property_id)->whereIsActive(1)->whereNull('deleted_at')->orderBy('id', 'Desc')->get();

                $workOrder = WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'contract_service_dates', 'property.country', 'property.state', 'property.city'])->where(function ($q) use ($request) {
                    
                    if ($request->property_id!='') {
                       
                        $q->where(function ($que) use ($request) {
                            $que->wherePropertyId( $request->property_id);
                           
                         });                   

                    }

                    if ($request->contract_id!='') {
                       
                        $q->where(function ($que) use ($request) {
                            $que->whereContractId( $request->contract_id);
                           
                         });                   

                    }

                    if ($request->work_order_id!='') {
                       
                        $q->where(function ($que) use ($request) {
                            $que->whereId( $request->work_order_id);
                           
                         });                   

                    }
                    

                    if ($request->contract_status!='') {
                        
                         if($request->contract_status ==3)
                            {
                                $contract_status = '0';
                            }
                         else
                            {
                                $contract_status = $request->contract_status;
                            }   

                        $q->where(function ($que) use ($contract_status) {
                            $que->where('status', $contract_status);
                           
                         });                   

                    } 

                    if ($request->contract_service!='') {
                        
                        $q->where(function ($que) use ($request) {
                            $que->where('service_id', $request->contract_service);
                           
                         });                   

                    }    

                })->get();

            } 
            else 
            {          
               if($this->data['sqlContract'])
               {
                    $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'contract_service_dates', 'property.country', 'property.state', 'property.city'])->whereContractId(@$this->data['sqlContract'][0]->id)->get();
               }
                
                    
            }

            if(count($workOrder)==0)
                {
                    $this->data['error'] = 'No data found!';
                }

            $this->data['serviceList'] = Service::whereIsActive(1)->get();

            //dd($sqlCalendar);
            $this->data['work_order_list']  = $workOrder;
            
            $this->data['slug'] = $logedInUserRole ->role->user_type->slug;
            $this->data['request'] = $request;

        
            return view($this->view_path.'.calendar-admin',$this->data);
        }
    }


    /*****************************************************/
    # CalendarController
    # Function name : getTaskLIst
    # Author        :
    # Created Date  : 08-12-2020
    # Purpose       : Get Work Order wise Task List
    # Params        : Request $request
    /*****************************************************/


    public function getTaskLIst(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'work_order_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allTasks = TaskLists::whereIsDeleted('N')->where('work_order_id', $request->work_order_id)->get();
        return response()->json(['status'=>true, 'allTasks'=>$allTasks,],200);
    }


    /*****************************************************/
    # CalendarController
    # Function name : getContractLIst
    # Author        :
    # Created Date  : 09-12-2020
    # Purpose       : Get Property wise Contract List
    # Params        : Request $request
    /*****************************************************/


    public function getContractLIst(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'property_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allContracts = Contract::whereNull('deleted_at')->where('property_id', $request->property_id)->get();
        return response()->json(['status'=>true, 'allContracts'=>$allContracts,],200);
    }


    
    /*****************************************************/
    # CalendarController
    # Function name : getWorkOrderLIst
    # Author        :
    # Created Date  : 09-12-2020
    # Purpose       : Get Contract wise Work Order List
    # Params        : Request $request
    /*****************************************************/


    public function getWorkOrderLIst(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'contract_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allWorkOrders = WorkOrderLists::whereNull('deleted_at')->where('contract_id', $request->contract_id)->get();
        return response()->json(['status'=>true, 'allWorkOrders'=>$allWorkOrders,],200);
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
                   
                );
                $validationMessages = array(
                    'task_title.required'   => 'Please enter Task title',
                    'task_title.min'        => 'Task title should be should be at least 2 characters',
                    'task_title.max'        => 'Task title should not be more than 255 characters',
                    
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
    # Function name : updateTask
    # Author        :
    # Created Date  : 09-12-2020
    # Purpose       : Update Work Order by property owner or property manager
    # Params        : Request $request
    /*****************************************************/

    
    public function updateTask(Request $request)
    {
       //dd($request->all());
        
        $logedInUser = \Auth::guard('admin')->user()->id;
        $validator = Validator::make($request->all(), [ 
            'work_order_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $checkWorkSlot = WorkOrderSlot::whereWorkOrderId($request->work_order_id)->first();
        $checkTask = TaskLists::whereWorkOrderId($request->work_order_id)->first();
        if($checkWorkSlot || $checkTask)
        {
            session()->flash('error', 'This Work Order is under process, date can not be modified now!');
            return response()->json(['status'=>false],200);
        }

        else
        {
            $start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
            $date_from = strtotime($start_date);
            $day_to_display = $date_from+86400;
                
            if($request->contract_service_date_id!='')
            {
                

                $updateContractService = ContractServiceDate::whereId($request->contract_service_date_id)->update([
                             'date'=>date('o-m-d',$day_to_display)
                        ]);
            }
            else
            {
                $updateWorkOrder = WorkOrderLists::whereId($request->work_order_id)->update([
                             'start_date'=>date('o-m-d',$day_to_display)
                        ]);
            }

            session()->flash('success-message', 'Work Order updated successfully.');
            return response()->json(['status'=>true],200);
        }
        // $sqlTask =  WorkOrderLists::whereId($request->work_order_id)->first();    
         

        // $start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
        // $end_date    = date('Y-m-d h:i:s', strtotime($request->modified_end_date));


        // $date_from = strtotime($start_date);
        // $date_to = strtotime($end_date);

        // $array_all_days = array();
        // $day_passed = ($date_to - $date_from); //seconds
        // $day_passed = ($day_passed/86400); //days
        // $arr_days=  array();
        // $counter = 1;
        // $day_to_display = $date_from;
        // while($counter <= $day_passed){
        //     $day_to_display += 86400;
        //     //echo date("F j, Y \n", $day_to_display);
        //     $checkFreeDate = TaskDetails::whereTaskDate(date('o-m-d',$day_to_display))->whereServiceId($sqlTask->service_allocation_id)->whereUserId($sqlTask->user_id)->first();
        //     if(!$checkFreeDate)
        //     {
        //         $arr_days[] = date('o-m-d',$day_to_display);
        //         $counter++;
        //     }
            
        //     else
        //     {
        //         session()->flash('error', 'Task already been added for this user on '.date("o-m-d",$day_to_display).' for this Service.');
        //         return response()->json(['status'=>false],200);
        //     }          
             
        // }

        // $sqlTask->start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
        // $sqlTask->end_date    = date('Y-m-d h:i:s', strtotime($request->modified_end_date));
        // $sqlTask->updated_at  = date('Y-m-d H:i:s');
        // $sqlTask->updated_by  = $logedInUser;
        // $save = $sqlTask->save(); 
        
        // $sqlTaskDetails = TaskDetails::whereServiceId($sqlTask->service_allocation_id)->whereTaskId($request->work_order_id)->whereUserId($sqlTask->user_id)->delete();

        // $tempTaskDetails = new TaskDetails;

        // $tempTaskDetails->service_id = $sqlTask->service_allocation_id;
        // $tempTaskDetails->task_id = $request->work_order_id;
        // $tempTaskDetails->user_id = $sqlTask->user_id;
        // $tempTaskDetails->task_date = date('o-m-d',$date_from);
        // $tempTaskDetails->created_by = auth()->guard('admin')->id();
        // $tempTaskDetails->save();

        // $array_all_days = array();
        // $day_passed = ($date_to - $date_from); //seconds
        // $day_passed = ($day_passed/86400); //days

        // $counter = 1;
        // $day_to_display = $date_from;

        // foreach ($arr_days as $key => $value) {

        //     $tempTaskDetails = new TaskDetails;
        //     $tempTaskDetails->service_id = $sqlTask->service_allocation_id;
        //     $tempTaskDetails->task_id = $request->work_order_id;
        //     $tempTaskDetails->user_id = $sqlTask->user_id;
        //     $tempTaskDetails->task_date = $value;
        //     $tempTaskDetails->created_by = auth()->guard('admin')->id();
        //     $tempTaskDetails->save();
        // }
        
        //return response()->json(['status'=>true],200);
    }



    /*****************************************************/
    # CalendarController
    # Function name : updateTaskDetails
    # Author        :
    # Created Date  : 09-12-2020
    # Purpose       : Update Task Details by Service Provider
    # Params        : Request $request
    /*****************************************************/

    
    public function updateTaskDetails(Request $request)
    {
       //dd($request->all());
        
        $logedInUser = \Auth::guard('admin')->user()->id;
        $validator = Validator::make($request->all(), [ 
            'task_details_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

            $checkTaskdetails = TaskDetails::whereId($request->task_details_id)->where('status','<>','2')->first();
            if($checkTaskdetails)
               {
                    $start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
                    $date_from = strtotime($start_date);
                    $day_to_display = $date_from+86400;
            
                    $checkTaskdetails->update([
                        'task_date'=>date('o-m-d',$day_to_display),
                        'updated_by'=>$logedInUser
                    ]);

                    session()->flash('success-message', 'Work Order updated successfully.');
                    return response()->json(['status'=>true],200);
               }
            else
            {
                session()->flash('error', 'This Task already completed, date can not be modified now!');
                return response()->json(['status'=>false],200);
            }   
            
    }



    /*****************************************************/
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
       
        $sqlProperty = ServiceAllocationManagement::with('property')->whereId($request->service_id)->first();


        $sqlCity    = City::whereId($sqlProperty->property->city_id)->whereIsActive('1')->first();
        $sqlState   = State::whereIsActive('1')->where('id', $sqlProperty->property->state_id)->first();
        $sqlCountry = Country::whereIsActive('1')->where('id', $sqlProperty->property->country_id)->first();
        
        return response()->json(['status'=>true, 'sqlProperty'=>$sqlProperty, 'sqlCity'=>$sqlCity, 'sqlState'=>$sqlState, 'sqlCountry'=>$sqlCountry],200);
    }
}
