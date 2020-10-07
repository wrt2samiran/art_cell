<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\State;
use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class StateController extends Controller
{

    private $view_path='admin.state';

    /*****************************************************/
    # StateController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing State List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Country List';
        if($request->ajax()){

            $state=State::orderBy('id','ASC')->orderBy('id','ASC');
            return Datatables::of($state)
            ->editColumn('created_at', function ($state) {
                return $state->created_at ? with(new Carbon($state->created_at))->format('m/d/Y') : '';
            })
            // ->editColumn('role_description', function ($role) {
            //     return Str::limit($country->role_description,100);
            // })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($state){
                if($state->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the gallery image" href="javascript:change_status('."'".route('admin.state.change_status',$state->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the gallery image" href="javascript:change_status('."'".route('admin.state.change_status',$state->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($state){
                $delete_url=route('admin.state.delete',$state->id);
                $details_url=route('admin.state.show',$state->id);
                $edit_url=route('admin.state.edit',$state->id);

                return '<a title="View Country Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Country" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete state" href="javascript:delete_country('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # StateController
    # Function name : stateAdd
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Adding new Country
    # Params        : Request $request
    /*****************************************************/
    public function stateAdd(Request $request) {

        $data['page_title']     = 'Add State';
        $data['panel_title']    = 'Add State';
    
        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:'.(new Country)->getTable().',name',
                    'country_code'  => 'required|min:2|max:8|unique:'.(new Country)->getTable().',country_code',
                    'dial_code'     => 'required|min:2|max:5',
                );
                $validationMessages = array(
                    'name.required'         => 'Please enter name',
                    'name.min'              => 'Name should be should be at least 2 characters',
                    'name.max'              => 'Name should not be more than 255 characters',
                    'country_code.required' => 'Please enter state code',
                    'country_code.min'      => 'Country code should be should be at least 2 characters',
                    'country_code.max'      => 'Country code should not be more than 8 characters',
                    'dial_code.required'    => 'Please enter dial code',
                    'dial_code.min'         => 'Dial code should be should be at least 2 characters',
                    'dial_code.max'         => 'Dial code should not be more than 5 characters',

               
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('admin.state.add')->withErrors($Validator)->withInput();
                } else {
                    
                    $new = new State;
                    $new->name = trim($request->name, ' ');
                    $new->country_code  = $request->country_code;
                    $new->dial_code  = $request->dial_code;
                    $new->created_at = date('Y-m-d H:i:s');
                    $save = $new->save();
                
                    if ($save) {                        
                        $request->session()->flash('alert-success', 'State has been added successfully');
                        return redirect()->route('admin.state.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while adding the state');
                        return redirect()->back();
                    }
                }
            }
            return view('admin.state.add', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.state.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CountryController
    # Function name : cityEdit
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']     = 'Edit State';
        $data['panel_title']    = 'Edit State';

        try
        {           
            //$pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
           // $data['pageNo'] = $pageNo;
           
            $details = State::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
               // dd($request->all());
                if ($id == null) {
                    return redirect()->route('admin.state.list');
                }
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:' .(new Country)->getTable().',name,'.$id.'',
                    'country_code'  => 'required|min:2|max:8|unique:'.(new Country)->getTable().',country_code,'.$id.'',
                    'dial_code'     => 'required|min:2|max:5',
                );
                $validationMessages = array(
                    'name.required'         => 'Please enter name',
                    'name.min'              => 'Name should be should be at least 2 characters',
                    'name.max'              => 'Name should not be more than 255 characters',
                    'country_code.required' => 'Please enter state code',
                    'country_code.min'      => 'Country code should be should be at least 2 characters',
                    'country_code.max'      => 'Country code should not be more than 8 characters',
                    'dial_code.required'    => 'Please enter dial code',
                    'dial_code.min'         => 'Dial code should be should be at least 2 characters',
                    'dial_code.max'         => 'Dial code should not be more than 5 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->name        = trim($request->name, ' ');
                    $details->country_code    = $request->country_code;
                    $details->dial_code       = $request->dial_code;
                    $details->updated_at      = date('Y-m-d H:i:s');
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'State has been updated successfully');
                        return redirect()->route('admin.state.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.state.edit', $data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.state.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # StateController
    # Function name : change_status
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Change state status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.state.list');
            }
            $details = Country::where('id', $id)->first();
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
                return redirect()->route('admin.state.list')->with('error', 'Invalid state');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.state.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CountryController
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
                return redirect()->route('admin.state.list');
            }

            $details = Country::where('id', $id)->first();
            if ($details != null) {
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'State has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the state');
                    }
            } else {
                $request->session()->flash('alert-danger', 'Invalid state');
                
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('admin.state.list')->with('error', $e->getMessage());
        }
    }
    

    public function show($id){
        return view($this->view_path.'.show');
    }
}
