<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UnitMaster;
use App\Models\SparePart;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class SparePartsController extends Controller
{

    private $view_path='admin.spare-parts';

    /*****************************************************/
    # SparePartsController
    # Function name : List
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Show Spare Parts List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Spare Parts List';
        if($request->ajax()){

            $spareParts=SparePart::with('unitmaster')->orderBy('id','Desc');
            return Datatables::of($spareParts)
            ->editColumn('created_at', function ($spareParts) {
                return $spareParts->created_at ? with(new Carbon($spareParts->created_at))->format('m/d/Y') : '';
            })
            // ->editColumn('description', function ($sharedService) {
            //     return Str::limit($sharedService->description,100);
            // })
            // ->editColumn('number_of_days', function ($sharedService) {
            //     return Str::limit($sharedService->number_of_days,100);
            // })
            // ->editColumn('price', function ($sharedService) {
            //     return Str::limit($sharedService->price,100);
            // })
            // ->editColumn('extra_price_per_day', function ($sharedService) {
            //     return Str::limit($sharedService->extra_price_per_day,100);
            // })
            // ->editColumn('currency', function ($sharedService) {
            //     return Str::limit($sharedService->currency,100);
            // })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($spareParts){
                if($spareParts->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the Spare Parts" href="javascript:change_status('."'".route('admin.spare-parts.change_status',$spareParts->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the Spare Parts" href="javascript:change_status('."'".route('admin.spare-parts.change_status',$spareParts->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($spareParts){
                $delete_url=route('admin.spare-parts.delete',$spareParts->id);
                $details_url=route('admin.spare-parts.show',$spareParts->id);
                $edit_url=route('admin.spare-parts.edit',$spareParts->id);

                return '<a title="View Spare Parts Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Spare Parts" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete Spare Parts" href="javascript:delete_shared_service('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # SparePartsController
    # Function name : sparePartsAdd
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Add new Spare Parts
    # Params        : Request $request
    /*****************************************************/
    public function sparePartsAdd(Request $request) {

        $this->data['page_title']     = 'Add Spare Part';
        //$data['panel_title']    = 'Add Spare Part';
        $logedin_user=auth()->guard('admin')->user();
        $unit_list = UnitMaster::where('is_active','1')->orderBy('unit_name', 'Asc')->get();
        $this->data['unit_list'] = $unit_list;
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:'.(new SparePart)->getTable().',name',
                    'manufacturer'    => 'required|min:2|max:255',
                    'unit_master_id'  => 'required',
                    'price'     => 'required',
                   // 'currency'     => 'required',
				);
				$validationMessages = array(
					'name.required'                => 'Please enter name',
					'name.min'                     => 'Name should be should be at least 2 characters',
                    'name.max'                     => 'Name should not be more than 255 characters',
                    'manufacturer.required'        => 'Please enter Manufacturer name',
                    'manufacturer.min'             => 'Manufacturer name should be at least 2 characters',
                    'manufacturer.max'             => 'Manufacturer name should not be more than 255 characters',
                    'unit_master_id.required'      => 'Unit is required',
                    'price.required'               => 'Price is required',
                   // 'currency.required'            => 'Currency is required',               
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.spare-parts.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new SparePart;
                    $new->name = trim($request->name, ' ');
                    $new->manufacturer  = $request->manufacturer;
                    $new->description  = $request->description;
                    $new->unit_master_id  = $request->unit_master_id;
                    $new->price  = $request->price;
                    $new->currency  = 'AED';
                    $new->created_at = Carbon::now();
                    $new->created_by = $logedin_user->id;
                    $new->updated_by = $logedin_user->id;
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Spare Spart has been added successfully');
						return redirect()->route('admin.spare-parts.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the state');
						return redirect()->back();
					}
				}
            }
            return view($this->view_path.'.add',$this->data);
		} catch (Exception $e) {
			return redirect()->route('admin.spare-parts.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # SparePartsController
    # Function name : edit
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Edit Spare Parts
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $this->data['page_title']     = 'Edit Spare Part';
        //$data['panel_title']    = 'Edit Spare Part';
        $logedin_user=auth()->guard('admin')->user();
        $unit_list = UnitMaster::where('is_active','1')->orderBy('unit_name', 'Asc')->get();
        $this->data['unit_list'] = $unit_list;

        try
        {           
           
            $details = SparePart::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
               // dd($request->all());
                if ($id == null) {
                    return redirect()->route('admin.spare-parts.list');
                }
               
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:'.(new SparePart)->getTable().',name,'.$id.'',
                    'manufacturer'    => 'required|min:2|max:255',
                    'unit_master_id'  => 'required',
                    'price'     => 'required',
                  //  'currency'     => 'required',
                );
                $validationMessages = array(
                    'name.required'                => 'Please enter name',
                    'name.min'                     => 'Name should be should be at least 2 characters',
                    'name.max'                     => 'Name should not be more than 255 characters',
                    'manufacturer.required'        => 'Please enter Manufacturer name',
                    'manufacturer.min'             => 'Manufacturer name should be at least 2 characters',
                    'manufacturer.max'             => 'Manufacturer name should not be more than 255 characters',
                    'unit_master_id.required'      => 'Unit is required',
                    'price.required'               => 'Price is required',
                  //  'currency.required'            => 'Currency is required',               
                );

                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    
                    $details->name = trim($request->name, ' ');
                    $details->manufacturer  = $request->manufacturer;
                    $details->description  = $request->description;
                    $details->unit_master_id  = $request->unit_master_id;
                    $details->price  = $request->price;
                    $details->currency  = 'AED';
                    $details->updated_at = Carbon::now();
                    $details->updated_by = $logedin_user->id;
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Spare Parts has been updated successfully');
                        return redirect()->route('admin.spare-parts.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view($this->view_path.'.edit',$this->data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.spare-parts.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # SparePartsController
    # Function name : change_status
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Change Spare Parts status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.spare-parts.list');
            }
            $details = SparePart::where('id', $id)->first();
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
                return redirect()->route('admin.spare-parts.list')->with('error', 'Invalid Sapre Parts');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.spare-parts.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # SparePartsController
    # Function name : delete
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Delete Spare Parts
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.spare-parts.list');
            }

            $details = SparePart::where('id', $id)->first();
            if ($details != null) {
                    $delete = $details->delete();
                    if ($delete) {
                        $request->session()->flash('alert-danger', 'Shared Service has been deleted successfully');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while deleting the state');
                    }
            } else {
                $request->session()->flash('alert-danger', 'Invalid state');
                
            }
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->route('admin.spare-parts.list')->with('error', $e->getMessage());
        }
    }
    
    /*****************************************************/
    # SparePartsController
    # Function name : show
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Showing Spare Parts details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $spareParts=SparePart::findOrFail($id);
        $this->data['page_title']='Spare Parts Details';
        $this->data['spareParts']=$spareParts;
        return view($this->view_path.'.show',$this->data);
    }
}
