<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User,Country,State,City,TaskLists, TaskDetails, ServiceAllocationManagement, Property};
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
