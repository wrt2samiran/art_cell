<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\UnitMaster;
use App\Http\Requests\Admin\Unit\{CreateUnitRequest,UpdateUnitRequest};

class UnitController extends Controller
{

    private $view_path='admin.unit';

    /*****************************************************/
    # MessageController
    # Function name : List
    # Author        :
    # Created Date  : 25-11-2020
    # Purpose       : Show Shared Service List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Unit List';
        if($request->ajax()){

            $sqlUnitMaster=UnitMaster::orderBy('id','ASC')->orderBy('id','DESC');
            return Datatables::of($sqlUnitMaster)
            ->editColumn('created_at', function ($sqlUnitMaster) {
                return $sqlUnitMaster->created_at ? with(new Carbon($sqlUnitMaster->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            
            ->addColumn('status',function($sqlUnitMaster){

                if($sqlUnitMaster->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the unit" href="javascript:change_status('."'".route('admin.unit.change_status',$sqlUnitMaster->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the unit" href="javascript:change_status('."'".route('admin.unit.change_status',$sqlUnitMaster->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            
            ->addColumn('action',function($sqlUnitMaster){
                $delete_url=route('admin.unit.delete',$sqlUnitMaster->id);
                $edit_url=route('admin.unit.edit',$sqlUnitMaster->id);
                $action_buttons='';
                
            
            //need to check permissions later
            if(true){
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Unit" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
            }
            //need to check permissions later
            if(true){
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Unit" href="javascript:delete_user('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
            }

            return $action_buttons;

                
                
            })
            ->rawColumns(['action','status'])
            ->make(true);

        }


        return view($this->view_path.'.list',$this->data);
    }

   

    /********************************************************************************/
    # Function to store user data                                                    #
    # Function name    : store                                                       #
    # Created Date     : 25-11-2020                                                  #
    # Modified date    : 25-11-2020                                                  #
    # Purpose          : store user data                                             #
    # Param            : CreateUnitRequest $request                                  #

    public function add(Request $request){

        $data['page_title']     = 'Add Unit';
        $logedin_user=auth()->guard('admin')->user();
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'unit_name' => 'required|min:2|max:255|unique:'.(new UnitMaster)->getTable().',unit_name',      
				);
				$validationMessages = array(
					'unit_name.required'                => 'Please enter name',
					'unit_name.min'                     => 'Name should be should be at least 2 characters',
                    'unit_name.max'                     => 'Name should not be more than 255 characters',

				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.unit.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new UnitMaster;
                    $new->unit_name             = trim($request->unit_name, ' ');
                    $new->created_by            = auth()->guard('admin')->id();
                    $new->updated_by            = auth()->guard('admin')->id();
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Unit has been added successfully');
						return redirect()->route('admin.unit.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the unit');
						return redirect()->back();
					}
				}
            }
			return view('admin.unit.create', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.unit.list')->with('error', $e->getMessage());
		}


    }

    
    
    /************************************************************************/
    # Function to load user edit page                            #
    # Function name    : edit                                                #
    # Created Date     : 25-11-2020                                          #
    # Modified date    : 25-11-2020                                          #
    # Purpose          : to load user edit page                  #
    # Param            : id                                                  #
    public function edit($id){
        $unit=UnitMaster::findOrFail($id);
        
        $this->data['page_title']='Edit Unit';
        $this->data['unit']=$unit;
       
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update user data                                           #
    # Function name    : update                                                          #
    # Created Date     : 25-11-2020                                                      #
    # Modified date    : 25-11-2020                                                      #
    # Purpose          : to update user data                                 #
    # Param            : UpdateLabourRequest $request,id                                   #
    public function update(Request $request,$id){

        $unit=UnitMaster::findOrFail($id);
        $unit->unit_name     = trim($request->unit_name, ' ');
        $unit->updated_by     = auth()->guard('admin')->id();
        $save = $unit->save(); 

        return redirect()->route('admin.unit.list')->with('success','Unit successfully updated.');

    }

    
    /*****************************************************/
    # MessageController
    # Function name : change_status
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Change Message status
    # Params        : Request $request
    /*****************************************************/
    public function change_status($id = null)
    {
        $unit=UnitMaster::findOrFail($id);
        $change_status_to=($unit->status=='A')?'I':'A';
        $message=($unit->status=='A')?'deactivated':'activated';
         //updating unit status
        $unit->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Unit successfully '.$message.'.']);
    }

    /************************************************************************/
    # Function to delete user                                                #
    # Function name    : delete                                              #
    # Created Date     : 25-11-2020                                          #
    # Modified date    : 25-11-2020                                          #
    # Purpose          : to delete user                                      #
    # Param            : id                                                  #
    public function delete($id){
        $unit=UnitMaster::findOrFail($id);
        
        $unit->delete();
        return response()->json(['message'=>'Unit successfully deleted.']);

    }
    
    /************************************************************************/
    # Function to show/load details page for user                            #
    # Function name    : show                                                #
    # Created Date     : 25-11-2020                                          #
    # Modified date    : 25-11-2020                                          #
    # Purpose          : show/load details page for user                     #
    # Param            : id                                                  #

    public function show($id){
        $user=UnitMaster::findOrFail($id);
        $this->data['page_title']='Unit Details';
        $this->data['user']=$user;
        return view($this->view_path.'.show',$this->data);

    }

    
}
