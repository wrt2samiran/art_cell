<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{Country,State,City,TaskLists, ServiceAllocationManagement};

use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use DB;

class ServiceManagementController extends Controller
{

    private $view_path='admin.service_management';

    /*****************************************************/
    # CityController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing City List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){

        $this->data['page_title']='Servicee Management List';
        $logedInUser = \Auth::guard('admin')->user()->id;
        if($request->ajax()){

            $srvalmnm=ServiceAllocationManagement::with('property')->orderBy('id','Desc');
           
            return Datatables::of($srvalmnm)
            ->editColumn('created_at', function ($srvalmnm) {
                return $srvalmnm->created_at ? with(new Carbon($v->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('work_status',function($srvalmnm){
                if($srvalmnm->work_status=='0'){
                   $message='Pending';
                    return '<a title="Click to deactivate the city" href="" class="btn btn-block btn-outline-warning btn-sm">Pending</a>';
                    
                }elseif($srvalmnm->work_status=='1'){
                   $message='Overdue';
                   return '<a title="Click to deactivate the city" href="" class="btn btn-block btn-outline-success btn-sm">Overdue</a>';
                   
                }
                else{
                    $message='Completed';
                    return '<a title="Click to deactivate the city" href="" class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($srvalmnm){
                
                $edit_url=route('admin.task_management.list',$srvalmnm->id);

                return '<a title="Add Task" href="'.$edit_url.'"><i class="fas fa-plus text-success"></i></a>';
                
            })
            ->rawColumns(['action','work_status'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # CityController
    # Function name : cityAdd
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Adding new City
    # Params        : Request $request
    /*****************************************************/
    public function cityAdd(Request $request) {

        $this->data['page_title']     = 'Add City';
        $this->data['panel_title']    = 'Add City';
    
        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:'.(new City)->getTable().',name',
                    'country_id'    => 'required',
                    'state_id'      => 'required',
                );
                $validationMessages = array(
                    'name.required'         => 'Please enter name',
                    'name.min'              => 'Name should be should be at least 2 characters',
                    'name.max'              => 'Name should not be more than 255 characters',
                    'country_id'            => 'Please select country',
                    'state_id.required'     => 'Please select state',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('admin.city.add')->withErrors($Validator)->withInput();
                } else {
                    
                    $new = new TaskLists;
                    $new->name = trim($request->name, ' ');
                    $new->country_id  = $request->country_id;
                    $new->state_id    = $request->state_id;

                    $new->created_at = date('Y-m-d H:i:s');
                    $save = $new->save();
                
                    if ($save) {                        
                        $request->session()->flash('alert-success', 'City has been added successfully');
                        return redirect()->route('admin.city.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while adding the city');
                        return redirect()->back();
                    }
                }
            }

            $country_list=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['country_list']=$country_list;
            return view($this->view_path.'.add',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.city.list')->with('error', $e->getMessage());
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

            $details = TaskLists::find($id);
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
            $details = TaskLists::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 'A') {
                    
                    $details->status = 'I';
                    $details->save();
                        
                    $request->session()->flash('alert-success', 'Status updated successfully');                 
                     } else if ($details->status == 'I') {
                    $details->status = 'A';
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

            $details = TaskLists::where('id', $id)->first();
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

    // public function show($id){
    //     $city=City::findOrFail($id);
    //     $this->data['page_title']='Country Details';
    //     $this->data['city']=$city;
    //     return view($this->view_path.'.show',$this->data);
    // }
    /*****************************************************/
    # CityController
    # Function name : getState
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Get Country wise State List
    # Params        : Request $request
    /*****************************************************/

    public function getState(Request $request)
    {
        

        $allState = State::where('country_ids', $request->country_id)->get();
        return response()->json(['status'=>true, 'allState'=>$allState,],200);
    }

    public function getZone(Request $request)
    {
         $validator = Validator::make($request->all(), [ 
            'country_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }

        $allZone = State::where('country_id', $request->country_id)->get();
        return response()->json(['status'=>true, 'allZone'=>$allZone,],200);
    }
}
