<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View;
use App\Models\ModuleFunctionality;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Config;

class ModuleManagementController extends Controller
{
    public $data= array();
    public function __construct(Request $request){
        parent::__construct($request);
        //  $this->middleware('check.permission');
    }
    /*****************************************************/
    # ModuleManagementController
    # Function name : moduleList
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display module List
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function moduleList(Request $request){
        $this->data['page_title']="Module List";
        $this->data['panel_title']="Module List";

        return view('admin.module.list',$this->data);
    }
    /*****************************************************/
    # ModuleManagementController
    # Function name : moduleListTable
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display module List
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function moduleListTable(Request $request){
        $data = Module::where('is_deleted','N')->get();
        $finalResponse= Datatables::of($data)
            ->addColumn('status', function ($model) {
                $modulestatuslink= route('admin.module-management.reset-module-status',  encrypt($model->id, Config::get('constant.enc_key')));
                
                    if($model->status == 'A'){
                        $statusHtml= '<button type="button" class="btn btn-block btn-success btn-xs changeStatus" data-redirect-url='.$modulestatuslink.' id="status'.$model->id.'">Active</button>';
                    } else{
                        $statusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs changeStatus" data-redirect-url='.$modulestatuslink.' id="status'.$model->id.'">Inactive</button>';
                    }
 
                return  $statusHtml;
            })
            ->addColumn('action', function ($model) {
               
                    $link1 = route('admin.module-management.edit',  encrypt($model->id, Config::get('constant.enc_key')));
                
                    $link2= route('admin.module-management.module-delete',  encrypt($model->id, Config::get('constant.enc_key')));
                
                
                $actions='<div class="btn-group btn-group-sm ">';
       
                    $actions .='<a href="' . $link1 . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>';
             
                
                    $actions .='<a href="javascript:void(0)" data-redirect-url="'.$link2.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>';
                
                $actions .='</div>';
                return $actions;
                
                
      
                
            
                
                
            })
        
            ->rawColumns(['action','status'])
            ->make(true);
            return $finalResponse;

    
}

    

    /*****************************************************/
    # ModuleManagementController
    # Function name : functionalityList
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display functionality List
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function functionalityList(Request $request){
        $this->data['page_title']="Functionality List";
        $this->data['panel_title']="Functionality List";
        return view('admin.module.functionality-list',$this->data);
    }

    /*****************************************************/
    # ModuleManagementController
    # Function name : functionalityTable
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display functionality List
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function functionalityTable(Request $request){

        $data = ModuleFunctionality::where('is_deleted','N')->with(['moduleData'])->get();
    
        $finalResponse= Datatables::of($data)

            ->addColumn('status', function ($model) {
                $functionstatuslink= route('admin.module-management.reset-function-status',  encrypt($model->id, Config::get('constant.enc_key')));
                
                    if($model->status == 'A'){
                        $fstatusHtml= '<button type="button" class="btn btn-block btn-success btn-xs fchangeStatus" data-redirect-url='.$functionstatuslink.' id="status'.$model->id.'">Active</button>';
                   
                    } else{
                            $fstatusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs fchangeStatus" data-redirect-url='.$functionstatuslink.' id="status'.$model->id.'">Inactive</button>';
                        }

                   return  $fstatusHtml;
            })
            ->addColumn('action', function ($model) {
                $link3 = route('admin.module-management.functionality-edit',  encrypt($model->id, Config::get('constant.enc_key')));
                $link4 = route('admin.module-management.function-delete',  encrypt($model->id, Config::get('constant.enc_key')));

                $factions='<div class="btn-group btn-group-sm ">';
                
                    $factions .='<a href="' . $link3 . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>';
              

                    $factions .='<a href="javascript:void(0)" data-redirect-url="'.$link4.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>';
             
                $factions .='</div>';
                return $factions;

                

       
            })
            ->rawColumns(['action','status'])
            ->make(true);


        return $finalResponse;
    }

    /*****************************************************/
    # ModuleManagementController
    # Function name : functionalityEdit
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Functionality Edit
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function functionalityEdit(Request $request, $encryptString){
        $this->data['page_title']='Functionality Edit';
        $this->data['panel_title']='Functionality Edit';
        $functionId = decrypt($encryptString, Config::get('constant.enc_key')); // get user-id After Decrypt with salt key.
        $details = ModuleFunctionality::with(['moduleData'])->findOrFail($functionId);
        $this->data['allModule']= Module::where('status','=','A')->where('is_deleted','=','N')->get();
        try
        {           

            if ($request->isMethod('POST')) {
                if ($functionId == null) {
                    return redirect()->route('admin.user-management.module.list');
                }
                $validationCondition = array(
                    
                    'function_name'               => 'required|min:2|max:255',
                    'function_description'	       => 'required|min:2',
                    //'slug'                         => 'required|unique:' .(new ModuleFunctionality)->getTable().',slug,'.$encryptString.',id,deleted_at,NULL',
                    
                    
                );
                $validationMessages = array(
                    
                    'function_name.required'             =>  'Please enter function name' , 
                    'function_name.min'                  => 'Function name should be should be at least 2 characters',
                    'function_name.max'                  => 'Function name should not be more than 255 characters', 
					'function_description.required'	     => 'Please enter function description',
                    'function_description.min'           => 'Function description should be should be at least 10 characters',
                    'slug.required'                      =>  'Slug is required',
                    'slug.unique'                        =>  'Slug must be unique',
                    
                   
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return Redirect::Route('admin.module-management.functionality-edit', ['encryptCode' => $encryptString])->withErrors($Validator);
                } else {
                    
                    $details->function_name        = trim($request->function_name, ' ');
                    $details->function_description   = $request->function_description;
                    $details->module_id              = $request->module_id;
                    $details->slug                   =$request->slug;
                    $details->status                 =$request->status;
                    $details->created_by            = Auth::guard('admin')->user()->id;

                    $saveEvent = $details->save();
                    if ($saveEvent) {
                        $request->session()->flash('alert-success', 'Functionality has been updated successfully');
                        return redirect()->route('admin.module-management.functionality.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the Functionality');
                         return redirect()->back();
                    }
                }
            }
        } catch (Exception $e) {
            return Redirect::Route('admin.module-management.functionality-edit', ['encryptCode' => $encryptString])->with('error', $e->getMessage());
        }

            $this->data['details']=$details;
            $this->data['encryptCode'] = $encryptString;

        return view('admin.module.function-edit',$this->data);





    }

    /*****************************************************/
    # ModuleManagementController
    # Function name : moduleEdit
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Module Edit
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function moduleEdit(Request $request, $encryptString) {
        $this->data['page_title']='Module Edit';
        $this->data['panel_title']='Module Edit';
        $userId = decrypt($encryptString, Config::get('constant.enc_key')); // get user-id After Decrypt with salt key.
        $details = Module::findOrFail($userId);
        try
        {


            // $details = Module::find($id);
            // $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($userId == null) {
                    return redirect()->route('admin.user-management.module.list');
                }
                $validationCondition = array(
                    'module_name'                 => 'required|min:2|max:255',
                    'module_description'	        => 'required|min:2',
                    //'slug'                         => 'required|unique:' .(new Module)->getTable().',slug,'.$encryptString.',id,deleted_at,NULL',
                   
                    
                    
                );
                $validationMessages = array(
                    'module_name.required'               => 'Please enter module name',
					'module_name.min'                    => 'Module name should be should be at least 2 characters',
                    'module_name.max'                    => 'Module name should not be more than 255 characters',
                    'module_description.required'	     => 'Please enter Module description',
                    'module_description.min'             => 'Module description should be should be at least 10 characters',
                    'slug.required'                      =>  'Slug is required',
                    'slug.unique'                        =>  'Slug must be unique',
                    
                   
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return Redirect::Route('admin.module-management.edit', ['encryptCode' => $encryptString])->withErrors($Validator);
                } else {
                    
                    $details->module_name        = trim($request->module_name, ' ');
                    $details->module_description     = $request->module_description;
                    $details->slug                   =$request->slug;
                    $details->status                 =$request->status;
                    $details->created_by  = Auth::guard('admin')->user()->id;

                    $saveEvent = $details->save();

                    if ($saveEvent) {
                        $request->session()->flash('alert-success', 'Module has been updated successfully');
                        return redirect()->route('admin.module-management.module.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the Module');
                         return redirect()->back();
                    }
                }
            }
        } catch (Exception $e) {
            return Redirect::Route('admin.module-management.edit', ['encryptCode' => $encryptString])->with('error', $e->getMessage());
        }

            $this->data['details']=$details;
            $this->data['encryptCode'] = $encryptString;

        return view('admin.module.module-edit',$this->data);


    }

    /*****************************************************/
    # ModuleManagementController
    # Function name : moduleDelete
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Module Delete
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/
    public function moduleDelete(Request $request,$encryptString)
    {

        $moduleId = decrypt($encryptString, Config::get('constant.enc_key')); // get user-id After Decrypt with salt key.
        $details = Module::findOrFail($moduleId);


        if ($details) {
            $details->deleted_at=Carbon::now();
            $details->deleted_by=\Auth::guard('admin')->user()->id;
            $details->is_deleted='Y';
            $details->save();
            return redirect()->route('admin.module-management.module.list')->with('success','Module has been deleted successfully!');
        } else {
            $request->session()->flash('alert-danger', 'An error occurred while deleting the Module list');
             return redirect()->back();
        }
    }
    /*****************************************************/
    # ModuleManagementController
    # Function name : functionaDelete
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Function  Delete
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/

    public function functionaDelete(Request $request,$encryptString)
    {

        $functionmoduleId = decrypt($encryptString, Config::get('constant.enc_key')); // get user-id After Decrypt with salt key.
        $details = ModuleFunctionality::findOrFail($functionmoduleId);


        if ($details) {
            $details->deleted_at=Carbon::now();
            $details->deleted_by=\Auth::guard('admin')->user()->id;
            $details->is_deleted='Y';
            $details->save();
            return redirect()->route('admin.module-management.functionality.list')->with('success','Function has been deleted successfully!');
        } else {
            $request->session()->flash('alert-danger', 'An error occurred while deleting the Functionality list');
             return redirect()->back();
        }
    }

    /*****************************************************/
    # ModuleManagementController
    # Function name : moduleAdd
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Module Add
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function moduleAdd(Request $request){
        $this->data['page_title']='Module Create ';
        $this->data['panel_title']='Module Create ';

    try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'module_name'              => 'required|min:2|max:255',
                    'module_description'	   => 'required|min:2',
                    'slug'                     => 'required|unique:'.(new Module)->getTable().',slug,NULL,id,deleted_at,NULL',
				);
				$validationMessages = array(
					'module_name.required'               => 'Please enter event name',
					'module_name.min'                    => 'Module name should be should be at least 2 characters',
					'module_name.max'                    => 'Module name should not be more than 255 characters',
					'module_description.required'	     => 'Please enter Module description',
                    'module_description.min'             => 'Module description should be should be at least 10 characters',
                    'slug.required'                      =>  'Slug is required',
                    'slug.unique'                        =>  'Slug must be unique', 
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.user-management.module.add')->withErrors($Validator)->withInput();
				} else {


                    $new = new Module;
                    $new->module_name = trim($request->module_name, ' ');
                    $new->module_description  = $request->module_description;
                    $new->status            = $request->status;
                    $new->slug = $request->slug;
                    $new->created_by  = Auth::guard('admin')->user()->id;
                    $new->updated_by  = Auth::guard('admin')->user()->id;
                    //dd($new);
                    $save = $new->save();

					if ($save) {
						$request->session()->flash('alert-success', 'Module has been added successfully');
						return redirect()->route('admin.module-management.module.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the Module');
						return redirect()->back();
					}
				}
			}
			return view('admin.module.module-add',$this->data);
		} catch (Exception $e) {
			return redirect()->route('module-management.module.list')->with('error', $e->getMessage());
		}

    }

    /*****************************************************/
    # ModuleManagementController
    # Function name : functionAdd
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Function Add
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function functionAdd(Request $request){
        $this->data['page_title']='Functionality Create ';
        $this->data['panel_title']='Functionality Create ';
        $allModule= Module::where('status','=','A')->where('is_deleted','=','N')->get();

    try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    
                    'function_name'          => 'required|min:2|max:255',
                    'function_description'	 => 'required|min:2',
                    'slug'                      => 'required|unique:'.(new ModuleFunctionality)->getTable().',slug,NULL,id,deleted_at,NULL',
				);
				$validationMessages = array(
					
                    'function_name.required'                =>  'Please enter function name' , 
                    'function_name.min'                     => 'Function name should be should be at least 2 characters',
                    'function_name.max'                     => 'Function name should not be more than 255 characters', 
					'function_description.required'	        => 'Please enter function description',
                    'function_description.min'              => 'Function description should be should be at least 10 characters',
                    'slug.required'                         =>  'Slug is required',
                    'slug.unique'                           =>  'Slug must be unique', 
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.module-management.function.add')->withErrors($Validator)->withInput();
				} else {


                    $new = new ModuleFunctionality;
                    $new->function_name = trim($request->function_name, ' ');
                    $new->function_description  = $request->function_description;
                    $new->module_id = $request->module_id;
                    $new->slug = $request->slug;
                    $new->status=$request->status;
                    $new->created_by  = Auth::guard('admin')->user()->id;
                    $new->updated_by  = Auth::guard('admin')->user()->id;
                    //dd($new);
                    $saveFunction = $new->save();

					if ($saveFunction) {

						$request->session()->flash('alert-success', 'Function has been added successfully');
						return redirect()->route('admin.module-management.functionality.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the Functionality');
						return redirect()->back();
					}
				}
			}
			return view('admin.module.function-add',$this->data, ['allModule'=>$allModule]);
		} catch (Exception $e) {
			return redirect()->route('module-management.functionality.list')->with('error', $e->getMessage());
        }
        
        


    }

    /*****************************************************/
    # ModuleManagementController
    # Function name : resetmoduleStatus
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Reset Module Status
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function resetmoduleStatus(Request $request){
        
            $response['has_error']=1;
            $response['msg']="Something went wrong.Please try again later.";
    
            $userId = decrypt($request->encryptCode, Config::get('constant.enc_key')); // get user-id After Decrypt with salt key.
    
            $userObj = Module::findOrFail($userId);
            $updateStatus = $userObj->status == 'A' ? 'I' : 'A'; 
            $userObj->status=$updateStatus;
            $userObj->updated_at=Carbon::now();
            $userObj->updated_by=Auth::guard('admin')->user()->id;
            $saveResponse=$userObj->save();       
            if($saveResponse){
                $response['has_error']=0;
                $response['msg']="Succressfuuly changed status.";
            }
            return $response;
        }

    /*****************************************************/
    # ModuleManagementController
    # Function name : resetfunctionStatus
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Reset Functionality Status
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/
    
        public function resetfunctionStatus(Request $request){
           
                $response['has_error']=1;
                $response['msg']="Something went wrong.Please try again later.";
        
                $userId = decrypt($request->encryptCode, Config::get('constant.enc_key')); // get user-id After Decrypt with salt key.
        
                $userObj = ModuleFunctionality::findOrFail($userId);
                $updateStatus = $userObj->status == 'A' ? 'I' : 'A'; 
                $userObj->status=$updateStatus;
                $userObj->updated_at=Carbon::now();
                $userObj->updated_by=Auth::guard('admin')->user()->id;
                $saveResponse=$userObj->save();       
                if($saveResponse){
                    $response['has_error']=0;
                    $response['msg']="Succressfuuly changed status.";
                }
                return $response;
            }


}
