<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SharedService;
use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class SharedServiceController extends Controller
{

    private $view_path='admin.shared-service';

    /*****************************************************/
    # SharedServiceController
    # Function name : List
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Show Shared Service List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Shared Service List';
        if($request->ajax()){

            $sharedService=SharedService::orderBy('id','Desc');
            return Datatables::of($sharedService)
            ->editColumn('created_at', function ($sharedService) {
                return $sharedService->created_at ? with(new Carbon($sharedService->created_at))->format('m/d/Y') : '';
            })
            ->editColumn('description', function ($sharedService) {
                return Str::limit($sharedService->description,100);
            })
            ->editColumn('number_of_days', function ($sharedService) {
                return Str::limit($sharedService->number_of_days,100);
            })
            ->editColumn('price', function ($sharedService) {
                return Str::limit($sharedService->price,100);
            })
            ->editColumn('extra_price_per_day', function ($sharedService) {
                return Str::limit($sharedService->extra_price_per_day,100);
            })
            ->editColumn('currency', function ($sharedService) {
                return Str::limit($sharedService->currency,100);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($sharedService){
                if($sharedService->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the shared service" href="javascript:change_status('."'".route('admin.shared-service.change_status',$sharedService->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the shared service" href="javascript:change_status('."'".route('admin.shared-service.change_status',$sharedService->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($sharedService){
                $delete_url=route('admin.shared-service.delete',$sharedService->id);
                $details_url=route('admin.shared-service.show',$sharedService->id);
                $edit_url=route('admin.shared-service.edit',$sharedService->id);

                return '<a title="View Shared Service Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Shared Service" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete shared service" href="javascript:delete_shared_service('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # SharedServiceController
    # Function name : sharedServiceAdd
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Add new Shared Service
    # Params        : Request $request
    /*****************************************************/
    public function sharedServiceAdd(Request $request) {

        $data['page_title']     = 'Add Shared Service';
        $data['panel_title']    = 'Add Shared Service';
        $logedin_user=auth()->guard('admin')->user();
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:'.(new SharedService)->getTable().',name',
                    'number_of_days'  => 'required',
                    'price'     => 'required',
                    'extra_price_per_day'  => 'required',
                    //'currency'     => 'required',
				);
				$validationMessages = array(
					'name.required'                => 'Please enter name',
					'name.min'                     => 'Name should be should be at least 2 characters',
                    'name.max'                     => 'Name should not be more than 255 characters',
                    'number_of_days.required'      => 'Number of Days is required',
                    'price.required'               => 'Price is required',
                    'extra_price_per_day.required' => 'Extra Price/day is required',
                   // 'currency.required'            => 'Currency is required',
                    

               
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.shared-service.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new SharedService;
                    $new->name = trim($request->name, ' ');
                    $new->description  = $request->description;
                    $new->number_of_days  = $request->number_of_days;
                    $new->price  = $request->price;
                    $new->extra_price_per_day  = $request->extra_price_per_day;
                    $new->currency  = 'AED';
                    $new->created_at = Carbon::now();
                    $new->created_by = $logedin_user->id;
                    $new->updated_by = $logedin_user->id;
                    $save = $new->save();
                
					if ($save) {						
                        return redirect()->route('admin.shared-service.list')->with('success','Shared Service successfully created.');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the state');
						return redirect()->back();
					}
				}
            }
			return view('admin.shared-service.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.shared-service.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # SharedServiceController
    # Function name : edit
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Edit Shared Service
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']     = 'Edit Shared Service';
        $data['panel_title']    = 'Edit Shared Service';
        $logedin_user=auth()->guard('admin')->user();

        try
        {           
            //$pageNo = Session::get('pageNo') ? Session::get('pageNo') : '';
           // $data['pageNo'] = $pageNo;
           
            $details = SharedService::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
               // dd($request->all());
                if ($id == null) {
                    return redirect()->route('admin.shared-service.list');
                }
               
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:'.(new SharedService)->getTable().',name,'.$id.'',
                    'number_of_days'  => 'required',
                    'price'     => 'required',
                    'extra_price_per_day'  => 'required',
                    //'currency'     => 'required',
                );
                $validationMessages = array(
                    'name.required'                => 'Please enter name',
                    'name.min'                     => 'Name should be should be at least 2 characters',
                    'name.max'                     => 'Name should not be more than 255 characters',
                    'number_of_days.required'      => 'Number of Days is required',
                    'price.required'               => 'Price is required',
                    'extra_price_per_day.required' => 'Extra Price/day is required',
                    //'currency.required'            => 'Currency is required',
                    

               
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    
                    $details->name = trim($request->name, ' ');
                    $details->description  = $request->description;
                    $details->number_of_days  = $request->number_of_days;
                    $details->price  = $request->price;
                    $details->extra_price_per_day  = $request->extra_price_per_day;
                    $details->currency  = 'AED';
                    $details->updated_at = Carbon::now();
                    $details->updated_by = $logedin_user->id;
                    $save = $details->save();                        
                    if ($save) {
                        
                        return redirect()->route('admin.shared-service.list')->with('success','Shared Service successfully updated.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.shared-service.edit', $data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.shared-service.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # SharedServiceController
    # Function name : change_status
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Change Shared Service status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.shared-service.list');
            }
            $details = SharedService::where('id', $id)->first();
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
                return redirect()->route('admin.shared-service.list')->with('error', 'Invalid Shared Service');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.shared-service.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # SharedServiceController
    # Function name : delete
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Delete Shared Service
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.shared-service.list');
            }

            $details = SharedService::where('id', $id)->first();
            if ($details != null) {
                    $delete = $details->delete();
                    if ($delete) {
                        return response()->json(['message'=>'Shared Service successfully deleted.']);
                    } else {
                        return response()->json(['message'=>'Something went wrong! Please try again letter.']);
                    }
            } 
           
        } catch (Exception $e) {
            return redirect()->route('admin.shared-service.list')->with('error', $e->getMessage());
        }
    }
    
    /*****************************************************/
    # SharedServiceController
    # Function name : show
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Showing Shared Service details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $sharedServices=SharedService::findOrFail($id);
        $this->data['page_title']='Shared Service Details';
        $this->data['sharedServices']=$sharedServices;
        return view($this->view_path.'.show',$this->data);
    }
}
