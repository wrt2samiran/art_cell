<?php

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View;
use Illuminate\Support\Facades\File as FileSystem;
use App\Models\User;
use  App\Models\PressRelease;
use  App\Models\PrCopywriterContent;
use App\Models\Timezone;
use App\Models\Transaction;
use App\Models\MasterCategory;
use App\Models\OurService;
use Yajra\Datatables\Datatables;
use Config;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public $data = array();             // set global class object
    
    /*****************************************************/
    # DashboardController
    # Function name : changeLanguage
    # Author        :
    # Created Date  : 01-10-2020
    # Purpose       : To change language              
    # Params        : 
    /*****************************************************/
    public function changeLanguage($locale){
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }


    /*****************************************************/
    # DashboardController
    # Function name : dashboardView
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Dashboard View
    #                 
    #                 
    # Params        : 
    /*****************************************************/

    public function dashboardView()
    {
        $this->data['page_title'] = 'Control Panel | Dashboard';
        $this->data['panel_title'] = 'Admin Dashboard';
       
                                              

        return view('admin.dashboard.index', $this->data);
    }

    /*****************************************************/
    # DashboardController
    # Function name : showChangePasswordForm
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Show Change Password Form
    #                 
    #                 
    # Params        : 
    /*****************************************************/

    public function showChangePasswordForm()
    {
        $this->data['page_title'] = 'Control Panel | Change Password';
        $this->data['panel_title'] = 'Change Password';
        

        return view('admin.dashboard.changepassword', $this->data);
    }

    /*****************************************************/
    # DashboardController
    # Function name : changePassword
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Change Password
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/


    public function changePassword(Request $request)
    {

        if (!(Hash::check($request->get('current_password'), Auth::guard('admin')->user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        } else {
            try {

                $validationCondition = [
                    'new_password' => 'required',
                    'confirm_password' => 'required|same:new_password',
                ];

                $validationMessages = array(
                    'new_password.required' => 'New Password is required.',
                    'confirm_password.required' => 'Confirm Password is required.',
                    'confirm_password.same' => 'Confirm Password should be same as new password.',
                );

                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    // If validation error occurs, load the error listing
                    return redirect()->back()->withErrors($Validator);
                } else {
                    $user = User::findOrFail(Auth::guard('admin')->user()->id);
                    $user->password = $request->new_password;
                    $saveResposne = $user->save();
                    if ($saveResposne == true) {
                        return redirect()->back()->with("success", "Password changed successfully !");
                    } else {
                        return redirect()->back()->with("error", "Password changed successfully !");
                    }
                }

            } catch (Exception $e) {
                return Redirect::Route('admin.changePassword')->with('error', $e->getMessage());
            }

        }
    }

    /*****************************************************/
    # DashboardController
    # Function name : settings
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Date format , time zone , Time format
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function settings(Request $request)
    {
        $this->data['page_title'] = "Settings";
        $this->data['panel_title'] = "Settings";
        $this->data['timezones'] = Timezone::select('id', 'tz_name', 'current_utc_offset')
            ->where('status', 'A')
            ->get();
   
        $this->data['settingObj']= $this->getCurrentUserSettingsData();

        //  dd($this->data['settingObj']);


        try {
            if ($request->isMethod('POST')) {
                $validationCondition = array(
                    'timezone' => 'required',
                    'date_format'=>'required',
                    'time_format'=>'required'
                );
                if(\Auth::guard('admin')->user()->usertype == 'S') {
                    $validationCondition['vat_value_for_pr_copywrite'] = 'required';
                    $validationCondition['vat_value_for_press_release'] = 'required';
                    // $validationCondition['return_request'] = 'required';
                    if(!empty($request->return_request)){
                        $validationCondition['limitation_count'] = 'required';
                    }
                }


                $validationMessages = array(
                    'timezone.required' => 'Please Choose Timezone',
                    'date_format.required' => 'Please Choose Data Fotmat',
                    'time_format.required' => 'Please Choose Time Fotmat'

                );
                
                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return Redirect::back()->withErrors($Validator);
                } else {
                    $updateDataObj['timezone']=$request->timezone;
                    $updateDataObj['date_format']=$request->date_format;
                    $updateDataObj['time_format']=$request->time_format;
                  
                    if(\Auth::guard('admin')->user()->usertype == 'S') {
                        $currencyArray=explode('||',$request['currency']);  // Convert to array
                        $updateDataObj['vat_value_for_pr_copywrite']=$request->vat_value_for_pr_copywrite;
                        $updateDataObj['vat_value_for_press_release']=$request->vat_value_for_press_release;
                        $updateDataObj['return_request']=!empty($request->return_request) ? "yes" : "no";
                        $updateDataObj['limitation_count']=   $updateDataObj['return_request'] == 'yes' ? $request->limitation_count: '';
                    }
                    $userModelObj= User::findOrFail(Auth::guard('admin')->user()->id);
                    $userModelObj->setting_json=json_encode($updateDataObj,true);
                    $saveEvent= $userModelObj->save();
                   
                    if ($saveEvent) {
                        return redirect()->route('admin.settings')->with('success', 'Settings has been updated successfully');
                    } else {
                        return redirect()->back()->with('error', 'An error occurred while updating settings');
                    }
                }
            }
        } catch (Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }
   
        return view('admin.dashboard.settings', $this->data);
    }


    public function categoryList(Request $request){
        $this->data['page_title']="Category List";
        $this->data['panel_title']="Category List";
        
        return view('admin.category.list',$this->data);
    }

    public function categoryListTable(Request $request){

        $data = MasterCategory::where('status','=','A')->where('is_deleted','=','N')->orderBy('id','desc')->get();
        
        $finalResponse= Datatables::of($data)
           
            ->addColumn('status', function ($model) {
                $statuslink= route('admin.reset-category-status',  encrypt($model->id, Config::get('Constant.ENC_KEY')));;
                if(checkFunctionPermission("reset-category-status")){
                    if($model->status == 'A'){
                        $statusHtml= '<button type="button" class="btn btn-block btn-success btn-xs changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'">Active</button>';
                    } else{
                        $statusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'">Inactive</button>';
                    }
                }else{
                    if($model->status == 'A'){
                        $fstatusHtml= '<button type="button" class="btn btn-block btn-success btn-xs">Active</button>';
                   
                    } else{
                            $fstatusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs">Inactive</button>';
                        }

                }   
         
                return  $statusHtml;
            })
           ->addColumn('action', function ($model) {
                $editlink = route('admin.edit',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $deletelink= route('admin.delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                //$viewlink= route('admin.cms-management.view',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                $actions='<div class="btn-group btn-group-sm ">';
                if(checkFunctionPermission("edit")){
                    $actions .='<a href="' . $editlink . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>';
                }
                    //$actions .='<a href="' . $viewlink . '" class="btn btn-info" id=""><i class="fas fa-eye"></i></a>';
                    
                              
                
                    if(checkFunctionPermission("delete")){
               
                    $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>';
                    }
                
                $actions .='</div>';
                return $actions;

            })
            ->rawColumns(['action','status'])
            ->make(true);
            
            return $finalResponse;

    }


    public function resetcategoryStatus(Request $request){
    
        $response['has_error']=1;
        $response['msg']="Something went wrong.Please try again later.";

        $categoryId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.

        $categoryObj = MasterCategory::findOrFail($categoryId);
        $updateStatus = $categoryObj->status == 'A' ? 'I' : 'A'; 
        $categoryObj->status=$updateStatus;
        $categoryObj->updated_at=Carbon::now();
        $categoryObj->updated_by=Auth::guard('admin')->user()->id;
        $saveResponse=$categoryObj->save();       
        if($saveResponse){
            $response['has_error']=0;
            $response['msg']="Succressfuuly changed status.";
        }
        return $response;
    }

    public function categoryDelete(Request $request,$encryptString)
    {
       
        $categoryId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = MasterCategory::findOrFail($categoryId);

        
        if ($details) {
            $details->deleted_at=Carbon::now();
            $details->deleted_by=\Auth::guard('admin')->user()->id;
            $details->is_deleted='Y';
            $details->save();
            return redirect()->route('admin.category.list')->with('success','Category has been deleted successfully!');
        } else {
            $request->session()->flash('alert-danger', 'An error occurred while deleting the User');
             return redirect()->back();
        }
    }


    public function categoryAdd(Request $request)
    {
        $this->data['page_title']='Category Create ';
        $this->data['panel_title']='Category Create ';
        


    try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    
                    'name'               => 'required',
                    'status'            => 'required',
                    'slug'              => 'required'
                    
				);
				$validationMessages = array(
					
                    'name.required'                 =>  'Please enter Name' , 
                  
                    'status.required'               => 'Status is required ',
                    'slug.required'                  => 'Slug is required',
                   
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.category-add')->withErrors($Validator)->withInput();
				} else {

                   
                    //dd($newSlug);
                    $new = new MasterCategory;
                    $new->name = trim($request->name, ' ');
                    $new->slug=$request->slug;
                    
                    $new->status = $request->status;
               
                    $new->created_at = Carbon::now();
                    $new->updated_at = Carbon::now();
                    $new->created_by  = Auth::guard('admin')->user()->id;
                    $new->updated_by  = Auth::guard('admin')->user()->id;
                    $saveCategory = $new->save();

					if ($saveCategory) {
                
						$request->session()->flash('alert-success', 'Category has been added successfully');
						return redirect()->route('admin.category.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the Category');
						return redirect()->back();
					}
				}
			}
			return view('admin.category.add',$this->data);
		} catch (Exception $e) {
			return redirect()->route('category.list')->with('error', $e->getMessage());
		}

    
    }

    public function categoryEdit(Request $request, $encryptString){
        $this->data['page_title']='Category Edit';
        $this->data['panel_title']='Category Edit';
        $categoryId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = MasterCategory::findOrFail($categoryId);
      // dd($details);
        try
        {
            if ($request->isMethod('POST')) {
                if ($categoryId == null) {
                    return redirect()->route('admin.category.list');
                }
                $validationCondition = array(
                    'name'               => 'required',
                    'status'             => 'required',
                    'slug'               => 'required',
                );
            
                $validationMessages = array(
                    'name.required'                 =>  'Please enter Name' , 
                    'slug.required'                  => 'Slug is required',
					'status.required'               => 'Status is required ',
                   
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return Redirect::Route('admin.edit', ['encryptCode' => $encryptString])->withErrors($Validator);
                } else {
                    
                    $details->name        = trim($request->name, ' ');
                    $details->slug         = $request->slug;
                    $details->status   = $request->status;
                    $details->updated_by  = Auth::guard('admin')->user()->id;
                    $details->updated_at = Carbon::now();
                   
                    
                    $saveEvent = $details->save();
                    if ($saveEvent) {
                        
                            return redirect()->route('admin.category.list')
                                        ->with('success','Category has been updated successfully');
                       
                    } else {  
                         return redirect()->back()
                                        ->with('error','An error occurred while updating the Category.');
                    }
                }
            }
        } catch (Exception $e) {
            return Redirect::back()
                           ->with('error', $e->getMessage());
        }

        $this->data['details']=$details;
        $this->data['encryptCode'] = $encryptString;
        return view('admin.category.edit',$this->data);
    }

    public function ourservicesList(Request $request){
        $this->data['page_title']="Our Services List";
        $this->data['panel_title']="Our Services List";
        
        return view('admin.ourservices.list',$this->data);
    }

    public function ourservicesListTable(Request $request){

        $data = OurService::where('status','=','A')->where('is_deleted','=','N')->orderBy('id','desc')->get();
        
        $finalResponse= Datatables::of($data)
           
            ->addColumn('status', function ($model) {
                $statuslink= route('admin.reset-our-services-status',  encrypt($model->id, Config::get('Constant.ENC_KEY')));;
                if(checkFunctionPermission("reset-our-services-status")){
                    if($model->status == 'A'){
                        $statusHtml= '<button type="button" class="btn btn-block btn-success btn-xs changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'">Active</button>';
                    } else{
                        $statusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'">Inactive</button>';
                    }
                }else{
                    if($model->status == 'A'){
                        $fstatusHtml= '<button type="button" class="btn btn-block btn-success btn-xs">Active</button>';
                   
                    } else{
                            $fstatusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs">Inactive</button>';
                        }

                }   
         
                return  $statusHtml;
            })
            ->addColumn('logo', function ($model) {
                $aaddonLogo= '<img src=" '.asset('assets/ourservices/'.$model->logo.'').' " alt="" height="50" width="50">';
                return  $aaddonLogo;
            })
           ->addColumn('action', function ($model) {
                $editlink = route('admin.our-services-edit',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $deletelink= route('admin.our-services-delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                //$viewlink= route('admin.cms-management.view',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                 $actions='<div class="btn-group btn-group-sm ">';
                 if(checkFunctionPermission("our-services-edit")){
                     $actions .='<a href="' . $editlink . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>';
                 }
                     //$actions .='<a href="' . $viewlink . '" class="btn btn-info" id=""><i class="fas fa-eye"></i></a>';
                    
                              
                
                     if(checkFunctionPermission("our-services-delete")){
               
                     $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>';
                    }
                
                 $actions .='</div>';
                 return $actions;

            })
            ->rawColumns(['action','status','logo'])
            ->make(true);
            
            return $finalResponse;

    }
    public function resetourservicesStatus(Request $request){
    
        $response['has_error']=1;
        $response['msg']="Something went wrong.Please try again later.";

        $ourserviceId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.

        $servicesObj = OurService::findOrFail($ourserviceId);
        $updateStatus = $servicesObj->status == 'A' ? 'I' : 'A'; 
        $servicesObj->status=$updateStatus;
        $servicesObj->updated_at=Carbon::now();
        $servicesObj->updated_by=Auth::guard('admin')->user()->id;
        $saveResponse=$servicesObj->save();       
        if($saveResponse){
            $response['has_error']=0;
            $response['msg']="Succressfuuly changed status.";
        }
        return $response;
    }
    public function ourservicesDelete(Request $request,$encryptString)
    {
       
        $ourserviceId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = OurService::findOrFail($ourserviceId);

        
        if ($details) {
            $details->deleted_at=Carbon::now();
            $details->deleted_by=\Auth::guard('admin')->user()->id;
            $details->is_deleted='Y';
            $details->save();
            return redirect()->route('admin.our.services.list')->with('success','Our Services has been deleted successfully!');
        } else {
            $request->session()->flash('alert-danger', 'An error occurred while deleting the User');
             return redirect()->back();
        }
    }
    public function ourservicesAdd(Request $request)
    {
        $this->data['page_title']='Our Service Create ';
        $this->data['panel_title']='Our Service Create ';
        


    try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    
                    'title'               => 'required',
                    'description'         => 'required',
                    //'logo'                => 'reqiured',
                    'status'              => 'required',
                    
				);
				$validationMessages = array(
					
                    'title.required'                 =>  'Please enter Title' , 
                    'description.required'          =>  'Please write Description' ,
                    //'logo.required'                 =>  'Please write Logo' ,
					'status.required'               => 'Status is required ',
                   
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.our.services.list')->withErrors($Validator)->withInput();
				} else {

                   
                    
                    $new = new OurService;
                   
                    $new->title = trim($request->title, ' ');
                    $new->description = $request->description;
                    

                    $file_name =time();
                    $file = $request->file('logo'); 
                    //dd($file)  ; 
                    $extension = $file->getClientOriginalExtension(); 
                    //dd($extension);
                    $fullFileName = $file_name.'.'.$extension;
                   
                    $destinationPath = 'assets/ourservices';
                    $uploadResponse = $file->move($destinationPath,$fullFileName); 
              //   dd($fullFileName,$uploadResponse);
        
                    $new->logo =$fullFileName;
                    
                    $new->status = $request->status;
               
                    $new->created_at = Carbon::now();
                    $new->updated_at = Carbon::now();
                    $new->created_by  = Auth::guard('admin')->user()->id;
                    $new->updated_by  = Auth::guard('admin')->user()->id;
                    $saveCategory = $new->save();

					if ($saveCategory) {
                
						$request->session()->flash('alert-success', 'Our Services has been added successfully');
						return redirect()->route('admin.our.services.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the Category');
						return redirect()->back();
					}
				}
			}
			return view('admin.ourservices.add',$this->data);
		} catch (Exception $e) {
			return redirect()->route('admin.our.services.list')->with('error', $e->getMessage());
		}

    
    }

    public function ourservicesEdit(Request $request, $encryptString){
        $this->data['page_title']='Our Services Edit';
        $this->data['panel_title']='Our Services Edit';
        $ourserviceId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = OurService::findOrFail($ourserviceId);
      // dd($details);
        try
        {
            if ($request->isMethod('POST')) {
                if ($ourserviceId == null) {
                    return redirect()->route('admin.our.services.list');
                }
                $validationCondition = array(
                    'title'               => 'required',
                    'description'         => 'required',
                    'status'              => 'required',
                    
                    
                );
            
                $validationMessages = array(
                    'title.required'                 =>  'Please enter Title' , 
                    'description.required'          =>  'Please write Description' ,
					'status.required'               => 'Status is required ',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return Redirect::Route('admin.our-services-edit', ['encryptCode' => $encryptString])->withErrors($Validator);
                } else {
                    
                    $details->title        = trim($request->title, ' ');
                    $details->description = $request->description;
                    $file_name = time();    
                    $file = $request->file('logo');    
                    if(!empty($file)) {
                        $extension = $file->getClientOriginalExtension();  
                        $fullFileName = $file_name.'.'.$extension; 
                        $destinationPath = 'assets/ourservices';
                        $uploadResponse = $file->move($destinationPath,$fullFileName);
                        $details->logo=$fullFileName;
                        }  

                    $details->status   = $request->status;
                    $details->updated_by  = Auth::guard('admin')->user()->id;
                    $details->updated_at = Carbon::now();
                   
                    
                    $saveEvent = $details->save();
                    if ($saveEvent) {
                        
                            return redirect()->route('admin.our.services.list')
                                        ->with('success','Our Services has been updated successfully');
                       
                    } else {  
                         return redirect()->back()
                                        ->with('error','An error occurred while updating the Our service.');
                    }
                }
            }
        } catch (Exception $e) {
            return Redirect::back()
                           ->with('error', $e->getMessage());
        }

        $this->data['details']=$details;
        $this->data['encryptCode'] = $encryptString;
        return view('admin.ourservices.edit',$this->data);
    }




}
