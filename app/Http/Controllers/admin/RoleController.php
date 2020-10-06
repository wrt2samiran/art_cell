<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
class RoleController extends Controller
{
    private $view_path='admin.roles';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Role List';
        if($request->ajax()){
            $roles=Role::orderBy('parrent_id','ASC')->orderBy('id','ASC');
            return Datatables::of($roles)
            ->editColumn('created_at', function ($role) {
                return $role->created_at ? with(new Carbon($role->created_at))->format('m/d/Y') : '';
            })
            ->editColumn('role_description', function ($role) {
                return Str::limit($role->role_description,100);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($role){
                if($role->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the gallery image" href="javascript:change_status('."'".route('admin.roles.change_status',$role->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the gallery image" href="javascript:change_status('."'".route('admin.roles.change_status',$role->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($role){
                $delete_url=route('admin.roles.delete',$role->id);
                $details_url=route('admin.roles.show',$role->id);
                $edit_url=route('admin.roles.show',$role->id);

                return '<a title="View Role Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Role" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete gallery image" href="javascript:delete_role('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

    public function create(){
        $this->data['page_title']='Role List';
        $parent_roles=Role::whereStatus('A')->orderBy('id','ASC')->get();
        $this->data['parent_roles']=$parent_roles;
        return view($this->view_path.'.create',$this->data);
    }

    public function store(Request $request){
        return redirect()->back()->with('success','Success message');

    }
    public function show($id){
        return view($this->view_path.'.show');
    }
    public function edit($id){

    }
    public function update(Request $request,$id){

    }
    public function delete($id){

    }

    public function change_status($id){
        $role=Role::findOrFail($id);
        $change_status_to=($role->status=='A')?'I':'A';
        $message=($role->status=='A')?'deactivated':'activated';

         //updating gallery status
        $role->update([
            'status'=>$change_status_to
        ]);
         
        //returning json success response
        return response()->json(['message'=>'Role successfully '.$message.'.']);
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
        // $this->middleware('check.permission');
    }

    /*****************************************************/
    # RoleController
    # Function name : roleList
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Role Listing
    #                 
    #                 
    # Params        : 
    /*****************************************************/

    public function roleList()
    {
        $this->data['page_title']='Role List';
        $this->data['panel_title']='Role List';
        $allRole= Role::whereNotIn('id',[1] )->get();
        $this->data['viewRole']=$allRole;
        $settingObj=$this->getCurrentUserSettingsData();
        $this->data['settingObj']=$settingObj;
        return view('admin.usermanagement.role-list',$this->data);

    }

    /*****************************************************/
    # RoleController
    # Function name : roleAdd
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Role Add
    #                 
    #                 
    # Params        :Request $request
    /*****************************************************/

    public function roleAdd(Request $request){
        $this->data['page_title']='Role Create ';
        $this->data['panel_title']='Role Create ';

    try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'role_name'                => 'required|min:2|max:255',
                    'role_description'	            => 'required|min:2'
				);
				$validationMessages = array(
					'role_name.required'               => 'Please enter event name',
					'role_name.min'                    => 'Role name should be should be at least 2 characters',
					'role_name.max'                    => 'Role name should not be more than 255 characters',
					'role_description.required'	       => 'Please enter Role description',
                    'role_description.min'             => 'Role description should be should be at least 10 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.user-management.role-add')->withErrors($Validator)->withInput();
				} else {

                    $new = new Role;
                    $new->role_name = trim($request->role_name, ' ');
                    $new->role_description  = $request->role_description;
                    $new->status            = $request->status;
                    $new->created_by  = Auth::guard('admin')->user()->id;
                    $new->updated_by  = Auth::guard('admin')->user()->id;
                    $save = $new->save();


                    $moduleFunctionalities = ModuleFunctionality::select('id', 'module_id')->where('is_deleted', 'N')->get();

                    foreach ($moduleFunctionalities as $moduleFunctionality) {
                        $createRolePermission = new RolePermission();
                        $createRolePermission->module_id = $moduleFunctionality->module_id;
                        $createRolePermission->module_functionality_id = $moduleFunctionality->id;
                        $createRolePermission->role_id = $new->id;
                        $createRolePermission->status = 'I';
                        $createRolePermission->created_by = Auth::guard('admin')->user()->id;
                        $save = $createRolePermission->save();
                    }

					if ($save) {
						$request->session()->flash('alert-success', 'Role has been added successfully');
						return redirect()->route('admin.user-management.role-list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the role');
						return redirect()->back();
					}
				}
			}
			return view('admin.usermanagement.role-add',$this->data);
		} catch (Exception $e) {
			return redirect()->route('user-management.role-list')->with('error', $e->getMessage());
		}

    }

    /*****************************************************/
    # RoleController
    # Function name : editRole
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Role Add
    #                 
    #                 
    # Params        :Request $request $id
    /*****************************************************/


    public function editRole(Request $request, $id) {
        $this->data['page_title']='Role Edit';
        $this->data['panel_title']='Role Edit';

        try
        {


            $details = Role::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('user-management.role-list');
                }
                $validationCondition = array(
                    'role_name'                => 'required|min:2|max:255',
                    'role_description'	        => 'required|min:2',

                );
                $validationMessages = array(
                    'role_name.required'               => 'Please enter event name',
					'role_name.min'                    => 'Role name should be should be at least 2 characters',
					'role_name.max'                    => 'Role name should not be more than 255 characters',
					'role_description.required'	        => 'Please enter Role description',
                    'role_description.min'              => 'Role description should be should be at least 10 characters',

                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {


                    $details->role_name        = trim($request->role_name, ' ');
                    $details->role_description     = $request->role_description;
                    $details->status                = $request->status;
                    $details->created_by  = Auth::guard('admin')->user()->id;
                    $details->updated_by  = Auth::guard('admin')->user()->id;
                    $saveEvent = $details->save();

                    if ($saveEvent) {
                        $request->session()->flash('alert-success', 'Role has been updated successfully');
                        return redirect()->route('admin.user-management.role-list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the Role');
                         return redirect()->back();
                    }
                }
            }
            $this->data['details']=$details;
            $this->data['data']=$data;

        return view('admin.usermanagement.role-edit',$this->data);

        } catch (Exception $e) {
            return redirect()->route('admin.user-management.role-list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # RoleController
    # Function name : roleDelete
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Role Delete
    #                 
    #                 
    # Params        :Request $request $id
    /*****************************************************/

    public function roleDelete(Request $request, $id){

        try
        {
            $roleDelete = new Role;

            if($id){
                $roleDelete = $roleDelete->where('id', $id)->first();

                if($roleDelete) {
                    if($roleDelete->deleted_at == Null){
                        $changeStatusTo = now();  //if status is 0 then change to  1
                        $massage        = 'Soft delete update Successfully';
                    }

                    $roleDelete->deleted_at = $changeStatusTo;
                    $deleteStat = $roleDelete->save();

                    if($deleteStat){
                        return Redirect::back()->with('alert-success', $massage);
                    } else {
                        return Redirect::back()->with('alert-danger', 'Sorry!! Something want wrong not update');
                    }
                } else {
                    return Redirect::back()->with('alert-danger', trans('Sorry!! No record found'));
                }

            } else {
                return Redirect::back()->with('alert-danger', 'Sorry!! Id is not found');
            }

        }catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    /*****************************************************/
    # RoleController
    # Function name : rolePermission
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Role Permission
    #                 
    #                 
    # Params        :Request $request $encryptCode
    /*****************************************************/

    public function rolePermission(Request $request,$encryptCode){
        $this->data['page_title'] = 'Control Panel: Role Permission'; // set page title
        $this->data['panel_title'] = 'Control Panel: Role Permission'; // set panel title
        $roleId=decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
        $this->data['PermissionsData'] = RolePermission::select('id', 'role_id', 'module_id', 'module_functionality_id', 'status', 'is_deleted')
            ->with(['module', 'functionality'])
            ->where(['role_id' => $roleId, 'is_deleted' => 'N'])
            ->get();
        $this->data['encryptId'] = $encryptCode;

        try // Try block of try-catch exception starts
        {
            if ($request->isMethod('post')) {
                $input=$request->all();
                if(!empty($input['permission'])){
                    foreach($this->data['PermissionsData'] as $val){
                        if(!in_array($val->id,$input['permission'])){
                            $rolePermission = RolePermission::findOrFail($val->id);
                            $rolePermission->status= 'I';
                            $rolePermission->updated_by=Auth::guard('admin')->user()->id;
                            $rolePermission->save();
                        } else{
                            $rolePermission = RolePermission::findOrFail($val->id);
                            $rolePermission->status= 'A';
                            $rolePermission->updated_by=Auth::guard('admin')->user()->id;
                            $rolePermission->save();
                        }
                    }
                } else {
                    foreach($this->data['PermissionsData'] as $val){
                        $rolePermission = RolePermission::findOrFail($val->id);
                        $rolePermission->status= 'I';
                        $rolePermission->updated_by=Auth::guard('admin')->user()->id;
                        $rolePermission->save();
                    }
                }
                return Redirect::Route('admin.user-management.role-list')->with('success', 'Role Permission has been changed successfully.');
            }
        } catch (Exception $e) // catch block of the try-catch exception
        {
            return Redirect::Route('user-management.role.permission', [$roleId])
                ->with('error', $e->getMessage()); //redirect with exception messages
        }
        return view('admin.usermanagement.role-permission',$this->data);
    }

    /*****************************************************/
    # RoleController
    # Function name : resetRoleStatus
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Change Role Status
    #                 
    #                 
    # Params        :Request $request $encryptCode
    /*****************************************************/

    public function resetRoleStatus(Request $request){
        
            $response['has_error']=1;
            $response['msg']="Something went wrong.Please try again later.";
    
            $userId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
    
            $userObj = Role::findOrFail($userId);
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


