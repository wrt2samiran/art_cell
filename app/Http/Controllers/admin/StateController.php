<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\State;
use App\Models\StateTranslation;
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
        $this->data['page_title']='State List';
        if($request->ajax()){

            $state=State::with('country')->orderBy('id','ASC');
            return Datatables::of($state)
            ->editColumn('created_at', function ($state) {
                return $state->created_at ? with(new Carbon($state->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($state){
                if($state->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the state" href="javascript:change_status('."'".route('admin.state.change_status',$state->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the state" href="javascript:change_status('."'".route('admin.state.change_status',$state->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($state){
                $delete_url=route('admin.state.delete',$state->id);
                $details_url=route('admin.state.show',$state->id);
                $edit_url=route('admin.state.edit',$state->id);

                return '<a title="View State Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit State" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete state" href="javascript:delete_state('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
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

        $this->data['page_title']     = 'Add State';
        $this->data['panel_title']    = 'Add State';
    
        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:'.(new State)->getTable().',name',
                    'country_id'    => 'required',
                    'ar_name'       =>'required|min:2|max:255',
                );
                $validationMessages = array(
                    'name.required'            => 'Please enter name',
                    'name.min'                 => 'Name should be should be at least 2 characters',
                    'name.max'                 => 'Name should not be more than 255 characters',
                    'country_id.required'      => 'Please select country',
                    'ar_name.required'         => 'Please enter arabic name',
                    'ar_name.min'              => 'Arabic Name should be should be at least 2 characters',
                    'ar_name.max'              => 'Arabic Name should not be more than 255 characters',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('admin.state.add')->withErrors($Validator)->withInput();
                } else {
                    
                    $new = new State;
                    $new->name = trim($request->name, ' ');
                    $new->country_id  = $request->country_id;
                    
                    $new->created_at = date('Y-m-d H:i:s');
                    $save = $new->save();
                
                    if ($save) {
                        
                        $insertedId = $new->id;

                        $languages = \Helper::WEBITE_LANGUAGES;
                        foreach ($languages as $language) {
                            $newLocal                   = new StateTranslation;
                            $newLocal->state_id          = $insertedId;
                            $newLocal->locale           = $language;
                            if ($language == 'en') {
                                $newLocal->name        = trim($request->name, ' ');
                                
                            } else{
                                $newLocal->name        = trim($request->ar_name, ' ');
                            } 
                            $saveLocal = $newLocal->save();
                        }
                        return redirect()->route('admin.state.list')->with('success','State successfully created.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while adding the state');
                        return redirect()->back();
                    }
                }
            }

            $country_list=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['country_list']=$country_list;
            return view($this->view_path.'.add',$this->data);
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
        $this->data['page_title']     = 'Edit State';
        $this->data['panel_title']    = 'Edit State';

        try
        {           

            $details = State::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {

                
                if ($id == null) {
                    return redirect()->route('admin.state.list');
                }
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:' .(new State)->getTable().',name,'.$id.'',
                    'country_id'    => 'required',
                    'ar_name'       =>'required|min:2|max:255',
                );
                $validationMessages = array(
                    'name.required'            => 'Please enter name',
                    'name.min'                 => 'Name should be should be at least 2 characters',
                    'name.max'                 => 'Name should not be more than 255 characters',
                    'country_id.required'      => 'Please select country',
                    'ar_name.required'         => 'Please enter arabic name',
                    'ar_name.min'              => 'Arabic Name should be should be at least 2 characters',
                    'ar_name.max'              => 'Arabic Name should not be more than 255 characters',

                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->name        = trim($request->name, ' ');
                    $details->country_id  = $request->country_id;
                    $details->updated_at      = date('Y-m-d H:i:s');
                    $save = $details->save();                        
                    if ($save) {
                        StateTranslation::where('state_id', $id)->delete();
                        $languages = \Helper::WEBITE_LANGUAGES;
                        foreach($languages as $language){
                            $newLocal                   = new StateTranslation;
                            $newLocal->state_id          = $id;
                            $newLocal->locale           = $language;
                            if ($language == 'en') {
                                $newLocal->name        = trim($request->name, ' ');
                                
                            } else {
                                $newLocal->name        = trim($request->ar_name, ' ');
                            }
                            $saveLocal = $newLocal->save();
                        }
                        return redirect()->route('admin.state.list')->with('success','State successfully updated.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            $country_list=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['country_list']=$country_list;
            return view($this->view_path.'.edit',$this->data)->with(['details' => $details]);
           // return view('admin.state.edit', $data)->with(['details' => $details]);

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
            $details = State::where('id', $id)->first();
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
    # StateController
    # Function name : stateDelete
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing subAdminList of state
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        
            if ($id == null) {
                return redirect()->route('admin.state.list');
            }

            $details = State::where('id', $id)->first();
                    $delete = $details->delete();
            return response()->json(['message'=>'State successfully deleted.']);           
    }
    

    /*****************************************************/
    # StateController
    # Function name : show
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing State details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){

        $state=State::findOrFail($id);
        $this->data['page_title']='Sate Details';
        $this->data['state']=$state;
        return view($this->view_path.'.show',$this->data);
    }
}
