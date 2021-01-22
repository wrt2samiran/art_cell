<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User,Country,State,City, WorkOrderLists, TaskLists, TaskDetails, ServiceAllocationManagement, Property, Contract, Service, WorkOrderSlot, ContractServiceDate, WorkOrderStatus};
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
        $allPropertyRelatedWorkOrders = array();
        $allContractServices = array();
        $allWorkOrdersRelatedServiceProvider = array();
        $allWorkOrdersRelatedContractServices = array();
        $allWorkOrdersRelatedServices = array();
        $this->data['status_list'] = WorkOrderStatus::whereIsActive(1)->orderBy('slug', 'asc')->get();

        if($logedInUserRole->role->user_type->slug=='property-owner' || $logedInUserRole->role->user_type->slug=='property-manager')
        {
            $sqlContract=Contract::with('property')->whereStatusId('1')->where(function($q) use ($logedInUser){
          
               
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
     
       
            //dd($sqlContract);
            $sqlService=ServiceAllocationManagement::with('service')->with('contract')->whereStatus('A')->where('work_status', '<>','2')->whereServiceProviderId($logedInUser)->get();

        
            $propertyList = array();
            foreach ($sqlContract as $key => $value) {
                $propertyList[]=$value->property_id;
            }
            
            $property_list = Property::whereIn('id', $propertyList)->orderBy('id', 'Desc')->get();

            if ($request->has('search')) {

                
                if($request->un_assigned==1 || $request->emergency_service==1)
                {
                   // dd($sqlContract);
                    //$sqlContract = array();
                        foreach ($sqlContract as $key => $value) {
                            $contractList[]=$value->id;
                    }
                    //dd($contractList);

                    $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->whereIn('contract_id', $contractList)->where(function ($q) use ($request) {

                            if ($request->un_assigned==1) {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->where('task_assigned', 'N');
                                       
                                     });                   

                            }

                            if ($request->emergency_service==1) {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->where('emergency_service', 'Y');
                                       
                                     });                   

                            }


                    })->orderBy('id','Desc')->get();   
                } 

                else{
               
                        $workOrder = WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'contract_service_dates', 'property.country', 'property.state', 'property.city', 'tasks', 'tasks.task_details', 'work_order_status'])->where(function ($q) use ($request, $property_list, $sqlContract) {
                            
                            if ($request->property_id!='') {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('property_id', $request->property_id);
                                       
                                     });                   

                            } 

                            else{

                                    $q->where(function ($que) use ($property_list) {
                                        $que->where('property_id', $property_list[0]->id);
                                       
                                     }); 

                            }

                            if ($request->contract_list!='') {
                               
                                $q->where(function ($que) use ($request) {
                                    $que->whereContractId( $request->contract_list);
                                   
                                 });                   

                            }

                            

                            if ($request->work_order_id!='') {
                                        
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('id', $request->work_order_id);
                                       
                                     });                   

                            }     

                            if ($request->maintenance_type!='') {
                                //dd($request->maintenance_type);
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('contract_service_id', $request->maintenance_type);
                                       
                                     });                   

                            }  

                            if ($request->service_provider_id!='') {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('user_id', $request->service_provider_id);
                                       
                                     });                   

                            }

                            if ($request->service_type!='') {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('user_id', $request->service_type);
                                       
                                     });                   

                            }

                            // if ($request->status!='') {
                            //          $q->whereHas('tasks.task_details',function($q1) use ($request){
                            //                 $q1->where('status',$request->status);
                            //             }); 
                                                  
                            //         }

                            if ($request->status!='') {

                                if(in_array(4, $request->status))
                                {
                                     $q->where(function ($que) use ($request) {
                                        $que->whereIn('status', $request->status)
                                            ->orWhere('warning', '<>', 0);
                                     }); 
                                }
                                else
                                {
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('status', $request->status);
                                    }); 
                                }                

                            }   

                        })->get();

                        if($request->property_id!='')
                        {
                            $allPropertyRelatedWorkOrders = WorkOrderLists::with('work_order_status')->whereNull('deleted_at')->whereIn('property_id', $request->property_id)->where(function($q) use ($request){

                                    if ($request->contract_list!='') {
                                    
                                            $q->where(function ($que) use ($request) {
                                                $que->whereIn('contract_id', $request->contract_list);
                                               
                                             });                   

                                    }   
                                })->get();
                        }
                        else
                        {
                            $allPropertyRelatedWorkOrders = WorkOrderLists::with('work_order_status')->whereNull('deleted_at')->where('property_id', $property_list[0]->id)->get();

                            $request["property_id"] = $property_list[0]->id;
                        }

                        if($request->work_order_id!='')
                        {
                            //$allWorkOrdersRelatedData = WorkOrderLists::with('service_provider', 'contract_services', 'service')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->get();
                            
                            $allWorkOrdersRelatedServiceProvider = WorkOrderLists::with('userDetails', 'work_order_status')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->groupBy('user_id')->get();

                            $allWorkOrdersRelatedContractServices = WorkOrderLists::with('contract_services', 'work_order_status')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->groupBy('contract_service_id')->get();

                            $allWorkOrdersRelatedServices = WorkOrderLists::with('service', 'work_order_status')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->groupBy('service_id')->get();
                        }

                }  

            } 
            else 
            {          
               // $sqlTask=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('contract_services')->whereCreatedBy($logedInUser)->orWhere('user_id',$logedInUser)->orderBy('id','Desc')->get();
                if($logedInUserRole ->role->user_type->slug=='property-owner' || $logedInUserRole ->role->user_type->slug=='property-manager')
                {
                    if(count($sqlContract)>0)
                    {
                        $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'contract_service_dates', 'property.country', 'property.state', 'property.city', 'work_order_status'])->whereContractId($sqlContract[0]->id)->get();
                    }
                    
                   // $this->data['work_order_all']  = $workOrderAll;
                }
                
            }

            if(count($workOrder)==0)
                {
                    $this->data['error'] = 'No data found!';
                }

            $this->data['serviceList'] = Service::whereIsActive(1)->get();

            //dd($workOrder);
            $this->data['work_order_list']  = $workOrder;
            $this->data['allPropertyRelatedWorkOrders'] = $allPropertyRelatedWorkOrders;
            $this->data['allWorkOrdersRelatedServiceProvider'] = $allWorkOrdersRelatedServiceProvider;
            $this->data['allWorkOrdersRelatedContractServices'] = $allWorkOrdersRelatedContractServices;
            $this->data['allWorkOrdersRelatedServices'] = $allWorkOrdersRelatedServices;
            $this->data['slug'] = $logedInUserRole ->role->user_type->slug;
            $this->data['request'] = $request;

        
            return view($this->view_path.'.calendar',$this->data);
        }

        else if($logedInUserRole->role->user_type->slug=='service-provider')
        {
            $allLabourList=array();
            $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->where('user_id',$logedInUser)->orderBy('id','Desc')->get();

            $taskList = TaskLists::with(['contract', 'task_details', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->whereWorkOrderId($workOrder[0]->id)->get();

            $this->data['work_order_list'] = $workOrder;
            
            $propertyList = array();
            foreach ($workOrder as $key => $value) {
                $propertyList[]=$value->property_id;
            }
            
            $property_list = Property::whereIn('id', $propertyList)->orderBy('id', 'Desc')->get();

            if ($request->has('search')) 
            {
                
                if($request->un_assigned==1 ||  $request->emergency_service==1)
                {
                    $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->where('user_id',$logedInUser)->where(function ($q) use ($request) {

                            if ($request->un_assigned==1) {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->where('task_assigned', 'N');
                                       
                                     });                   

                            }

                            if ($request->emergency_service==1) {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->where('emergency_service', 'Y');
                                       
                                     });                   

                            }


                    })->orderBy('id','Desc')->get();   
                }

                else
                {
                    //dd($request->all());
                    //$taskList = TaskLists::with(['contract', 'task_details', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city'])->where(function ($q) use ($request) {
                    $allContractServices = array();

                    $taskList = TaskLists::with(['contract', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->where(function ($q) use ($request, $property_list) {


                        if ($request->property_id!='') {
                                
                                $q->where(function ($que) use ($request) {
                                    $que->whereIn('property_id', $request->property_id);
                                   
                                 });                   

                        } 

                        else{

                                $q->where(function ($que) use ($property_list) {
                                    $que->where('property_id', $property_list[0]->id);
                                   
                                 }); 

                        }


                        if ($request->work_order_id!='') {
                                
                                $q->where(function ($que) use ($request) {
                                    $que->whereIn('work_order_id', $request->work_order_id);
                                   
                                 });                   

                        } 

                        if ($request->maintenance_type!='') {
                            //dd($request->maintenance_type);
                                
                                $q->where(function ($que) use ($request) {
                                    $que->whereIn('contract_service_id', $request->maintenance_type);
                                   
                                 });                   

                        }

                         
                        
                        

                    })->groupBy('id')->get();
                    //dd($taskList);
                    
                    //dd($taskListData);

                    if(count($taskList)>0)
                    {  

                        $taskListData = array();
                        foreach ($taskList as $key => $value) {
                            $taskListData[]=$value->id;
                        } 
                       
                        $taskDetailsList = TaskDetails::with('userDetails', 'task', 'work_order_status')->where(function ($q) use ($request, $taskListData) {
                            

                            if ($request->task_id!='') {
                                
                                $q->where(function ($que) use ($request) {
                                    $que->whereIn('task_id', $request->task_id);
                                   
                                 });                   

                            } 
                            else
                            {
                                $q->where(function ($que) use ($taskListData) {
                                    $que->whereIn('task_id', $taskListData);
                                   
                                 });

                                //$request->task_id = @$taskListData;
                                
                            } 

                          
                            if ($request->service_type!='') {
                                
                                $q->where(function ($que) use ($request) {
                                    $que->whereIn('service_id', $request->service_type);
                                   
                                 });                   

                            } 

                            if ($request->labour_id!='') {
                                
                                $q->where(function ($que) use ($request) {
                                    $que->whereIn('user_id', $request->labour_id);
                                   
                                 });                   

                            }  

                            if ($request->status!='') {
                              
                                $q->where(function ($que) use ($request) {
                                    $que->whereIn('status', $request->status);
                                   
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

                   // dd($request->all());
                    if($request->property_id!='')
                    {
                        $allPropertyRelatedWorkOrders = WorkOrderLists::with('work_order_status')->whereNull('deleted_at')->whereIn('property_id', $request->property_id)->get();
                    }
                    else
                    {
                        $allPropertyRelatedWorkOrders = WorkOrderLists::with('work_order_status')->whereNull('deleted_at')->where('property_id', $property_list[0]->id)->get();

                        $request["property_id"] = $property_list[0]->id;
                    }
                    
                    
                    if($request->task_id)
                        {
                            $allLabourList = TaskDetails::with('userDetails', 'work_order_status')->whereNull('deleted_at')->whereIn('task_id', $request->task_id)->groupBy('user_id')->get();
                        }
                   $this->data['allLabourList'] = $allLabourList;

                   if($request->work_order_id)
                       {
                            $allContractServices = TaskLists::with(['contract', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->whereIn('work_order_id',$request->work_order_id)->orderBy('id', 'Desc')->get();
                       }
               
                }

            } 
            else 
            {       
                    
                   // $taskList = TaskLists::with(['contract', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city'])->whereWorkOrderId($workOrder[0]->id)->orderBy('id', 'Desc')->get();
                    $taskList = TaskLists::with(['contract', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->where(function ($q) use ($property_list) {


                        if ($property_list!='') {
                            $q->where(function ($que) use ($property_list) {
                                $que->where('property_id', $property_list[0]->id);
                             }); 

                        } 
                    })->get();

                    $allContractServices = TaskLists::with(['contract', 'property','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->whereWorkOrderId($workOrder[0]->id)->orderBy('id', 'Desc')->get();

                    
                    if(count($taskList)>0)
                    {
                        $request["property_id"] = $property_list[0]->id; 
                        $taskDetailsList = TaskDetails::with('userDetails', 'task', 'work_order_status')->whereTaskId($taskList[0]->id)->get();
                    }
                    else
                    {
                        $this->data['error'] = 'No data found!';
                    }
                   
            }      
            $this->data['workOrder'] = $workOrder;
            $this->data['property_list'] = $property_list;
            $this->data['serviceList'] = Service::whereIsActive(1)->get();

            //dd($workOrder);
            $this->data['task_list']  = $taskList;
            $this->data['task_details_list']  = $taskDetailsList;
            $this->data['slug'] = $logedInUserRole ->role->user_type->slug;
            $this->data['request'] = $request;
            $this->data['allPropertyRelatedWorkOrders'] = $allPropertyRelatedWorkOrders;
            $this->data['allContractServices'] = $allContractServices;
            return view($this->view_path.'.calendar-service-provider',$this->data);
        }  

        else if($logedInUserRole->role->user_type->slug=='super-admin' || $logedInUserRole->role->user_type->slug=='sub-admin') 
        {
            $sqlContract=Contract::with('property')->whereStatusId('1')->whereNull('deleted_at')



            ->when($request->contract_status_id,function($query) use($request){
                $query->where('contract_status_id',$request->contract_status_id);
            })->orderBy('id', 'Desc')->get();

            $this->data['sqlContract'] = $sqlContract;
     
       
            //dd($sqlContract);
            $sqlService=ServiceAllocationManagement::with('service')->with('contract')->whereStatus('A')->where('work_status', '<>','2')->whereServiceProviderId($logedInUser)->get();

        
            $propertyList = array();
            foreach ($sqlContract as $key => $value) {
                $propertyList[]=$value->property_id;
            }
            
            $property_list = Property::whereIn('id', $propertyList)->orderBy('id', 'Desc')->get();

            if ($request->has('search')) {

                
                if($request->un_assigned==1 || $request->emergency_service==1)
                {
                   // dd($sqlContract);
                    //$sqlContract = array();
                        foreach ($sqlContract as $key => $value) {
                            $contractList[]=$value->id;
                    }
                    //dd($contractList);

                    $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'property.country', 'property.state', 'property.city', 'work_order_status'])->whereIn('contract_id', $contractList)->where(function ($q) use ($request) {

                            if ($request->un_assigned==1) {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->where('task_assigned', 'N');
                                       
                                     });                   

                            }

                            if ($request->emergency_service==1) {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->where('emergency_service', 'Y');
                                       
                                     });                   

                            }


                    })->orderBy('id','Desc')->get();   
                } 

                else{
               
                        $workOrder = WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'contract_service_dates', 'property.country', 'property.state', 'property.city', 'tasks', 'tasks.task_details', 'work_order_status'])->where(function ($q) use ($request, $property_list, $sqlContract) {
                            
                            if ($request->property_id!='') {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('property_id', $request->property_id);
                                       
                                     });                   

                            } 

                            else{

                                    $q->where(function ($que) use ($property_list) {
                                        $que->where('property_id', $property_list[0]->id);
                                       
                                     }); 

                            }

                            if ($request->contract_list!='') {
                               
                                $q->where(function ($que) use ($request) {
                                    $que->whereContractId( $request->contract_list);
                                   
                                 });                   

                            }

                            

                            if ($request->work_order_id!='') {
                                        
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('id', $request->work_order_id);
                                       
                                     });                   

                            }     

                            if ($request->maintenance_type!='') {
                                //dd($request->maintenance_type);
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('contract_service_id', $request->maintenance_type);
                                       
                                     });                   

                            }  

                            if ($request->service_provider_id!='') {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('user_id', $request->service_provider_id);
                                       
                                     });                   

                            }

                            if ($request->service_type!='') {
                                    
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('user_id', $request->service_type);
                                       
                                     });                   

                            }

                            // if ($request->status!='') {
                            //          $q->whereHas('tasks.task_details',function($q1) use ($request){
                            //                 $q1->where('status',$request->status);
                            //             }); 
                                                  
                            //         }

                            if ($request->status!='') {

                                if(in_array(4, $request->status))
                                {
                                     $q->where(function ($que) use ($request) {
                                        $que->whereIn('status', $request->status)
                                            ->orWhere('warning', '<>', 0);
                                     }); 
                                }
                                else
                                {
                                    $q->where(function ($que) use ($request) {
                                        $que->whereIn('status', $request->status);
                                    }); 
                                }                

                            }   

                        })->get();

                        if($request->property_id!='')
                        {
                            $allPropertyRelatedWorkOrders = WorkOrderLists::with('work_order_status')->whereNull('deleted_at')->whereIn('property_id', $request->property_id)->where(function($q) use ($request){

                                    if ($request->contract_list!='') {
                                    
                                            $q->where(function ($que) use ($request) {
                                                $que->whereIn('contract_id', $request->contract_list);
                                               
                                             });                   

                                    }   
                                })->get();
                        }
                        else
                        {
                            $allPropertyRelatedWorkOrders = WorkOrderLists::with('work_order_status')->whereNull('deleted_at')->where('property_id', $property_list[0]->id)->get();

                            $request["property_id"] = $property_list[0]->id;
                        }

                        if($request->work_order_id!='')
                        {
                            //$allWorkOrdersRelatedData = WorkOrderLists::with('service_provider', 'contract_services', 'service')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->get();
                            
                            $allWorkOrdersRelatedServiceProvider = WorkOrderLists::with('userDetails', 'work_order_status')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->groupBy('user_id')->get();

                            $allWorkOrdersRelatedContractServices = WorkOrderLists::with('contract_services', 'work_order_status')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->groupBy('contract_service_id')->get();

                            $allWorkOrdersRelatedServices = WorkOrderLists::with('service', 'work_order_status')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->groupBy('service_id')->get();
                        }

                }  

            } 
            else 
            {          
               // $sqlTask=TaskLists::with('property')->with('service')->with('country')->with('state')->with('city')->with('contract_services')->whereCreatedBy($logedInUser)->orWhere('user_id',$logedInUser)->orderBy('id','Desc')->get();
                
                    if(count($sqlContract)>0)
                    {
                        $workOrder=WorkOrderLists::with(['contract','property','service_provider','service', 'contract_services', 'contract_service_dates', 'property.country', 'property.state', 'property.city', 'work_order_status'])->whereContractId($sqlContract[0]->id)->get();
                    }
                    
                   // $this->data['work_order_all']  = $workOrderAll;
               
                
            }

            if(count($workOrder)==0)
                {
                    $this->data['error'] = 'No data found!';
                }

            $this->data['serviceList'] = Service::whereIsActive(1)->get();

            //dd($workOrder);

            $this->data['work_order_list']  = $workOrder;
            $this->data['allPropertyRelatedWorkOrders'] = $allPropertyRelatedWorkOrders;
            $this->data['allWorkOrdersRelatedServiceProvider'] = $allWorkOrdersRelatedServiceProvider;
            $this->data['allWorkOrdersRelatedContractServices'] = $allWorkOrdersRelatedContractServices;
            $this->data['allWorkOrdersRelatedServices'] = $allWorkOrdersRelatedServices;
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
        $allTasks = TaskLists::whereIsDeleted('N')->whereIn('work_order_id', $request->work_order_id)->get();
        $allService = WorkOrderLists::with('service')->whereIsDeleted('N')->whereIn('id', $request->work_order_id)->groupBy('service_id')->get();
        $allContractServices = TaskLists::with(['contract_services'])->whereIn('work_order_id', $request->work_order_id)->orderBy('id', 'Desc')->get();
           // dd($allContractServices->contract_services->service_type);

        if(count($allContractServices)>0)
        {
            foreach ($allContractServices as $key => $value) {
                $allContractServicesData [] = $value->contract_services;
            }
            
        }
        
       $allContractServicesDataList = array_unique($allContractServicesData);
        //dd($allContractServicesDataList);
       // $allTasks = TaskLists::whereIsDeleted('N')->whereIn('work_order_id', $request->work_order_id)->get();
        return response()->json(['status'=>true, 'allTasks'=>$allTasks, 'allService'=>$allService, 'allContractServices'=>$allContractServicesDataList,],200);
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

        $allContracts = Contract::whereNull('deleted_at')->whereStatusId('1')->where('property_id', $request->property_id)->get();
        return response()->json(['status'=>true, 'allContracts'=>$allContracts,],200);
    }


    
    /*****************************************************/
    # CalendarController
    # Function name : getPropertyWorkOrderLIst
    # Author        :
    # Created Date  : 28-12-2020
    # Purpose       : Get Property wise Work Order List
    # Params        : Request $request
    /*****************************************************/


    public function getPropertyWorkOrderLIst(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'property_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allWorkOrders = WorkOrderLists::whereNull('deleted_at')->whereIn('property_id', $request->property_id)->get();
        return response()->json(['status'=>true, 'allWorkOrders'=>$allWorkOrders,],200);
    }

    /*****************************************************/
    # CalendarController
    # Function name : getTaskLabour
    # Author        :
    # Created Date  : 28-12-2020
    # Purpose       : Get Task wise Labour List
    # Params        : Request $request
    /*****************************************************/


    public function getTaskLabour(Request $request)
    {
       // dd($request->all());
         $validator = Validator::make($request->all(), [ 
            'task_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allLabourList = TaskDetails::with('userDetails')->whereNull('deleted_at')->whereIn('task_id', $request->task_id)->groupBy('user_id')->get();
        return response()->json(['status'=>true, 'allLabourList'=>$allLabourList,],200);
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
    # Function name : getPropertyContractList
    # Author        :
    # Created Date  : 03-01-2021
    # Purpose       : Get Property wise Contract List
    # Params        : Request $request
    /*****************************************************/


    public function getPropertyContractList(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'property_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allProperties = Contract::whereNull('deleted_at')->whereIn('property_id', $request->property_id)->get();
        return response()->json(['status'=>true, 'allProperties'=>$allProperties,],200);
    }

    /*****************************************************/
    # CalendarController
    # Function name : getContractWorkOrderLIst
    # Author        :
    # Created Date  : 03-01-2021
    # Purpose       : Get Contract wise Work Order  List
    # Params        : Request $request
    /*****************************************************/


    public function getContractWorkOrderLIst(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'contract_list' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allWorkOrders = WorkOrderLists::whereNull('deleted_at')->whereIn('contract_id', $request->contract_list)->get();
        return response()->json(['status'=>true, 'allWorkOrders'=>$allWorkOrders,],200);
    }


    /*****************************************************/
    # CalendarController
    # Function name : getServiceProviderList
    # Author        :
    # Created Date  : 03-01-2021
    # Purpose       : Get Work Order wise Service List
    # Params        : Request $request
    /*****************************************************/


    public function getServiceProviderList(Request $request)
    {
       // dd($request->all());
         $validator = Validator::make($request->all(), [ 
            'work_order_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allServiceProvider = WorkOrderLists::with('userDetails')->whereNull('deleted_at')->whereIn('id', $request->work_order_id)->groupBy('user_id')->get();

        $allService = WorkOrderLists::with('service')->whereIsDeleted('N')->whereIn('id', $request->work_order_id)->groupBy('service_id')->get();
        $allContractServices = WorkOrderLists::with(['contract_services'])->whereIn('id', $request->work_order_id)->orderBy('id', 'Desc')->get();

        return response()->json(['status'=>true, 'allServiceProvider'=>$allServiceProvider, 'allService'=>$allService, 'allContractServices'=>$allContractServices],200);
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
