<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{User,Country,State,City,Tasks, Calendars};

use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use DB;

class TaskManagementController extends Controller
{

    private $view_path='admin.task_management';

    /*****************************************************/
    # CityController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing City List
    # Params        : Request $request
    /*****************************************************/
       
    public function list(Request $request, $id){
        //dd($request->all());
        $this->data['page_title']='Task List';
        $logedInUser = \Auth::guard('admin')->user()->id;

        if($request->ajax()){

            $calendar=Calendars::orderBy('id','Desc');
            return Datatables::of($calendar)
            ->editColumn('created_at', function ($calendar) {
                return $calendar->created_at ? with(new Carbon($calendar->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($calendar){
                if($calendar->status=='1'){
                   //$message='deactivate';
                   return '<span class="btn btn-block btn-outline-success btn-sm">Overdue</a>';
                    
                }else if($calendar->status=='0'){
                  // $message='activate';
                   return '<span class="btn btn-block btn-outline-success btn-sm">Pending</a>';
                }
                else
                {
                    return '<span class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($calendar){
                $delete_url=route('admin.task_management.delete',$calendar->id);
                $details_url=route('admin.task_management.show',$calendar->id);
                $add_url=route('admin.task_management.list',$calendar->id);

                return '<a title="View City Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Add Task" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>&nbsp;&nbsp;<a title="Delete city" href="javascript:delete_city('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        $service_list=Tasks::whereStatus('A')->where('work_status', '<>','2')->orderBy('id','ASC')->get();

        $labour_list= User::whereStatus('A')->whereRoleId('5')->whereCreatedBy($logedInUser)->get();


        $this->data['service_list'] = $service_list;
        $this->data['task_id'] = $id;
        //$this->data['task_type'] = $sqlTask->task_type;

        //$this->data['property_list']= $property_list;
        $this->data['labour_list']  = $labour_list;
        //$sqlCalendar=Calendars::whereTaskId($id)->orderBy('id','Desc')->get();

        if ($request->has('search')) {
            
            $sqlCalendar = Calendars::where(function ($q) use ($request) {
                
                if ($request->has('user_labour_id')) {
                   
                    $q->where(function ($que) use ($request) {
                        $que->where('user_id', $request->user_labour_id);
                       
                     });                   

                    }

                if ($request->has('task_status')) {
                    
                    $q->where(function ($que) use ($request) {
                        $que->where('status', $request->task_status);
                       
                     });                   

                    }    

            })->get();

        } else {
            $sqlCalendar=Calendars::whereTaskId($id)->orderBy('id','Desc')->get();
        }

        $this->data['calendar_list']  = $sqlCalendar;
        $this->data['request'] = $request;

       // return view($this->view_path.'.add',$this->data);

        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # CityController
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
                    'job_title'     => 'required|min:2|max:255',
                    'service_id'    => 'required',
                    'property_id'   => 'required',
                    'country_id'    => 'required',
                    'state_id'      => 'required',
                    'city_id'       => 'required',
                    'labour_id'     => 'required',
                );
                $validationMessages = array(
                    'job_title.required'    => 'Please enter Job title',
                    'job_title.min'         => 'Job title should be should be at least 2 characters',
                    'job_title.max'         => 'Job title should not be more than 255 characters',
                    'service_id.required'   => 'Please select service',
                    'property_id.required'  => 'Please select property',
                    'country_id.required'   => 'Please select country',
                    'state_id.required'     => 'Please select state',
                    'city_id.required'      => 'Please select city',
                    'labour_id.required'    => 'Please select user',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {

                    return redirect()->route('admin.task_management.list',$request->labour_id)->withErrors($Validator)->withInput();
                    
                } else {
                    
                    $rangeDate = (explode("-",$request->date_range));
                    //dd($rangeDate);
                    $new = new Calendars;
                    $new->task_id = $request->service_id;   
                    $new->property_id = $request->property_id;
                    $new->country_id =$request->country_id;
                    $new->state_id =$request->state_id;
                    $new->city_id =$request->city_id;
                    $new->user_id =$request->labour_id;
                    $new->job_title =$request->job_title;
                    $new->job_desc =$request->job_desc;
                    $new->job_type ='1';
                    $new->start_date = date("Y-m-d", strtotime($rangeDate[0]));
                    $new->end_date = date("Y-m-d", strtotime($rangeDate[1]));
                    $new->status ='0';
                    $new->created_by =auth()->guard('admin')->id();
                    $new->updated_by =auth()->guard('admin')->id();

                    $new->created_at = date('Y-m-d H:i:s');
                    $save = $new->save();

                                      
                        $request->session()->flash('alert-success', 'Task has been added successfully');
                        return redirect()->route('admin.task_management.list',$request->labour_id);
                    
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
    # CityController
    # Function name : cityEdit
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
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
    # CityController
    # Function name : change_status
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Change city status
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
    # CityController
    # Function name : cityDelete
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
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
    # CityController
    # Function name : show
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing Country details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $city=City::findOrFail($id);
        $this->data['page_title']='Country Details';
        $this->data['city']=$city;
        return view($this->view_path.'.show',$this->data);
    }
    /*****************************************************/
    # CityController
    # Function name : getCities
    # Author        :
    # Created Date  : 12-10-2020
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
    # CityController
    # Function name : getData
    # Author        :
    # Created Date  : 14-10-2020
    # Purpose       : Get Service Related Data List
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

    
    public function updateTask(Request $request)
    {
        $logedInUser = \Auth::guard('admin')->user()->id;
         $validator = Validator::make($request->all(), [ 
            'calendar_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

         $sqlCalendar = Calendars::find($request->calendar_id);
            $sqlCalendar->start_date  = date('Y-m-d h:i:s', strtotime($request->modified_start_date));
            $sqlCalendar->end_date    = date('Y-m-d h:i:s', strtotime($request->modified_end_date));
            $sqlCalendar->updated_at  = date('Y-m-d H:i:s');
            $sqlCalendar->updated_by  = $logedInUser;
            $save = $sqlCalendar->save();                     
                    

        
        return response()->json(['status'=>true],200);
    }
}
