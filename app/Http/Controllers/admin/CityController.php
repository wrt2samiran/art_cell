<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class CityController extends Controller
{

    private $view_path='admin.city';

    /*****************************************************/
    # CityController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing City List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='City List';
        if($request->ajax()){

            $city=City::with('state')->with('country')->orderBy('id','Desc');
            return Datatables::of($city)
            ->editColumn('created_at', function ($city) {
                return $city->created_at ? with(new Carbon($city->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($city){
                if($city->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the city" href="javascript:change_status('."'".route('admin.city.change_status',$city->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the city" href="javascript:change_status('."'".route('admin.city.change_status',$city->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($city){
                $delete_url=route('admin.city.delete',$city->id);
                $details_url=route('admin.city.show',$city->id);
                $edit_url=route('admin.city.edit',$city->id);

                return '<a title="View City Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit City" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete city" href="javascript:delete_city('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
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
                    
                    $new = new City;
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

            $details = City::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {

                
                if ($id == null) {
                    return redirect()->route('admin.city.list');
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
                        return redirect()->route('admin.city.list');
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
            return redirect()->route('admin.city.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.city.list');
            }
            $details = City::where('id', $id)->first();
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
                return redirect()->route('admin.city.list')->with('error', 'Invalid city');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.city.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.city.list');
            }

            $details = City::where('id', $id)->first();
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
            return redirect()->route('admin.city.list')->with('error', $e->getMessage());
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
