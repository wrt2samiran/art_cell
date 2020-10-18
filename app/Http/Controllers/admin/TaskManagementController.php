<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User,Country,State,City,Tasks, TaskDetails, ServiceAllocationManagement, Property};

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
    # Function name : List
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : Showing Task List
    # Params        : Request $request
    /*****************************************************/
       
    public function list(Request $request, $id){
        //dd($request->all());
        $this->data['page_title']='Task List';
        $logedInUser = \Auth::guard('admin')->user()->id;

        if($request->ajax()){

            $task_list=Tasks::orderBy('id','Desc');
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
                $add_url=route('admin.task_management.list',$task_list->id);

                return '<a title="View City Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Add Task" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>&nbsp;&nbsp;<a title="Delete city" href="javascript:delete_city('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        $service_data=ServiceAllocationManagement::whereStatus('A')->where('work_status', '<>','2')->whereServiceProviderId($logedInUser)->whereId($id)->first();

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


        $this->data['service_data'] = $service_data;
        $this->data['task_id'] = $id;
        $this->data['property_data'] = $sqlProperty;
        $this->data['city_data'] = $sqlCity;
        $this->data['state_data'] = $sqlState;
        $this->data['country_data'] = $sqlCountry;

        $this->data['labour_list']  = $labour_list;

        if ($request->has('search')) {
            
            $sqlCalendar = Tasks::where(function ($q) use ($request) {
                
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
            $sqlTask=Tasks::whereServiceAllocationId($id)->orderBy('id','Desc')->get();
        }

        //dd($sqlCalendar);
        $this->data['tasks_list']  = $sqlTask;
        $this->data['request'] = $request;

       // return view($this->view_path.'.add',$this->data);

        return view($this->view_path.'.list',$this->data);
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

        $this->data['page_title']     = 'Add Task';
    
        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'task_title'     => 'required|min:2|max:255',
                    'service_id'    => 'required',
                    'property_id'   => 'required',
                    'country_id'    => 'required',
                    'state_id'      => 'required',
                    'city_id'       => 'required',
                    'labour_id'     => 'required',
                );
                $validationMessages = array(
                    'task_title.required'    => 'Please enter Task title',
                    'task_title.min'         => 'Task title should be should be at least 2 characters',
                    'task_title.max'         => 'Task title should not be more than 255 characters',
                    'service_id.required'   => 'Please select service',
                    'property_id.required'  => 'Please select property',
                    'country_id.required'   => 'Please select country',
                    'state_id.required'     => 'Please select state',
                    'city_id.required'      => 'Please select city',
                    'labour_id.required'    => 'Please select user',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {

                    return redirect()->route('admin.task_management.list',$request->service_id)->withErrors($Validator)->withInput();
                    
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
                            return redirect()->route('admin.task_management.list',$request->service_id)->with('error', 'Task already been added for this user on '.date("o-m-d",$day_to_display).' for this Service.');
                        }
                        
                         
                    }
                  //  print_r($arr_days);
                 
                    $new = new Tasks;
                    $new->service_allocation_id = $request->service_id;   
                    $new->property_id = $request->property_id;
                    $new->country_id =$request->country_id;
                    $new->state_id =$request->state_id;
                    $new->city_id =$request->city_id;
                    $new->user_id =$request->labour_id;
                    $new->task_title =$request->task_title;
                    $new->task_desc =$request->task_desc;
                   
                    $new->start_date = date("Y-m-d", strtotime($rangeDate[0]));
                    $new->end_date = date("Y-m-d", strtotime($rangeDate[1]));
                    $new->status ='0';
                    $new->created_by =auth()->guard('admin')->id();
                    $new->updated_by =auth()->guard('admin')->id();

                    $new->created_at = date('Y-m-d H:i:s');
                    $save = $new->save();

                    
                     
                    
                    //$arr_days []= date('o-m-d',$date_from);
                    
                    $temp = new TaskDetails;
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
                        $temp->task_id = $new->id;
                        $temp->user_id =$request->labour_id;
                        $temp->task_date = $value;
                        $temp->created_by = auth()->guard('admin')->id();
                        $temp->save();
                    }

                        $request->session()->flash('alert-success', 'Task has been added successfully');
                        return redirect()->route('admin.task_management.list',$request->service_id);
                    
                }
            }

            $country_list=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['country_list']=$country_list;
            return view($this->view_path.'.add',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.task_management.list')->with('error', $e->getMessage());
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
        $this->data['page_title']     = 'Edit City';
        $this->data['panel_title']    = 'Edit City';

        try
        {           

            $details = Tasks::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {

                
                if ($id == null) {
                    return redirect()->route('admin.service_management.list');
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
                        return redirect()->route('admin.service_management.list');
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
            return redirect()->route('admin.service_management.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.service_management.list');
            }
            $details = Tasks::where('id', $id)->first();
            if ($details != null) {
                if ($details->is_active == 1) {
                    
                    $details->is_active = '0';
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
                return redirect()->route('admin.service_management.list')->with('error', 'Invalid city');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.service_management.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # TaskManagementController
    # Function name : taskDelete
    # Author        :
    # Created Date  : 16-10-2020
    # Purpose       : delete task
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.service_management.list');
            }

            $details = Tasks::where('id', $id)->first();
            if ($details != null) {
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'City has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the city');
                    }
            } else {
                $request->session()->flash('alert-danger', 'Invalid city');
                
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('admin.service_management.list')->with('error', $e->getMessage());
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
        $city=City::findOrFail($id);
        $this->data['page_title']='Country Details';
        $this->data['city']=$city;
        return view($this->view_path.'.show',$this->data);
    }
    /*****************************************************/
    # TaskManagementController
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
    # TaskManagementController
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
        $sqlProperty = DB::table('tasks')
        ->join('contracts', 'contracts.id', '=', 'tasks.contract_id')
        ->join('properties', 'properties.id', '=', 'contracts.property_id')
        ->where('tasks.service_provider_id', $logedInUser)
        ->where('tasks.id', $request->service_id)
        ->first();

        $sqlCity    = City::whereIsActive('1')->where('id', $sqlProperty->city_id)->first();
        $sqlState   = State::whereIsActive('1')->where('id', $sqlCity->state_id)->first();
        $sqlCountry = Country::whereIsActive('1')->where('id', $sqlState->country_id)->first();
        
        return response()->json(['status'=>true, 'sqlProperty'=>$sqlProperty, 'sqlCity'=>$sqlCity, 'sqlState'=>$sqlState, 'sqlCountry'=>$sqlCountry],200);
    }


    /*****************************************************/
    # TaskManagementController
    # Function name : updateTask
    # Author        :
    # Created Date  : 16-10-2020
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

         
         $sqlTask =  Tasks::whereId($request->task_id)->first();    

         

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
                  //  print_r($arr_days);

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
}
