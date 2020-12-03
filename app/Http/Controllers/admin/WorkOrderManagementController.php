<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User,Country,State,City,WorkOrderLists,TaskLists, TaskDetails, ServiceAllocationManagement, Property, Contract, ContractService, ContractServiceDate, WorkOrderSlot, ContractServiceRecurrence};
use Auth, Validator;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use DB;

class WorkOrderManagementController extends Controller
{

    private $view_path='admin.work_order_management';

    
    /*****************************************************/
    # WorkOrderManagementController
    # Function name : workOrderCreate
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Adding new work order by property owner or property manager
    # Params        : Request $request
    /*****************************************************/
    public function workOrderCreate(Request $request) {

        $this->data['page_title']     = 'Create Work Order';
        $logedInUserRole = \Auth::guard('admin')->user()->role_id;
        $logedInUser = \Auth::guard('admin')->user()->id;
    
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

                    return redirect()->route('admin.work-order-management.create',$request->service_allocation_id)->withErrors($Validator)->withInput();
                    
                } else {
                    
                    // $rangeDate = (explode("-",$request->date_range));     
                    // $start_date = \Carbon\Carbon::parse($rangeDate['0']);
                    // $end_date = \Carbon\Carbon::parse($rangeDate['1']);

                    $start_date = Carbon::createFromFormat('d/m/Y', $request->date_range)->format('Y-m-d');
                    //$end_date = Carbon::createFromFormat('d/m/Y', $request->date_range)->format('Y-m-d');

                    $date_from = strtotime($start_date);
                    //$date_to = strtotime($end_date);

                    $sqlContract = Contract::findOrFail($request->contract_id);
                    $sqlContractService = ContractService::where('contract_id', $request->contract_id)->whereServiceId($request->service_id)->first();
                    $sqlSlot = ContractServiceRecurrence::whereContractServiceId($sqlContractService->id)->first();

                    $task=WorkOrderLists::create([
                        'contract_id'=>$request->contract_id,
                        'property_id'=>$request->property_id,
                        'service_id' => $request->service_id,
                        'contract_service_id' => $sqlContractService->id,
                        // 'country_id' =>$request->country_id,
                        // 'state_id' =>$request->state_id,
                        // 'city_id' =>$request->city_id,
                        'user_id'=>$sqlContract->service_provider_id,
                        'task_title'=>$request->task_title,
                        'task_desc' => $request->task_desc,
                        'start_date'=>date("Y-m-d", strtotime($start_date)),
                        //'end_date'=>date("Y-m-d", strtotime($end_date)),
                        'created_by'=>$logedInUser,
                        'updated_by'=>$logedInUser
                    ]);

                    
                    // if($sqlSlot and count($sqlSlot)>0)
                    // {
                    //     for ($i=0; $i <count($sqlSlot) ; $i++) { 
                    //         if(array_key_exists($i, $allSlot))
                    //         {
                    //             $daily_slot_value = $allSlot[$i];
                    //         }
                    //         $slot_array[]=[
                    //             'work_order_id'            => $request->service_id,
                    //             'contract_service_date_id' => $request->task_id,
                    //             'daily_slot'               => $daily_slot_value,
                    //             'created_by'               => auth()->guard('admin')->id(),
                    //         ];
                    //     }
                    // }


                    
                    if ($sqlContractService) {
                         if($sqlContractService->service_type=='General' || $sqlContractService->service_type=='Free')  
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
                        return redirect()->route('admin.work-order-management.list');
                }
            }

            if($logedInUserRole==2)
            {
                //$this->data['contract_list']=Contract::whereIsActive('1')->whereNull('deleted_at')->whereCustomerId($logedInUser)->orderBy('id','ASC')->get();
                $this->data['contract_list']=Contract::with(['property','service_provider','services','contract_status'])
                ->whereHas('property')
                ->whereHas('service_provider')
                ->whereHas('services')
                ->whereHas('contract_status')
                ->where(function($q) use ($logedInUser){
              
                    // if logged in user is the service_provider of the contract 
                    $q->where('service_provider_id',$logedInUser)
                    //OR if logged in user is the property_owner or added as property manager of the property related to this contract 
                    ->orWhereHas('property',function($q1) use ($logedInUser){
                        $q1->where('property_owner',$logedInUser)
                        ->orWhere('property_manager',$logedInUser);
                    });
                })
                ->select('contracts.*')->get();
            }
            else
            {
                $this->data['contract_list']=Contract::whereIsActive('1')->whereNull('deleted_at')->orderBy('id','ASC')->get();
            }
            //dd($this->data['contract_list']);
            return view($this->view_path.'.add',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.work-order-management.calendar')->with('error', $e->getMessage());
        }
        
    }

    /*****************************************************/
    # WorkOrderManagementController
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

        $sqlService = ContractService::with('service')->whereContractId($request->contract_id)->where('service_type','<>', 'Maintenance')->get();
        // $restContractService = array();
        // foreach ($sqlService as $key => $serviceValue) {
        //     if(($serviceValue->service_type=='General' || $serviceValue->service_type=='Free') and ($serviceValue->number_of_times_already_used=='NULL' || $serviceValue->number_of_times_already_used == ''))
        //     {
        //         $restContractService[] = $serviceValue->id;
        //     }
        //     elseif($serviceValue->service_type=='Maintenance')
        //     {
        //         $sqlTaskData = TaskLists::whereContractId($serviceValue->contract_id)->whereServiceId($serviceValue->service_id)->whereNull('deleted_at')->first();
        //         if($sqlTaskData)
        //         {
        //            // if(strtotime($sqlTaskData->start_date) < strtotime('-"'.$serviceValue->interval_days.'" days')) {
        //             $addedOn = new \DateTime($sqlTaskData->start_date);
        //             $now = new \DateTime();

        //             if($addedOn->diff($now)->days > $serviceValue->interval_days) {
                        
                      
        //                 $restContractService[] = $serviceValue->id;
        //             }
        //         }
        //         else
        //         {
        //             $restContractService[] = $serviceValue->id;
        //         }
                

        //         //where('start_date', '<=', Carbon::now()->subDays($serviceValue->interval_days)->toDateTimeString())
        //     }
        //     elseif($serviceValue->service_type=='On Demand' and ($serviceValue->number_of_times_already_used=='NULL' || $serviceValue->number_of_times_already_used < $serviceValue->number_of_time_can_used))
        //     {
        //         $restContractService[] = $serviceValue->id;
        //     }
        // }

        // $sqlServiceFinal = ContractService::with('service')->whereContractId($request->contract_id)->whereIn('id', $restContractService)->get();

        $sqlCity    = City::whereId($sqlProperty->property->city_id)->whereIsActive('1')->first();
        $sqlState   = State::whereIsActive('1')->where('id', $sqlProperty->property->state_id)->first();
        $sqlCountry = Country::whereIsActive('1')->where('id', $sqlProperty->property->country_id)->first();
        
        return response()->json(['status'=>true, 'sqlProperty'=>$sqlProperty, 'sqlCity'=>$sqlCity, 'sqlState'=>$sqlState, 'sqlCountry'=>$sqlCountry, 'sqlService'=>$sqlService],200);
    }

    
    

    /*****************************************************/
    # WorkOrderManagementController
    # Function name : getContractServiceStatus
    # Author        :
    # Created Date  : 05-11-2020
    # Purpose       : Checking whether a service is available to create a new task or not
    # Params        : Request $request
    /*****************************************************/

   

    public function getContractServiceStatus(Request $request)
    {
        $logedInUser = \Auth::guard('admin')->user()->id;
         $validator = Validator::make($request->all(), [ 
            'service_id'  => 'required',
            'contract_id' => 'required'
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }
        
        $sqlService = ContractService::with('service')->whereContractId($request->contract_id)->whereServiceId($request->service_id)->first();
        if($sqlService){

           

            if(($sqlService->service_type=='General' || $sqlService->service_type=='Free') and $sqlService->number_of_times_already_used==''){
                $service_status = 'Available';
            }
            elseif(($sqlService->service_type=='General' || $sqlService->service_type=='Free') and $sqlService->number_of_times_already_used>=1){
                $service_status = 'Not Available';
            }
            

            elseif($sqlService->service_type=='On Demand' and $sqlService->number_of_times_already_used < $sqlService->number_of_time_can_used){
                $service_status = 'Available';
            }
            elseif($sqlService->service_type=='On Demand' and $sqlService->number_of_times_already_used >= $sqlService->number_of_time_can_used){
                $service_status = 'Not Available';
            }

            elseif($sqlService->service_type=='Maintenance' and $sqlService->number_of_times_already_used=='NULL'){
                $service_status = 'Available';
            }
            elseif($sqlService->service_type=='Maintenance' and $sqlService->number_of_times_already_used!='NULL'){
                
                    if($sqlService->number_of_times_already_used < $sqlService->frequency_number)
                    {
                        $sqlTask = TaskLists::whereContractId($request->contract_id)->whereContractServiceId($sqlService->id)->whereServiceId($request->service_id)->whereIsDeleted('N')->whereNull('deleted_at')->latest()->first();

                        if($sqlTask)
                        {

                            $addedOn = new \DateTime($sqlTask->start_date);
                            $now = new \DateTime();

                            if($addedOn->diff($now)->days > $sqlService->interval_days) {
                              $service_status = 'Available';
                            }
                        
                            else
                            {
                                $service_status = 'Out of period';
                            }
                        }
                        else
                        {
                            $service_status = 'Available';
                        }
                        
                    }
                    else{
                        $service_status = 'Not Available';
                    }
                    
            }

        }

        return response()->json(['status'=>true, 'service_status'=>$service_status],200);
    }


    /*****************************************************/
    # WorkOrderManagementController
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
                $delete_url=route('admin.work-order-management.delete',$task_list->id);
                $details_url=route('admin.work-order-management.show',$task_list->id);
                $add_url=route('admin.work-order-management.calendar',$task_list->id);

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
    # WorkOrderManagementController
    # Function name : List
    # Author        :
    # Created Date  : 20-10-2020
    # Purpose       : Showing Task List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){


        $this->data['page_title']='Work Order Management List';
        $logedInUser = \Auth::guard('admin')->user()->id;
        $logedInUserRole = \Auth::guard('admin')->user()->role_id;
        if($logedInUserRole !=5)
        {
        if($request->ajax()){
            
           // $tasks=WorkOrderLists::with('property')->with('service')->with('country')->with('state')->with('city')->where('created_by', $logedInUser)->orWhere('user_id', $logedInUser)->orderBy('id','Desc');

            $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'property.country', 'property.state', 'property.city'])
                
                ->where(function($q) use ($logedInUser){
              
                    // if logged in user is the service_provider of the contract 
                    $q->where('user_id',$logedInUser)
                    //OR if logged in user is the property_owner or added as property manager of the property related to this contract 
                    ->orWhereHas('property',function($q1) use ($logedInUser){
                        $q1->where('property_owner',$logedInUser)
                        ->orWhere('property_manager',$logedInUser);
                    });
                })
                ->select('work_order_lists.*');

            
            return Datatables::of($workOrder)
            ->editColumn('created_at', function ($workOrder) {
                return $workOrder->created_at ? with(new Carbon($workOrder->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($workOrder){
                if($workOrder->status=='0'){
                   $message='Pending';
                    return '<a href="" class="btn btn-block btn-outline-warning btn-sm">Pending</a>';
                    
                }elseif($workOrder->status=='1'){
                   $message='Overdue';
                   return '<a  href="" class="btn btn-block btn-outline-success btn-sm">Overdue</a>';
                   
                }
                else{
                    $message='Completed';
                    return '<a href="" class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($workOrder)use ($logedInUser){
                $action_buttons='';
               
             
                if($logedInUser==$workOrder->user_id){
                     $add_url=route('admin.work-order-management.labourTaskList',$workOrder->id);

                     $action_buttons =$action_buttons.'<a title="Labour Task List" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>';
                }
                
                if(\Auth::guard('admin')->user()->role_id==5){
                    $details_url = route('admin.work-order-management.dailyTask',$workOrder->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Daily Task List" id="details_task" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                 }

                else{
                    $details_url = route('admin.work-order-management.show',$workOrder->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Show Work Order" id="details_task" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                } 

               
                // if( \Auth::guard('admin')->user()->hasAllPermission(['work-order-edit'])){   

                //     $edit_url=route('admin.work-order-management.edit',$workOrder->id);
                //     $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Work Order" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';

                //     $delete_url=route('admin.work-order-management.delete',$workOrder->id);
                //     $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete contract" href="javascript:delete_task('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                // }

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
    # WorkOrderManagementController
    # Function name : taskEdit
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Editing task
    # Params        : Request $request
    /*****************************************************/
    public function edit($id) {
        $this->data['page_title']     = 'Edit Task';
        $this->data['panel_title']    = 'Edit Task';

        $logedInUserRole = \Auth::guard('admin')->user()->role_id;
        $logedInUser = \Auth::guard('admin')->user()->id;
      

            $details = WorkOrderLists::with(['contract','property','service_provider','service', 'property.country', 'property.state', 'property.city'])->find($id);
            //$data['id'] = $id;
            //dd($details);
            
            if($logedInUserRole==2)
            {
               // $this->data['contract_list']=Contract::with('property')->whereIsActive('1')->whereNull('deleted_at')->whereCustomerId($logedInUser)->orderBy('id','ASC')->get();

                $this->data['contract_list']=Contract::with(['property','service_provider','services','contract_status'])
                ->whereHas('property')
                ->whereHas('services')
                ->whereHas('contract_status')
                ->where(function($q) use ($logedInUser){
              
                    // if logged in user is the service_provider of the contract 
                    $q->where('service_provider_id',$logedInUser)
                    //OR if logged in user is the property_owner or added as property manager of the property related to this contract 
                    ->orWhereHas('property',function($q1) use ($logedInUser){
                        $q1->where('property_owner',$logedInUser)
                        ->orWhere('property_manager',$logedInUser);
                    });
                })
                ->select('contracts.*')->get();
            }
            else
            {
                $this->data['contract_list']=Contract::with('property')->whereIsActive('1')->whereNull('deleted_at')->orderBy('id','ASC')->get();
            }

            $this->data['property_list']=Property::with('parent_user')->whereIsActive('1')->whereNull('deleted_at')->orderBy('id','ASC')->get();

            $sqlService = ContractService::with('service')->whereContractId($details->contract->id)->where('service_type','<>', 'Maintenance')->get();

            return view($this->view_path.'.edit',$this->data)->with(['details' => $details, 'sqlService' => $sqlService]);

    }


    /*****************************************************/
    # WorkOrderManagementController
    # Function name : update
    # Author        :
    # Created Date  : 10-11-2020
    # Purpose       : Updating the task
    # Params        : Request $request
    /*****************************************************/
    public function update(Request $request, $id) {
        

        $logedInUserRole = \Auth::guard('admin')->user()->role_id;
        $logedInUser = \Auth::guard('admin')->user()->id;

        try
        {           

            $details = WorkOrderLists::find($id);
            //$rangeDate = (explode("-",$request->date_range));     
            $start_date = Carbon::createFromFormat('d/m/Y', $request->date_range)->format('Y-m-d');
            $end_date = Carbon::createFromFormat('d/m/Y', $request->date_range)->format('Y-m-d');
           // $data['id'] = $id;

        

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
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {

                    $sqlContractService = ContractService::where('contract_id', $request->contract_id)->whereServiceId($request->service_id)->first();

                    $checkContractService = ContractService::whereId($details->contract_service_id)->first();
                    if($checkContractService->contract_id != $request->contract_id || $checkContractService->service_id != $request->service_id)
                    {
                        if($sqlContractService->service_type=='General' || $sqlContractService->service_type=='Free')  
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


                           if($checkContractService->service_type=='General' || $checkContractService->service_type=='Free')
                                {
                                    $checkContractService->number_of_times_already_used = 'Null';
                                }
                                else
                                {
                                    if($checkContractService->number_of_times_already_used>1)
                                    {
                                        $checkContractService->number_of_times_already_used = ($checkContractService->number_of_times_already_used-1);
                                    }
                                    else
                                    {
                                        $checkContractService->number_of_times_already_used = 'Null';   
                                    }
                                    
                                }

                                $contractUpdate = $checkContractService->save(); 
                    }
                    $sqlContract = Contract::findOrFail($request->contract_id);
                    

                    $details->update([
                        'contract_id'=>$request->contract_id,
                        'property_id'=>$request->property_id,
                        'service_id' => $request->service_id,
                        'contract_service_id' => $sqlContractService->id,
                        
                        'user_id'=>$sqlContract->service_provider_id,
                        'task_title'=>$request->task_title,
                        'task_desc' => $request->task_desc,
                        'start_date'=>date("Y-m-d", strtotime($start_date)),
                        'updated_by'=>$logedInUser
                    ]);

                    $request->session()->flash('alert-success', 'Task updated successfully');
                    return redirect()->route('admin.work-order-management.list');
                }
           
            
            
            
        } catch (Exception $e) {
            return redirect()->route('admin.service_management.calendar')->with('error', $e->getMessage());
        }
    }



    /*****************************************************/
    # WorkOrderManagementController
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
                return redirect()->route('admin.work-order-management.daily-task');
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
    # WorkOrderManagementController
    # Function name : delete
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : delete task
    # Params        : Request $request
    /*****************************************************/
    public function delete($id)
    {
        $task=WorkOrderLists::where('task_assigned', 'N')->whereId($id)->first();
        if($task)
        {
            $contractService=ContractService::whereId($task->contract_service_id)->first();

            if($contractService)
            {
                if($contractService->service_type=='General' || $contractService->service_type=='Free')
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

                $contractUpdate = $contractService->save();
               
            }

            $task->update([
            'deleted_by'=>auth()->guard('admin')->id()
            ]);
            $task->delete();
            return response()->json(['message'=>'Work Order successfully deleted.']);
        }
        else
        {
            $task->update([
            'deleted_by'=>auth()->guard('admin')->id()
            ]);
            $task->delete();
            return response()->json(['message'=>'Work Order alreday been assigned, can not be deleted!']);
        }

    }
    

    /*****************************************************/
    # WorkOrderManagementController
    # Function name : show
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Showing Task details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'property.country', 'property.state', 'property.city'])->whereId($id)->first();
        $this->data['page_title']='Work Order Details';
        $this->data['work_order_list']=$workOrder;

        return view($this->view_path.'.show',$this->data);
    }
    

    /*****************************************************/
    # WorkOrderManagementController
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
        $totalTask   = TaskDetails::whereTaskId($sqlTaskData->task_id)->get();
        $sqlTask     = TaskLists::findOrFail($sqlTaskData->task_id);
        $sqlWorkOrder = WorkOrderLists::findOrFail($sqlTask->work_order_id);
        $workOrderTaskList = TaskLists::whereWorkOrderId($sqlTask->work_order_id)->get();
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

                    return redirect()->route('admin.work-order-management.dailyTask',$sqlTaskData->task_id)->withErrors($Validator)->withInput();
                    
                } else {
                    
                    $sqlTaskData->user_feedback  = $request->user_feedback;
                    $sqlTaskData->status         = '2';
                    $sqlTaskData->updated_at     = date('Y-m-d H:i:s');
                    $sqlTaskData->updated_by     = $logedInUser;
                    $save                        = $sqlTaskData->save(); 

                    
                    $sqlTask->task_complete_percent = ($sqlTask->task_complete_percent+(100/count($totalTask)));
                    $updatePercent = $sqlTask->save();

                    $sqlTaskWorkPercent     = TaskLists::whereWorkOrderId($sqlTask->work_order_id)->whereId($sqlTaskData->task_id)->first();
                    if($sqlTaskWorkPercent->task_complete_percent==100)
                    {
                        $sqlWorkOrder->work_order_complete_percent = ($sqlWorkOrder->work_order_complete_percent+(100/count($workOrderTaskList)));
                    }
                    

                    $request->session()->flash('alert-success', 'Task Feedback has been added successfully');
                    return redirect()->route('admin.work-order-management.labourTaskList',$sqlTaskData->task_id);
                    
                }
            }

           
        } catch (Exception $e) {
            return redirect()->route('admin.work-order-management.labourTaskList',$sqlTaskData->task_id)->with('error', $e->getMessage());
        }
    }


    /*****************************************************/
    # WorkOrderManagementController
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
    # WorkOrderManagementController
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
    # WorkOrderManagementController
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
             if($logedInUserRole==5){
                 $task_list=TaskDetails::with('task')->with('service')->with('userDetails')->where('user_id', $id)->orderBy('id','Desc');
             }
             else{
                $task_list=TaskLists::with('service')->where('work_order_id', $id)->where('created_by', $logedInUser)->orderBy('id','Desc');
             }
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
                   return '<span class="btn btn-block btn-outline-denger btn-sm">Overdue</a>';
                    
                }else if($task_list->status=='0'){
                   $message='complete';
                   if($task_list->user_feedback==''){
                        return '<span class="btn btn-block btn-outline-warning btn-sm">Pending</a>';
                   }
                   else{
                        return '<a title="Click to Complete the daily task" href="javascript:change_status('."'".route('admin.work-order-management.change_status',$task_list->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Pending</a>';
                   }
                   
                   
                }
                else
                {
                    return '<span class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($task_list){
                $logedInUser = \Auth::guard('admin')->user()->id;
                $today = date('Y-m-d');
                $action_buttons='';
                
                if($logedInUser == $task_list->user_id)
                { 
                    if($task_list->user_feedback==''){
                        if($task_list->user_id==$logedInUser){
                            
                            if($today >= $task_list->task_date)
                            {
                                return '<a title="Add Task Feedback" href="javascript:void(0)" onclick="addFeedback('.$task_list->id.')"><i class="fas fa-head-side-cough"></i></a>&nbsp;&nbsp;';
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
                         $details_url = route('admin.work-order-management.taskLabourList',$task_list->id);
                        return '<a title="View Daily Task Update" id="details_task_feedback" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                    }
                }
                else
                {
                    if($logedInUser==$task_list->user_id){
                         $add_url=route('admin.work-order-management.labourTaskList',$task_list->id);

                         $action_buttons =$action_buttons.'<a title="Labour Task List" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>';
                    }
                  
                    $details_url = route('admin.work-order-management.taskLabourList',$task_list->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Show Task" id="details_task" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                  

                    if($logedInUser==$task_list->created_by and $task_list->task_date>$today){
                        $edit_url = route('admin.work-order-management.editDailyTask',$task_list->id);
                        $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Task" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                   
                        $delete_url=route('admin.work-order-management.deleteLabourTask',$task_list->id);
                        $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete contract" href="javascript:delete_task('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                    }

                    if($action_buttons==''){
                        $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                    } 
                    return $action_buttons;
                }

                if(\Auth::guard('admin')->user()->role_id==4){
                    $details_url = route('admin.work-order-management.taskLabourList',$task_list->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Daily Task List" id="details_task" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                 }
                
            })

            ->rawColumns(['action','status'])
            ->make(true);
        }


        
        //$this->data['task_list_data']=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('userDetails')->findOrFail($id);
        $workOrderList=WorkOrderLists::with(['contract', 'contract_services', 'contract_service_dates', 'contract_service_recurrence',  'property','service_provider','service', 'property.country', 'property.state', 'property.city'])->whereId($id)->whereIsDeleted('N')->first();
        //dd($workOrderList->contract_service_dates);
        if($logedInUserRole!=5)
        {
            if($workOrderList->contract_service_recurrence->interval_type == 'daily')
            {
                
                $checkSlot = WorkOrderSlot::whereWorkOrderId($id)->first();
                    if(!$checkSlot)
                    { 
                        if($workOrderList->contract_service_recurrence->number_of_times!='' || $workOrderList->contract_service_recurrence->number_of_times>0){
                            foreach ($workOrderList->contract_service_dates as $key => $valueServiceDate) {
                                for($x=1; $x<$workOrderList->contract_service_recurrence->number_of_times; $x++)
                                {
                                    $work_order_slot[]=[
                            
                                     'work_order_id'            => $id,
                                     'contract_service_date_id' => $valueServiceDate->id,
                                     'daily_slot'               => $x,
                                     'created_by'               => auth()->guard('admin')->id(),
                                 ];
                                }
                                
                            }
                            $storeSlote = WorkOrderSlot::insert($work_order_slot);     
                        }               
                    }

                    else
                    {
                        $checkSlot = WorkOrderSlot::with('contract_service_dates')->whereWorkOrderId($id)->whereBookedStatus('N')->get();
                        $this->data['slot_data'] = $checkSlot;

                        $checkDate = WorkOrderSlot::with('contract_service_dates')->whereWorkOrderId($id)->whereBookedStatus('N')->groupBy('contract_service_date_id')->get();
                        $this->data['available_dates'] = $checkDate;
                     
                    }
            }
        }
        
        $this->data['work_order_list'] = $workOrderList;
        //dd($this->data['work_order_list']->contract_service_recurrence->number_of_times);
        $this->data['labour_list']= User::whereCreatedBy($logedInUser)->orderBy('name', 'Desc')->get();
        
        $this->data['request'] = $request;
        $this->data['work_order_id'] = $id;
       if($logedInUserRole !=5)
       {
            return view($this->view_path.'.daily-task-list',$this->data);
       }
       else
       {
            return view($this->view_path.'.work-order-task-list',$this->data);
       }
        
    }

    /*****************************************************/
    # WorkOrderManagementController
    # Function name : taskLabourList
    # Author        :
    # Created Date  : 01-12-2020
    # Purpose       : Showing Task details anlong with labour task list
    # Params        : Request $request
    /*****************************************************/

    public function taskLabourList(Request $request, $id){

        //dd($request->all());
        $this->data['page_title']='Labour Task List';
        $logedInUser = \Auth::guard('admin')->user()->id;
        $logedInUserRole = \Auth::guard('admin')->user()->role_id;

        if($request->ajax()){
            // if($logedInUserRole!=5){
            //     $task_detail_list=TaskDetails::with('task')->with('service')->with('userDetails')->where('task_id', $id)->orderBy('id','Desc');
            // }
            // else{
            $labour_task_list=TaskDetails::with('task')->with('userDetails')->whereTaskId($id)->orderBy('id','Desc');
            // }
            return Datatables::of($labour_task_list)
            ->editColumn('created_at', function ($labour_task_list) {
                return $labour_task_list->created_at ? with(new Carbon($labour_task_list->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($labour_task_list){
                if($labour_task_list->status=='1'){
                   //$message='deactivate';
                   return '<span class="btn btn-block btn-outline-denger btn-sm">Overdue</a>';
                    
                }else if($labour_task_list->status=='0'){
                   $message='complete';
                   if($labour_task_list->user_feedback==''){
                        return '<span class="btn btn-block btn-outline-warning btn-sm">Pending</a>';
                   }
                   else{
                        return '<a title="Click to Complete the daily task" href="javascript:change_status('."'".route('admin.work-order-management.change_status',$labour_task_list->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Pending</a>';
                   }
                   
                   
                }
                else
                {
                    return '<span class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($labour_task_list){
                $logedInUser = \Auth::guard('admin')->user()->id;
                $today = date('Y-m-d');
                $action_buttons='';
            

                if(\Auth::guard('admin')->user()->role_id==4){
                    $details_url = route('admin.work-order-management.taskLabourList',$labour_task_list->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Daily Task List" id="details_task" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                 }
                
            })

            ->rawColumns(['action','status'])
            ->make(true);
        }
        
      
        $this->data['request'] = $request;
        $this->data['task_data'] = TaskLists::with('contract')->with('property')->with('service')->with('work_order')->whereId($id)->whereIsDeleted('N')->first();
        //$this->data['work_order_id'] = $id;
       
            return view($this->view_path.'.task-labour-list',$this->data);
       
        
    }

    /*****************************************************/
    # WorkOrderManagementController
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

        //$this->data['sqltaskData']=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->whereId($id)->whereIsDeleted('N')->first();

        $this->data['sqltaskData']=TaskLists::with(['contract','property','service_provider','service', 'property.country', 'property.state', 'property.city'])->whereId($id)->whereIsDeleted('N')->first();

        $this->data['labour_list']= User::whereRoleId('5')->whereCreatedBy($logedInUser)->orderBy('name', 'Desc')->get();
        $labour_list= User::whereStatus('A')->whereRoleId('5')->whereCreatedBy($logedInUser)->get();
        return view($this->view_path.'.labour-task-add',$this->data);
    }


    
    /*****************************************************/
    # WorkOrderManagementController
    # Function name : taskAssign
    # Author        :
    # Created Date  : 02-11-2020
    # Purpose       : Assigning new Task to Labour
    # Params        : Request $request
    /*****************************************************/
    public function taskAssign(Request $request) {

       // dd($request->all());

        $this->data['page_title']     = 'Assign Task';
        
        $logedInUser = \Auth::guard('admin')->user()->id;
        
        try
        {
            
            $validationCondition = array(
                'work_order_id'     => 'required',
                'task_title'        => 'required|min:3|max:100',
                'service_id'        => 'required',
                'date_range'        => 'required',
                'user_id'           => 'required',
            );
            $validationMessages = array(
                
                'work_order_id.required'    => 'Please select Task',
                'task_title.required'       => 'Task Title is required',
                'task_title.min'            => 'Task Title should be should be at least 3 characters',
                'task_title.max'            => 'Task Title should not be more than 100 characters',
                'service_id.required'       => 'Please select service',
                'user_id.required'          => 'Please select user',
                'date_range.required'       => 'Please select date',
            );

            $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {
                
                return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id)->withErrors($Validator)->withInput();
                
            } else {
                
                $sqlWorkorder = WorkOrderLists::findOrFail($request->work_order_id);
                $rangeDate = (explode("-",$request->date_range));     
                $start_date = \Carbon\Carbon::parse($rangeDate['0']);
                $end_date = \Carbon\Carbon::parse($rangeDate['1']);

                $date_from = strtotime($start_date);
                $date_to = strtotime($end_date);

                
                $addTask = TaskLists::create([
                    'work_order_id'         => $request->work_order_id,
                    'contract_id'           => $sqlWorkorder->contract_id,
                    'contract_service_id'   => $sqlWorkorder->contract_service_id,
                    'service_id'            => $sqlWorkorder->service_id,
                    'property_id'           => $sqlWorkorder->property_id,
                    'task_title'            => $request->task_title,
                    'task_desc'             => $request->task_description,
                    'task_assigned'         => 'Y',
                    'created_by'            => auth()->guard('admin')->id(),
                ]);

                 foreach ($request->user_id as $labourUser) {
                    $array_all_days = array();
                    $day_passed = ($date_to - $date_from); //seconds
                    $day_passed = ($day_passed/86400); //days
                    $arr_days=  array();
                    $counter = 0;
                    $day_to_display = ($date_from-86400);
                   // dd($day_passed);
                    while($counter <= $day_passed){
                        $day_to_display += 86400;
                        $addTaskDetails = TaskDetails::create([
                            'service_id'            => $request->service_id,
                            'task_id'               => $addTask->id,
                            'user_id'               => $labourUser,
                            'task_date'             => date('o-m-d',$day_to_display),
                            'task_description'      => $request->task_description,
                            'created_by'            => auth()->guard('admin')->id(),
                        ]);
                        $counter++;
                    }    
                }    


                $sqlWorkorder->update([
                        'task_assigned'=>'Y',
                        'updated_by'=>$logedInUser
                    ]);

                $request->session()->flash('success', 'Task has been added successfully');
                return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id);                
            }

        } catch (Exception $e) {
            return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id)->with('error', $e->getMessage());
        }
    }

    
    /*****************************************************/
    # WorkOrderManagementController
    # Function name : checkAvailablity
    # Author        :
    # Created Date  : 01-12-2020
    # Purpose       : Checking Labour Leave
    # Params        : Request $request
    /*****************************************************/

    public function checkAvailablity(Request $request)
    {
        $userData = json_decode(stripslashes($request->data));
        $selectedDate = json_decode(stripslashes($request->selectedDate));
        $work_order_id = json_decode(stripslashes($request->work_order_id));


        $rangeDate = (explode("-",$selectedDate));     
        $start_date = \Carbon\Carbon::parse($rangeDate['0']);
        $end_date = \Carbon\Carbon::parse($rangeDate['1']);

        $date_from = strtotime($start_date);
        $date_to = strtotime($end_date);


        $userLeaveList = array();
        foreach ($userData as $key =>$labourUser) {

                    $labourWeekEnd = User::whereId($labourUser)->first();
                    //$userArrayList[] = $labourWeekEnd->name;
                   // dd($userArrayList);
                    $array_all_days = array();
                    $day_passed = ($date_to - $date_from); //seconds
                    $day_passed = ($day_passed/86400); //days
                    $arr_days=  array();
                    $counter = 0;
                    $day_to_display = ($date_from-86400);
                   // dd($day_passed);
                    $weeklyLeave = array();
                    while($counter <= $day_passed){
                        $day_to_display += 86400;
                        $dayName = date('l', $day_to_display);
                        $labourWeekEnd->weekly_off;
                        
                        if($dayName == $labourWeekEnd->weekly_off)
                        {
                            
                            $weeklyLeave[] = date('o-m-d',$day_to_display);
                            //date('o-m-d',$day_to_display);
                        }
                        // $checkTask = TaskLists::whereId($work_order_id)->get();
                        // $checkTaskDetails = TaskDetails::whereUserId($labourUser)->whereTaskDate(date('o-m-d',$day_to_display))->whereIn('task_id', $checkTask)->first();
                        
                        $counter++;
                    }  
                    if(count($weeklyLeave)>0)
                    {
                        $userLeaveList[$labourWeekEnd->name] =  $weeklyLeave;
                    }
                    
                }    

            if(count($userLeaveList)>0)
                {
                    return response()->json(['status'=>true, 'userLeaveList'=>$userLeaveList],200);
                }
        
    }  


    
    /*****************************************************/
    # WorkOrderManagementController
    # Function name : taskMaintanenceAssign
    # Author        :
    # Created Date  : 27-11-2020
    # Purpose       : Assigning new Daily Maintanence Task to Labour
    # Params        : Request $request
    /*****************************************************/
    public function taskMaintanenceAssign(Request $request) {


       // dd($request->all());
        

        $this->data['page_title']     = 'Assign Task';
        

        try
        {
            
            $validationCondition = array(
                'work_order_id'             => 'required',
                'task_title_maintanence_daily'=> 'required|min:3|max:100',
                'service_id'                => 'required',
                'maintanence_user_id'       => 'required',
                'work_date'                 => 'required',
               
            );
            $validationMessages = array(
                'work_order_id.required'       => 'Please select Task',
                'task_title_maintanence_daily.required'          => 'Task Title is required',
                'task_title_maintanence_daily.min'               => 'Task Title should be should be at least 3 characters',
                'task_title_maintanence_daily.max'               => 'Task Title should not be more than 100 characters',
                'service_id.required'          => 'Please select service',
                'maintanence_user_id.required' => 'Please select user',
                'work_date.required'           => 'Please select date',
                
            );

            $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {
               
                return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id)->withErrors($Validator)->withInput();
                
            } else {

                $sqlWorkorder = WorkOrderLists::findOrFail($request->work_order_id);
                $addTask = TaskLists::create([
                    'work_order_id'         => $request->work_order_id,
                    'contract_id'           => $sqlWorkorder->contract_id,
                    'contract_service_id'   => $sqlWorkorder->contract_service_id,
                    'service_id'            => $sqlWorkorder->service_id,
                    'property_id'           => $sqlWorkorder->property_id,
                    'task_title'            => $request->task_title_maintanence_daily,
                    'task_desc'             => $request->task_description,
                    'task_assigned'         => 'Y',
                    'created_by'            => auth()->guard('admin')->id(),
                ]);

                
                $slot_list = array();
                $filtered_work_date = array();
                foreach ($request->work_date as $key => $value) {
                    if (is_numeric($value)) {
                        $slot_list[] = $value;
                    }
                    else
                    {
                        $filtered_work_date[] =  $value;
                    }
                }
             
            
                foreach ($request->maintanence_user_id as $maintainUser) {
                    
                    foreach ($filtered_work_date as $workDateValue) {
                    
                        $addTaskDetails = TaskDetails::create([
                            'service_id'            => $sqlWorkorder->service_id,
                            'task_id'               => $addTask->id,
                            'user_id'               => $maintainUser,
                            'task_date'             => $workDateValue,
                            'task_description'      => $request->task_description,
                            'created_by'            => auth()->guard('admin')->id(),
                        ]);
                    }

                }
          
                foreach ($slot_list as $keySlot => $valueSlot) {
                    foreach ($request->work_date as $workDateValue) {
                        
                        $sqlWorkOrderList = WorkOrderLists::whereId($request->work_order_id)->first();
                        $sqlServiceDate = ContractServiceDate::whereContractServiceId($sqlWorkOrderList->contract_service_id)->where('date', $workDateValue)->first();
                        $sqlSlot = WorkOrderSlot::whereWorkOrderId($request->work_order_id)->whereContractServiceDateId($sqlServiceDate['id'])->wheredailySlot($valueSlot)->whereBookedStatus('N')->update([
                             'booked_status'=>'Y'
                        ]);
                    }

                }
               
                // TaskDetails::insert($task_details_data_array);
                $request->session()->flash('success', 'Task has been added successfully');
                return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id);                
            }

        } catch (Exception $e) {
           // dd($e->getMessage());
            return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id)->with('error', $e->getMessage());
        }
    }


     /*****************************************************/
    # WorkOrderManagementController
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
                    return redirect()->route('admin.work-order-management.labourTaskList', $details->task_id);
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
                            return redirect()->route('admin.work-order-management.labourTaskList', $request->task_id);
                        } else {
                            $request->session()->flash('error', 'An error occurred while updating the Labour Task');
                            return redirect()->back();
                        }
                    }
                    
                    else
                    {
                        return redirect()->route('admin.work-order-management.labourTaskList', $request->task_id)->with('error', 'Task already been added for this user on '.date("o-m-d",$date_from).' for this Service.');
                    }                   
                }
            }
            
            return view($this->view_path.'.labour-task-edit',$this->data)->with(['sqltaskData' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.work-order-management.labourTaskList', $id)->with('error', $e->getMessage());
        }
    }




    /*****************************************************/
    # WorkOrderManagementController
    # Function name : taskOtherMaintanenceAssign
    # Author        :
    # Created Date  : 27-11-2020
    # Purpose       : Assigning new Maintanence Task to Labour except Daily
    # Params        : Request $request
    /*****************************************************/
    public function taskOtherMaintanenceAssign(Request $request) {


       // dd($request->all());
        $this->data['page_title']     = 'Assign Task';
        

        try
        {
            
            $validationCondition = array(
                'work_order_id'             => 'required',
                'task_title_maintanence_other'=> 'required|min:3|max:100',
                'service_id'                => 'required',
                'maintanence_other_user_id'       => 'required',
                'work_date_other'                 => 'required',
               
            );
            $validationMessages = array(
                'work_order_id.required'       => 'Please select Task',
                'task_title_maintanence_other.required'          => 'Task Title is required',
                'task_title_maintanence_other.min'               => 'Task Title should be should be at least 3 characters',
                'task_title_maintanence_other.max'               => 'Task Title should not be more than 100 characters',
                'service_id.required'          => 'Please select service',
                'maintanence_other_user_id.required' => 'Please select user',
                'work_date_other.required'           => 'Please select date',
                
            );

            $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
            if ($Validator->fails()) {
               
                return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id)->withErrors($Validator)->withInput();
                
            } else {

                $sqlWorkorder = WorkOrderLists::findOrFail($request->work_order_id);
                $addTask = TaskLists::create([
                    'work_order_id'         => $request->work_order_id,
                    'contract_id'           => $sqlWorkorder->contract_id,
                    'contract_service_id'   => $sqlWorkorder->contract_service_id,
                    'service_id'            => $sqlWorkorder->service_id,
                    'property_id'           => $sqlWorkorder->property_id,
                    'task_title'            => $request->task_title_maintanence_other,
                    'task_desc'             => $request->task_description,
                    'task_assigned'         => 'Y',
                    'created_by'            => auth()->guard('admin')->id(),
                ]);

               
            
                foreach ($request->maintanence_other_user_id as $maintainOtherUser) {
                    
                    foreach ($request->work_date_other as $workDateValue) {
                    
                        $addTaskDetails = TaskDetails::create([
                            'service_id'            => $sqlWorkorder->service_id,
                            'task_id'               => $addTask->id,
                            'user_id'               => $maintainOtherUser,
                            'task_date'             => $workDateValue,
                            'task_description'      => $request->task_description,
                            'created_by'            => auth()->guard('admin')->id(),
                        ]);
                    }

                }
               
                $request->session()->flash('success', 'Task has been added successfully');
                return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id);                
            }

        } catch (Exception $e) {
           // dd($e->getMessage());
            return redirect()->route('admin.work-order-management.labourTaskList', $request->work_order_id)->with('error', $e->getMessage());
        }
    }



    /*****************************************************/
    # WorkOrderManagementController
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
