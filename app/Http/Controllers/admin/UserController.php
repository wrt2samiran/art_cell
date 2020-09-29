<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ContactUs;
use App\Models\Subscriber;
use App\Models\SubscriberCategory;
use App\Models\SubscriberType;
use Helper, AdminHelper, Image, Validator, View;
use Illuminate\Support\Facades\Hash;
use Auth;
use Redirect;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Config;



class UserController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        // $this->middleware('check.permission');
    }
    /*****************************************************/
    # UserController
    # Function name : index
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Check whether a user is logged in and
    #                 then redirect the user to either Login
    #                 Panel or Dashboard
    # Params        : Request $request
    /*****************************************************/
    public function index()
    {
        return view('admin.login.admin_login');
    }

    public $data= array();

    /*****************************************************/
    # UserController
    # Function name : userList
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display Admin User listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function userList(Request $request){
        $this->data['page_title']="User List";
        $this->data['panel_title']="User List";
        
        return view('admin.usermanagement.user-list',$this->data);
    }

    /*****************************************************/
    # UserController
    # Function name : userListTable
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display Admin User listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/
    public function userListTable(Request $request){
        $data = User::where('id','!=','1')->where('is_deleted','=','N')->where('usertype','!=','FU')->with(['roleData'])->get();
        $settingObj=$this->getCurrentUserSettingsData();
        $finalResponse= Datatables::of($data)
            ->addColumn('updated', function ($model) use ($settingObj) {
                $date = \Carbon::createFromFormat('Y-m-d H:i:s', $model->created_at, 'UTC');
                $formattedDate=$date->setTimezone($settingObj->timezone)->format($settingObj->date_format.' '.$settingObj->time_format);
                return $formattedDate;
            })
            ->addColumn('status', function ($model) {
                $statuslink= route('admin.user-management.reset-user-status',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                if(checkFunctionPermission("user-management.reset-user-status")){
                    if($model->status == 'A'){
                        $statusHtml= '<button type="button" class="btn btn-block btn-success btn-xs changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'">Active</button>';
                    } else{
                        $statusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'">Inactive</button>';
                    }
                }else{
                    if($model->status == 'A'){
                        $statusHtml= '<button type="button" class="btn btn-block btn-success btn-xs">Active</button>';
                    } else{
                        $statusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs">Inactive</button>';
                    }

                }
                return  $statusHtml;
            })
           ->addColumn('action', function ($model) {
                $editlink = route('admin.user-management.user-edit',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $deletelink= route('admin.user-management.user-delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $changepassword= route('admin.user-management.user-changepassword',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                $actions='<div class="btn-group btn-group-sm ">';
                if(checkFunctionPermission("user-management.user-edit")){
                    $actions .='<a href="' . $editlink . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>';
                }
                if(checkFunctionPermission("user-management.user-delete")){
                    $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>';
                }
                $actions .='<a href="' . $changepassword . '" class="btn btn-info" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;

                // return '<div class="btn-group btn-group-sm ">
                //         <a href="' . $editlink . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>
                //         <a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>
                //       </div>';
            })
            ->rawColumns(['updated','action','status'])
            ->make(true);
            
            return $finalResponse;

    }
    /*****************************************************/
    # UserController
    # Function name : SiteuserList
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display Site User listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function SiteuserList(Request $request){
        $this->data['page_title']="Site User List";
        $this->data['panel_title']="Site User List";
        
        return view('admin.usermanagement.site-user-list',$this->data);
    }

    /*****************************************************/
    # UserController
    # Function name : SiteuserListTable
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display Site User listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function SiteuserListTable(Request $request){
        $data = User::where('id','!=','1')->where('is_deleted','=','N')->where('usertype','=','FU')->get();
        $settingObj=$this->getCurrentUserSettingsData();
        $finalResponse= Datatables::of($data)
            ->addColumn('updated', function ($model) use ($settingObj) {
                // dd($settingObj);
                $date = \Carbon::createFromFormat('Y-m-d H:i:s', $model->created_at, 'UTC');
                $formattedDate=$date->setTimezone($settingObj->timezone)->format($settingObj->date_format.' '.$settingObj->time_format);
                return $formattedDate;
            })
            ->addColumn('status', function ($model) {
                $statuslink= route('admin.user-management.reset-user-status',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                if(checkFunctionPermission("user-management.reset-user-status")){
                    if($model->status == 'A'){
                        $statusHtml= '<button type="button" class="btn btn-block btn-success btn-xs changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'">Active</button>';
                    } else{
                        $statusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'">Inactive</button>';
                    }
                }else{
                    if($model->status == 'A'){
                        $statusHtml= '<button type="button" class="btn btn-block btn-success btn-xs">Active</button>';
                    } else{
                        $statusHtml= '<button type="button" class="btn btn-block btn-warning btn-xs">Inactive</button>';
                    }

                }
                return  $statusHtml;
            })
           ->addColumn('action', function ($model) {
                $editlink = route('admin.user-management.user-edit',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $deletelink= route('admin.user-management.user-delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $changepassword= route('admin.user-management.user-changepassword',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $actions='<div class="btn-group btn-group-sm ">';
                if(checkFunctionPermission("user-management.user-edit")){
                    $actions .='<a href="' . $editlink . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>';
                }
                if(checkFunctionPermission("user-management.user-delete")){
                    $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>';
                }
                $actions .='<a href="' . $changepassword . '" class="btn btn-info" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;

                // return '<div class="btn-group btn-group-sm ">
                //         <a href="' . $editlink . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>
                //         <a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>
                //       </div>';
            })
            ->rawColumns(['updated','action','status'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }



    /*****************************************************/
    # UserController
    # Function name : userAdd
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : User add
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function userAdd(Request $request)
    {
        $this->data['page_title']='User Create ';
        $this->data['panel_title']='User Create ';
        $allRole= Role::where('status','=','A')->where('is_deleted','=','N')->where('id','!=','1')->get();


    try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    
                    'name'               => 'required|min:2|max:255',
                    'email'                 => 'required|email',
                    'phone'                 => 'required',
                    'password'              => 'required',
                    'confirm_password' => 'required|same:password',
                    'usertype'            => 'required'
				);
				$validationMessages = array(
					
                    'name.required'                 =>  'Please enter Name' , 
                    'name.min'                      => ' Name should be should be at least 2 characters',
                    'name.max'                      => ' Name should not be more than 255 characters', 
					'email.required'                => 'Please provide email id',
                    'email.email'                   => 'Please provide a valid email id',
                    'phone.required'                => 'Please provide Phone Number',
                   
                    'password.required'              => 'Password is required.',
                    'confirm_password.required'       => 'Confirm Password is required.',
                    'confirm_password.same'             => 'Confirm Password should be same as password.',
                    'usertype.reqiuired'              =>"Please provide user type."
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.user-management.user.add')->withErrors($Validator)->withInput();
				} else {

                    $userType= ($request->usertype == 'app') ? 'FU' : 'SA';

                    $new = new User;
                    $new->name = trim($request->name, ' ');
                    $new->email  = $request->email;
                    $new->phone  = $request->phone;
                    $new->password  = $request->confirm_password;
                    $new->role_id = $request->role_id;
                    $new->status = $request->status;
                    $new->setting_json= '{"timezone":"Asia/Kolkata","date_format":"Y-m-d"}';
                    $new->usertype= $userType;
                    $new->created_by  = Auth::guard('admin')->user()->id;
                    $new->updated_by  = Auth::guard('admin')->user()->id;
                    $saveFunction = $new->save();

					if ($saveFunction) {
                        if($userType == 'SA')
                            return redirect()->route('admin.user-management.user.list')->with('success','User has been added successfully.');
                        return redirect()->route('admin.user-management.site.user.list')->with('success','User has been added successfully.');
					} else {
						return redirect()->back()->with('error','An error occurred while adding the User.');
					}
				}
			}
			return view('admin.usermanagement.user-add',$this->data, ['allRole'=>$allRole]);
		} catch (Exception $e) {
			return redirect()->route('user-management.user.list')->with('error', $e->getMessage());
		}

    
    }

    /*****************************************************/
    # UserController
    # Function name : userEdit
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : User edit
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/

    public function userEdit(Request $request, $encryptString){
        $this->data['page_title']='User Edit';
        $this->data['panel_title']='User Edit';
        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = User::with(['roleData'])->findOrFail($userId);
        $this->data['allRole']= Role::where('status','=','A')->where('is_deleted','=','N')->where('id','!=','1')->get();
        try
        {
            if ($request->isMethod('POST')) {
                if ($userId == null) {
                    return redirect()->route('admin.user-management.user.list');
                }
                $validationCondition = array(
                    'name'               => 'required|min:2|max:255',
                    'email'                 => 'required|email',
                    'phone'                 => 'required', 
                );
                if(!empty($request->password)){
                    $validationCondition['confirm_password']='required|same:password';
                }
                if($details->usertype != 'FU'){
                    $validationCondition['role_id']='required';
                }
                if($details->usertype == 'FU'){
                   
                    $validationCondition['website'] = 'required';
                    $validationCondition['facebook_url'] = 'required';
                    $validationCondition['twitter_url'] = 'required';
                    $validationCondition['linkedin_url'] = 'required';
                    $validationCondition['additional_info'] = 'required';
                }
                $validationMessages = array(
                    'name.required'                 =>  'Please enter Name' , 
                    'name.min'                      => ' Name should be should be at least 2 characters',
                    'name.max'                      => ' Name should not be more than 255 characters', 
					'email.required'                => 'Please provide email id',
                    'email.email'                   => 'Please provide a valid email id',
                    'phone.required'                => 'Please provide Phone Number',
                    'role_id.required'              => 'Please Choose Role.',
                    'confirm_password.required'      => "Confirm Password value should be same as password.",
                    'confirm_password.same'         =>  "Confirm Password value should be same as password.",
                    'website.required'              =>"Website Link is required.",
                    'facebook_url.required'         =>"Facebook Link is required.",
                    'twitter_url.required'          =>"Twitter Link is required.",
                    'linkedin_url.required'         =>"Linkdin Link is required.",
                    'additional_info.required'      =>"Additional information is required.",
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return Redirect::Route('admin.user-management.user-edit', ['encryptCode' => $encryptString])->withErrors($Validator);
                } else {
                    
                    
                    $details->name        = trim($request->name, ' ');
                    $details->email   = $request->email;
                    $details->phone   = $request->phone;
                    $details->status   = $request->status;
                    $details->created_by  = Auth::guard('admin')->user()->id;
                    if($details->usertype =='FU')
                        $contentArray['website']=$request['website'];
                        $contentArray['facebook_url']=$request['facebook_url'];
                        $contentArray['twitter_url']=$request['twitter_url'];
                        $contentArray['linkedin_url']=$request['linkedin_url'];
                        $contentArray['additional_info']=$request['additional_info'];
                        $settingJson=json_encode($contentArray);
                        $details->setting_json      =$settingJson;
                    if($details->usertype !='FU')
                        $details->role_id = $request->role_id;
                    if(!empty($request->password))
                        $details->password   = $request->password;  // when password has value.
                        //dd($details);
                    $saveEvent = $details->save();
                    if ($saveEvent) {
                        if($details->usertype !='FU')
                            return redirect()->route('admin.user-management.user.list')
                                        ->with('success','User has been updated successfully');
                        else
                            return redirect()->route('admin.user-management.site.user.list')
                                             ->with('success','User has been updated successfully');

                    } else {  
                         return redirect()->back()
                                        ->with('error','An error occurred while updating the User.');
                    }
                }
            }
        } catch (Exception $e) {
            return Redirect::back()
                           ->with('error', $e->getMessage());
        }

        $this->data['details']=$details;
        $this->data['encryptCode'] = $encryptString;
        return view('admin.usermanagement.user-edit',$this->data);
    }

    public function userChangePassword(Request $request, $encryptString){


        
        $this->data['page_title']='User Change Password';
        $this->data['panel_title']='User Change Password';
        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = User::findOrFail($userId);
        
       
       
            if ($request->isMethod('POST')) {
                if ($userId == null) {
                    return redirect()->route('admin.user-management.user.list');
                }
               
                if(!empty($request->password)){
                    $validationCondition['confirm_password']='required|same:password';
                }
               
               
                $validationMessages = array(
                   
                    'confirm_password.required'      => "Confirm Password value should be same as password.",
                    'confirm_password.same'         =>  "Confirm Password value should be same as password.",
                    
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return Redirect::Route('admin.user-management.user-changepassword', ['encryptCode' => $encryptString])->withErrors($Validator);
                } else {
                    
                  
                
                    
                    if(!empty($request->password))
                        $details->password   = $request->password;  // when password has value.
                        //dd($details);
                    $saveEvent = $details->save();
                    if ($saveEvent) {
                        if($details->usertype !='FU')
                            return redirect()->route('admin.user-management.user.list')
                                        ->with('success','User password has been changed successfully');
                        else
                            return redirect()->route('admin.user-management.site.user.list')
                                             ->with('success','User has been changed successfully');

                    } else {  
                         return redirect()->back()
                                        ->with('error','An error occurred while updating the User.');
                    }
                }
            }
      

        $this->data['details']=$details;
        $this->data['encryptCode'] = $encryptString;
        return view('admin.usermanagement.password-change',$this->data);
    }


    /*****************************************************/
    # UserController
    # Function name : userDelete
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : User Delete
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/


    public function userDelete(Request $request,$encryptString)
    {
       
        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = User::findOrFail($userId);

        
        if ($details) {
            $details->deleted_at=Carbon::now();
            $details->deleted_by=\Auth::guard('admin')->user()->id;
            $details->is_deleted='Y';
            $details->save();
            return redirect()->route('admin.user-management.user.list')->with('success','User has been deleted successfully!');
        } else {
            $request->session()->flash('alert-danger', 'An error occurred while deleting the User');
             return redirect()->back();
        }
    }

    /*****************************************************/
    # UserController
    # Function name : resetuserStatus
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : User Status Change
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/

    public function resetuserStatus(Request $request){
    
        $response['has_error']=1;
        $response['msg']="Something went wrong.Please try again later.";

        $userId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.

        $userObj = User::findOrFail($userId);
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
    # UserController
    # Function name : userList
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Display Admin User listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function contactUsList(Request $request){
        $this->data['page_title']="Contact Us List";
        $this->data['panel_title']="Contact Us List";
        
        return view('admin.contactus.list',$this->data);
    }

    public function contactUsListTable(Request $request){
        $data = ContactUs::where('is_deleted','=','N')->orderBy('id','desc')->get();
        $settingObj=$this->getCurrentUserSettingsData();
        $finalResponse= Datatables::of($data)
            ->addColumn('updated', function ($model) use ($settingObj) {
                $response=$this->compareWithCurrentTime($model->created_at);
                if($response){
                    $dt = \Carbon::parse($model->created_at);
                      
                        $formattedDate=$dt->diffForHumans();
                } else {
                    $date = \Carbon::createFromFormat('Y-m-d H:i:s', $model->created_at, 'UTC');
                    $formattedDate=$date->setTimezone($settingObj->timezone)->format($settingObj->date_format.' '.$settingObj->time_format);
                }
                return $formattedDate;
            })
           
           ->addColumn('action', function ($model) {
                
                $deletelink=  route('admin.contact-us-delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
               

                $actions='<div class="btn-group btn-group-sm ">';
                
                    //$actions .='<a href="' . $editlink . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>';
                    //$actions .='<a href="' . $viewlink . '" class="btn btn-info" id=""><i class="fas fa-eye"></i></a>';
                    
                              
                
                    if(checkFunctionPermission("contact-us-delete")){
               
                    $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>';
                    }
                
                $actions .='</div>';
                return $actions;

                // return '<div class="btn-group btn-group-sm ">
                //         <a href="' . $editlink . '" class="btn btn-info" id=""><i class="fas fa-edit"></i></a>
                //         <a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>
                //       </div>';
            })
            ->rawColumns(['updated','action'])
            ->make(true);
            
            return $finalResponse;

    }

    public function subscriberList(Request $request){
        $this->data['page_title']="Subscriber List";
        $this->data['panel_title']="Subscriber List";
      
        return view('admin.subscriber.list',$this->data);
    }

    public function subscriberListTable(Request $request){
        $data = Subscriber::where('is_deleted','=','N')->with([
                                                            'subscribercategoryData'=>function($query){
                                                                $query->with([
                                                                    'categoryDetails'
                                                                ]);
                                                        }])->orderBy('id','desc')->get();
        $finalResponse= Datatables::of($data)->addColumn('category', function ($model) {
            $categorydetailsString='';
            foreach($model->subscribercategoryData as $cat){
                $categorydetailsString .= $cat->categoryDetails->name.', ';
            }
            return $categorydetailsString;
        })->addColumn('action', function ($model) {
                $deletelink= route('admin.subscribers-delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $actions='<div class="btn-group btn-group-sm ">';
                if(checkFunctionPermission("subscribers-delete")){
                    $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn btn-danger delete-alert" id="button"><i class="fas fa-trash"></i></a>';
                }
                $actions .='</div>';
                return $actions;
        })
        ->rawColumns(['updated','action','category'])
        ->make(true);   
        return $finalResponse;
    }

    public function contactusDelete(Request $request,$encryptString)
    {
       
        $contactusId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = ContactUs::findOrFail($contactusId);

        
        if ($details) {
            $details->deleted_at=Carbon::now();
            $details->deleted_by=\Auth::guard('admin')->user()->id;
            $details->is_deleted='Y';
            $details->save();
            return redirect()->route('admin.contactus.list')->with('success','Contact Us has been deleted successfully!');
        } else {
            $request->session()->flash('alert-danger', 'An error occurred while deleting the Contact Us');
             return redirect()->back();
        }
    }

    public function subcriberDelete(Request $request,$encryptString)
    {
       
        $subcriberId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        $details = Subscriber::findOrFail($subcriberId);

        
        if ($details) {
            $details->deleted_at=Carbon::now();
            $details->deleted_by=\Auth::guard('admin')->user()->id;
            $details->is_deleted='Y';
            $details->save();
            return redirect()->route('admin.subscriber.list')->with('success','Subscribers has been deleted successfully!');
        } else {
            $request->session()->flash('alert-danger', 'An error occurred while deleting the Subscribers ');
             return redirect()->back();
        }
    }



}
