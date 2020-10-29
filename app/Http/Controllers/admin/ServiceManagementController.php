<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{Country,State,City,Contract, TaskLists, ServiceAllocationManagement, ContractService, Property};

use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use DB;

class ServiceManagementController extends Controller
{

    private $view_path='admin.service_management';

    /*****************************************************/
    # ServiceManagementController
    # Function name : List
    # Author        :
    # Created Date  : 19-10-2020
    # Purpose       : Showing Service List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){

        $this->data['page_title']='Servicee Management List';
        $logedInUser = \Auth::guard('admin')->user()->id;
        if($request->ajax()){

            $srvalmnm=ServiceAllocationManagement::with('tasks_list')->with('contract')->with('property')->with('service')->orderBy('id','Desc');
            
            return Datatables::of($srvalmnm)
            ->editColumn('created_at', function ($srvalmnm) {
                return $srvalmnm->created_at ? with(new Carbon($srvalmnm->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('work_status',function($srvalmnm){
                if($srvalmnm->work_status=='0'){
                   $message='Pending';
                    return '<a href="" class="btn btn-block btn-outline-warning btn-sm">Pending</a>';
                    
                }elseif($srvalmnm->work_status=='1'){
                   $message='Overdue';
                   return '<a  href="" class="btn btn-block btn-outline-success btn-sm">Overdue</a>';
                   
                }
                else{
                    $message='Completed';
                    return '<a href="" class="btn btn-block btn-outline-success btn-sm">Completed</a>';
                }
            })
            ->addColumn('action',function($srvalmnm)use ($logedInUser){
                $action_buttons='';
               
             
                if($logedInUser==$srvalmnm->service_provider_id){
                     $add_url=route('admin.task_management.calendar',$srvalmnm->id);

                     $action_buttons =$action_buttons.'<a title="Add Task" href="'.$add_url.'"><i class="fas fa-plus text-success"></i></a>';
                }

                //if($logedInUser==$srvalmnm->created_by and $logedInUser!=$srvalmnm->service_provider_id){
                    $details_url = route('admin.service_management.show',$srvalmnm->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp<a title="View Service Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
               // }

                if(($logedInUser==$srvalmnm->created_by and $logedInUser!=$srvalmnm->service_provider_id) and isset($srvalmnm->tasks_list)=='' ){
                    $edit_url = route('admin.service_management.edit',$srvalmnm->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit service" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }
                if(($logedInUser==$srvalmnm->created_by and $logedInUser!=$srvalmnm->service_provider_id) and isset($srvalmnm->tasks_list)==''){
                    $delete_url=route('admin.service_management.delete',$srvalmnm->id);
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete contract" href="javascript:delete_service('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }

                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;

               
                
            })
            ->rawColumns(['action','work_status'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # ServiceManagementController
    # Function name : addService
    # Author        :
    # Created Date  : 19-10-2020
    # Purpose       : Adding new Service
    # Params        : Request $request
    /*****************************************************/
    public function addService(Request $request) {


        $this->data['page_title']     = 'Add Service';
        
        $logedInUser = \Auth::guard('admin')->user()->id;

        

        $sqlService = ServiceAllocationManagement::pluck('contract_id');
       // $this->data['contract_list'] = Contract::whereStatus('Ongoing')->orWhere('status','active')->whereIsActive(1)->whereNotIn('id', $sqlService)->get();

        $this->data['contract_list'] = Contract::whereNotIn('id', $sqlService)->get();

          // $sqlService =  $sqlService->pluck('service_name');
       // $contract_service = ContractService::with('service')->whereNotIn('service_id', $sqlService)->where('contract_id',$request->contract_id)->get();


        $this->data['contract_service'] = Contract::whereStatus('Ongoing')->whereIsActive(1)->get();

    
    
        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'contract_id'          => 'required',
                    'property_id'          => 'required',
                    'service_provider_id'  => 'required',
                    'service_id'           => 'required',
                    'service_details'      => 'required',
                    'service_start_date'   => 'required',
                    'service_end_date'     => 'required',
                );
                $validationMessages = array(
                    'contract_id.required'         => 'Please enter name',
                    'property_id.required'         => 'Please select country',
                    'service_provider_id.required' => 'Please select state',
                    'service_id.required'          => 'Please select country',
                    'service_details.required'     => 'Service Details is required',
                    'service_start_date.required'  => 'Please select state',
                    'service_end_date.required'    => 'Please select state',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    
                    return redirect()->route('admin.service_management.addService')->withErrors($Validator)->withInput();
                } else {
                    

                    $start_date=Carbon::createFromFormat('d/m/Y', $request->service_start_date)->format('Y-m-d');
                    $end_date=Carbon::createFromFormat('d/m/Y', $request->service_end_date)->format('Y-m-d');
                     
                    $newService = new ServiceAllocationManagement;
                    $newService->contract_id            = $request->contract_id;
                    $newService->property_id            = $request->property_id;
                    $newService->service_provider_id    = $request->service_provider_id;
                    $newService->service_name           = $request->service_id;
                    $newService->service_details        = $request->service_details;
                    $newService->service_start_date     = $start_date;
                    $newService->service_end_date       = $end_date;
                    $newService->created_at             = date('Y-m-d H:i:s');
                    $newService->created_by             = $logedInUser;
                    $save = $newService->save();
                
                    if ($save) {                        
                        $request->session()->flash('alert-success', 'Service has been added successfully');
                        return redirect()->route('admin.service_management.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while adding the city');
                        return redirect()->back();
                    }
                }
            }

           
            return view($this->view_path.'.add',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.service_management.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # ServiceManagementController
    # Function name : getData
    # Author        :
    # Created Date  : 19-10-2020
    # Purpose       : Get Contract Related Data List
    # Params        : Request $request
    /*****************************************************/

   

    public function getData(Request $request)
    {
        $logedInUser = \Auth::guard('admin')->user()->id;
        $validator = Validator::make($request->all(), [ 
            'contract_id' => 'required',
            ]);

           if ($validator->fails()) { 
              return response()->json(['success' =>false,'message'=>$validator->errors()->first()], 200);
            }
       
           $sqlService = ServiceAllocationManagement::where('contract_id',$request->contract_id)->get();

           $sqlService =  $sqlService->pluck('service_name');
        $contract_service = ContractService::with('service')->whereNotIn('service_id', $sqlService)->where('contract_id',$request->contract_id)->get();

        $sqlProperty   = Contract::with('property')->whereId($request->contract_id)->first();

        $sqlServiceProvider   = Contract::with('service_provider')->whereId($request->contract_id)->first();
        
        return response()->json(['status'=>true,  'contract_service'=>$contract_service, 'sqlProperty'=>$sqlProperty, 'sqlServiceProvider'=>$sqlServiceProvider],200);
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
        $this->data['page_title']     = 'Edit Service';
        $logedInUser = \Auth::guard('admin')->user()->id;

         $service_data = ServiceAllocationManagement::whereId($id)->where('status', '<>', 'D')->first();

         $this->data['service_data'] = $service_data;

        $this->data['contract_list'] = Contract::whereIsActive(1)->get();


        try
        {           

            $details = ServiceAllocationManagement::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {

                
                if ($id == null) {
                    return redirect()->route('admin.service_management.list');
                }
                 $validationCondition = array(
                    'contract_id'          => 'required',
                    'property_id'          => 'required',
                    'service_provider_id'  => 'required',
                    'service_id'           => 'required',
                    'service_details'      => 'required',
                    'service_start_date'   => 'required',
                    'service_end_date'     => 'required',
                );
                $validationMessages = array(
                    'contract_id.required'         => 'Please enter name',
                    'property_id.required'         => 'Please select country',
                    'service_provider_id.required' => 'Please select state',
                    'service_id.required'          => 'Please select country',
                    'service_details.required'     => 'Service Details is required',
                    'service_start_date.required'  => 'Please select state',
                    'service_end_date.required'    => 'Please select state',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {

                    $start_date=Carbon::createFromFormat('d/m/Y', $request->service_start_date)->format('Y-m-d');
                    $end_date=Carbon::createFromFormat('d/m/Y', $request->service_end_date)->format('Y-m-d');

                    $details->contract_id            = $request->contract_id;
                    $details->property_id            = $request->property_id;
                    $details->service_provider_id    = $request->service_provider_id;
                    $details->service_name           = $request->service_id;
                    $details->service_details        = $request->service_details;
                    $details->service_start_date     = $start_date;
                    $details->service_end_date       = $end_date;
                    $details->created_at             = date('Y-m-d H:i:s');
                    $details->created_by             = $logedInUser;
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
            
           // $this->data['contract_list']=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            //$this->data['property']   = Contract::with('property')->whereId($id)->first();
            $this->data['property']   = Contract::with('property')->whereId($service_data->contract_id)->first();

            $sqlService = ServiceAllocationManagement::where('contract_id',$service_data->contract_id)->where('id', '<>', $id)->get();

           $sqlService =  $sqlService->pluck('service_name');
           $this->data['contract_service'] = ContractService::with('service')->whereNotIn('service_id', $sqlService)->where('contract_id',$service_data->contract_id)->get();


            $this->data['sqlServiceProvider']   = Contract::with('service_provider')->whereId($service_data->contract_id)->first();
            $state_list=State::whereIsActive('1')->whereCountryId($details->country_id)->orderBy('id','ASC')->get();
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

            $details = ServiceAllocationManagement::where('id', $id)->first();
            if ($details != null) {
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'Service has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the city');
                    }
            } else {
                $request->session()->flash('alert-danger', 'Invalid Service');
                
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
      //  $sqlService=ServiceAllocationManagement::findOrFail($id);
        $sqlService=ServiceAllocationManagement::with(['contract','property','service_provider','service'])
            ->whereHas('contract')
            ->whereHas('property')
            ->whereHas('service_provider')
            ->whereHas('service')->findOrFail($id);

        $this->data['page_title']='Service Details';
        $this->data['service_allocation_data']=$sqlService;
        return view($this->view_path.'.show',$this->data);
    }
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
