<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class CountryController extends Controller
{

    private $view_path='admin.country';

    /*****************************************************/
    # CountryController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing Country List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Country List';
        if($request->ajax()){

            $country=Country::orderBy('id','ASC')->orderBy('id','ASC');
            return Datatables::of($country)
            ->editColumn('created_at', function ($country) {
                return $country->created_at ? with(new Carbon($country->created_at))->format('m/d/Y') : '';
            })
            // ->editColumn('role_description', function ($role) {
            //     return Str::limit($country->role_description,100);
            // })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($country){
                if($country->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the gallery image" href="javascript:change_status('."'".route('admin.country.change_status',$country->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the gallery image" href="javascript:change_status('."'".route('admin.country.change_status',$country->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($country){
                $delete_url=route('admin.country.delete',$country->id);
                $details_url=route('admin.country.show',$country->id);
                $edit_url=route('admin.country.edit',$country->id);

                return '<a title="View Country Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Country" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete country" href="javascript:delete_country('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # CountryController
    # Function name : countryAdd
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Adding new Country
    # Params        : Request $request
    /*****************************************************/
    public function countryAdd(Request $request) {

        $data['page_title']     = 'Add Country';
        $data['panel_title']    = 'Add Country';
    
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
                    'country_code.required' => 'Please enter country code',
                    'country_code.min'      => 'Country code should be should be at least 2 characters',
                    'country_code.max'      => 'Country code should not be more than 8 characters',
                    'dial_code.required'    => 'Please enter dial code',
                    'dial_code.min'         => 'Dial code should be should be at least 2 characters',
                    'dial_code.max'         => 'Dial code should not be more than 5 characters',

               
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.country.country.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new Country;
                    $new->name = trim($request->name, ' ');
                    $new->country_code  = $request->country_code;
                    $new->dial_code  = $request->dial_code;
                    $new->created_at = date('Y-m-d H:i:s');
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'State has been added successfully');
						return redirect()->route('admin.country.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the state');
						return redirect()->back();
					}
				}
            }
			return view('admin.country.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.country.list')->with('error', $e->getMessage());
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
        $data['page_title']     = 'Edit Country';
        $data['panel_title']    = 'Edit Country';

        try
        {           
            //$pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
           // $data['pageNo'] = $pageNo;
           
            $details = Country::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
               // dd($request->all());
                if ($id == null) {
                    return redirect()->route('admin.country.list');
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
                    'country_code.required' => 'Please enter country code',
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
                        return redirect()->route('admin.country.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.country.edit', $data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.country.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CountryController
    # Function name : change_status
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Change country status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.country.list');
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
                return redirect()->route('admin.country.list')->with('error', 'Invalid country');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.country.list')->with('error', $e->getMessage());
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
                return redirect()->route('admin.country.list');
            }

            $details = Country::where('id', $id)->first();
            if ($details != null) {
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'Country has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the state');
                    }
            } else {
                $request->session()->flash('alert-danger', 'Invalid state');
                
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('admin.country.list')->with('error', $e->getMessage());
        }
    }
    

    public function show($id){
        $country=Country::findOrFail($id);
        $this->data['page_title']='Country Details';
        $this->data['country']=$country;
        return view($this->view_path.'.show',$this->data);
    }
}
