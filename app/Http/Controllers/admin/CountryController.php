<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Helper;
use Image, Auth, Hash, Redirect, Validator, View;
use AdminHelper;
use App\Models\Country;

class CountryController extends Controller
{
    /*****************************************************/
    # StateController
    # Function name : cityList
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request) {
        $data['page_title'] = 'Country List';
        $data['panel_title']= 'Country List';
        
        try
        {
            $pageNo = $request->input('page');
            Session::put('pageNo',$pageNo);

            $data['order_by']   = 'created_at';
            $data['order']      = 'desc';

            $query = Country::whereNull('deleted_at');

            $data['searchText'] = $key = $request->searchText;

            if ($key) {
                // if the search key is provided, proceed to build query for search
                $query->where(function ($q) use ($key) {
                    $q->where('name', 'LIKE', '%' . $key . '%');
                    
                
                });
            }
            $exists = $query->count();
            if ($exists > 0) {
                $list = $query->orderBy($data['order_by'], $data['order'])->paginate(AdminHelper::ADMIN_LIST_LIMIT);
                $data['list'] = $list;
            } else {
                $data['list'] = array();
            }       
            return view('admin.country.list', $data);
        } catch (Exception $e) {
            return redirect()->route('admin.country.list')->with('error', $e->getMessage());
        }
    }

   /*****************************************************/
    # StateController
    # Function name : cityAdd
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function add(Request $request) {
        $data['page_title']     = 'Add State';
        $data['panel_title']    = 'Add State';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'name' => 'required|min:2|max:255|unique:'.(new State)->getTable().',name',
				);
				$validationMessages = array(
					'name.required'    => 'Please enter name',
					'name.min'         => 'Name should be should be at least 2 characters',
                    'name.max'         => 'Name should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.state.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new State;
                    $new->name = trim($request->name, ' ');
                    $new->country_id  = '101';
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
    # StateController
    # Function name : cityEdit
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']  = 'Edit Satate';
        $data['panel_title'] = 'Edit Satate';

        try
        {           
            $pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
            $data['pageNo'] = $pageNo;
           
            $details = State::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.state.list');
                }
                $validationCondition = array(
                    'name'         => 'required|min:2|max:255|unique:' .(new State)->getTable().',name,'.$id.'',
                );
                $validationMessages = array(
                    'name.required'    => 'Please enter name',
                    'name.min'         => 'Name should be should be at least 2 characters',
                    'name.max'         => 'Name should not be more than 255 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->name        = trim($request->name, ' ');
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'State has been updated successfully');
                        return redirect()->route('admin.state.list', ['page' => $pageNo]);
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.state.edit')->with(['details' => $details, 'data' => $data]);

        } catch (Exception $e) {
            return redirect()->route('admin.state.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # StateController
    # Function name : cityStatus
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.state.list');
            }
            $details = State::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 1) {
                    
                    $details->status = '0';
                    $details->save();
                        
                    $request->session()->flash('alert-success', 'Status updated successfully');                 
                     } else if ($details->status == 0) {
                    $details->status = '1';
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

            $details = State::where('id', $id)->first();
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
    
}
