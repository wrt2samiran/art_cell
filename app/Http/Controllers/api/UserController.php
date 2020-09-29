<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Storage;
use Mail;
use Auth;
use Image;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\ContactUs;
use App\Models\SubscriberCategory;
use App\Models\Subscriber;
use App\Models\MasterCategory;
use Illuminate\Support\Facades\File as FileSystem;
use App\Rules\Recaptcha;
use App\Models\Subscription;
use App\Models\UserSubscription;

class UserController extends Controller
{

    /*****************************************************/
    # UserController
    # Function name : login
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : User login after registration
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/
    public function login(Request $request)
    {
        $response = array();
        $input = $request->all();

        $validator = Validator::make($input, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            $response['has_error'] = 1;
            $response['msg'] = $this->formatError($validator->errors()->toArray());
        } else {
            $user = User::where(function ($query) use ($input) {
                $query->where('email', $input['email']);
            })->first();

            if (!empty($user)) {
                    if (Hash::check($input['password'], $user->password)) {
                        if ($user->status == 'A') {
                            $api_token = Str::random(60);
                            
                            $updateArray['api_token'] = $api_token;
                            User::whereId($user->id)->update($updateArray);
                            $response['has_error'] = 0;
                            $response['msg'] = 'Successfully Logged in';
                            $response['api_token'] = $api_token;
                            $response['user'] = $user;
                        } else {
                            $response['has_error'] = 1;
                            $response['msg'] = 'Account Is Deactivated.';
                        }
                    } else {
                        $response['has_error'] = 1;
                        $response['msg'] = 'Wrong Password.';
                    }

            } else {
                $response['has_error'] = 1;
                $response['msg'] = 'Email Isn\'t Registered.';
            }
        }
        return $response;
    }

    /*****************************************************/
    # UserController
    # Function name : register
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : User registration
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function register(Request $request,Recaptcha $recaptcha)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $input=$request->all();

        $validationCondition ['name']  = 'required';
        $validationCondition ['email']  =  'required|email|'.Rule::unique('users')->where(function ($query)  {
            $query->whereNotIn('status',['D']);
        }).'';
        $validationCondition['password'] = 'required';
        $validationCondition['confirm_password'] = 'required';
        $validationCondition['confirm_password'] = 'same:password';
        $validationCondition['phone'] = 'required';

        $validationCondition['profile_pic'] = 'required';
        $validationCondition['recaptcha']=['required', $recaptcha];


        $validationMessage['name.required']="Name field is required.";
        $validationMessage['email.required']="Email field is required.";
        $validationMessage['email.email']="Please enter valid email.";
        $validationMessage['email.unique']="Email Already exists.Please enter another one.";
        $validationMessage['phone.required']="Phone number is required.";
        $validationMessage['profile_pic.required']="Profile pic is required.";



        $validator = Validator::make($request->all(),$validationCondition,$validationMessage);
        if ($validator->fails()) {
            $response['msg']=$this->formatError($validator->errors()->toArray());
        } else {
            $userData['name']=$input['name'];
            $userData['email']=$input['email'];
            $userData['password']=bcrypt($input['password']);
            $userData['phone']=$input['phone'];

            $file_name =time();
            $file = $request->file('profile_pic');    // Make a file  object in a variable.
            $extension = $file->getClientOriginalExtension();  //Only retuns extention included string.
            $fullFileName = $file_name.'.'.$extension; // file name will saved in project dir as the variable value.
           
            $destinationPath = 'assets/images/';
            $uploadResponse = $file->move($destinationPath,$fullFileName); 

            $userData['profile_pic']=$fullFileName;
            $userData['usertype']='FU';
            $userData['created_from']='F';
            $userData['created_at']=Carbon::now();
            $userData['updated_at']=Carbon::now();
            $newUserId=User::insertGetId($userData);
            

            if($newUserId){
                $basicSubscriptionObj = Subscription::where('id',1)->first();
                $newUserSubscriptioModelObj = new UserSubscription();
                $newUserSubscriptioModelObj->user_id=$newUserId;
                $newUserSubscriptioModelObj->subscription_id=$basicSubscriptionObj->id;
                $newUserSubscriptioModelObj->created_at=Carbon::now();
                $newUserSubscriptioModelObj->updated_at=Carbon::now();
                $newUserSubscriptioModelObj->expiry_at=null;
                $newUserSubscriptioModelObj->submission_count=1;
                $newUserSubscriptioModelObj->remaining_count=1;
                $newUserSubscriptioModelObj->status='A';
                $newUserSubscriptioModelObj->save();


                 if(env('MAIL_SERVICE')  == 'ACTIVE'){

                    \Mail::send('email.email-register',
                    [
                        'user' => $userData,
                        'app_config' => [
               
                            'controllerName'=> 'User',
                         ],
                    ], function ($m) use ($userData) {
                        $m->to($userData['email'], $userData['name'])->subject('Email Verification');
                 });
                    }
                     else {
                        $response['has_error']=0;
                        $response['msg']= 'Due to technical issue. We can\'t send mail.please contact with admin.';
                    }  

                $response['has_error']=0;
                $response['msg']='Successfully Registered.';
            }
        }
        return $response;
    }

//     public function testing(Request $request,Recaptcha $recaptcha){
// //        dd('okkk');


//         $validationCondition['title']='required';
//         $validationCondition['recaptcha']=['required', $recaptcha];
// //        dd('valid');

//         $validator = Validator::make($request->all(),$validationCondition);


//         if ($validator->fails()) {
//             $response['msg']=$this->formatError($validator->errors()->toArray());
//         } else {
//             $response['msg']='Okk';
//         }

//         return $response;
//     }

    public function verification(Request $request,$token)
    {
     $response['has_error']=1;
    $response['msg']='Something went wrong.Please try again ';

    
        if ($token) {
            $userId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.

            $data = explode("~",$userId);
            $userData = User::where('id', $data[0])->first();
            if ($userData) {
                if ($userData->remember_token != '') {
                    $userData->status                = 'A';
                    $userData->remember_token        = '';
                    
                    $userData->save();
                
            
                    $response['has_error']=0;
                    $response['msg']='Successfully Registered.'; 

                    
                } 
                return $response;
                
            } 
        } 
    }

    /*****************************************************/
    # UserController
    # Function name : updateUser
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : User Profile Update
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function updateUser(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $authUserDetails = \Auth::guard('api')->user();
        $userDetails=User::findOrFail($authUserDetails->id);


        $validationCondition ['name']  = 'required';              
        $validationCondition['phone'] = 'required';
        // $validationCondition['profile_pic'] = 'required';
       

        $validationMessage['name.required']="Name field is required.";
        $validationMessage['phone.required']="Phone number is required.";
        // $validationMessage['profile_pic.required']="Profile pic is required.";
      


        $validator = Validator::make($request->all(),$validationCondition,$validationMessage);
        if ($validator->fails()) {
            $response['msg']=$this->formatError($validator->errors()->toArray());
        } else {

            $contentArray['website']=$request['website'];
            $contentArray['facebook_url']=$request['facebook_url'];
            $contentArray['twitter_url']=$request['twitter_url'];
            $contentArray['youtube_url']=$request['youtube_url'];
            $contentArray['linkedin_url']=$request['linkedin_url'];
            $contentArray['additional_info']=$request['additional_info'];
            
            $settingJson=json_encode($contentArray);
            $userDetails->name        = trim($request->name, ' ');
            $userDetails->phone             = $request->phone;
            $userDetails->setting_json      =$settingJson;
            $file_name = time();    
            $file = $request->file('profile_pic');    
            if(!empty($file)) {
                $extension = $file->getClientOriginalExtension();  
                $fullFileName = $file_name.'.'.$extension; 
                $destinationPath = 'assets/images';
                $uploadResponse = $file->move($destinationPath,$fullFileName);
                $userDetails->profile_pic=$fullFileName;
                }  
                
                $userDetails->updated_by=\Auth::guard('api')->user()->id;
                $userDetails->updated_at=Carbon::now();
                $savedetails = $userDetails->save();
                if($savedetails){
                    $response['has_error']=0;
                    $response['msg']='Successfully Updated User Details.';
                } 
        }
        return $response;


    }

    /*****************************************************/
    # UserController
    # Function name : forgotPassword
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Forgot password
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function forgotPassword(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';


        
           
                if ($request->isMethod('post')) {
                    // Checking validation
                    $validationCondition = array(
                        'email' => 'required|email',
                    );
                    $validationMessages = array(
                        'email.required' => 'Please provide email id',
                        'email.email' => 'Please provide a valid email id',
                    );
                    $validator = \Validator::make($request->all(), $validationCondition, $validationMessages);

                    if ($validator->fails()) {
                        $response['msg']=$this->formatError($validator->errors()->toArray());
                    } else 
                        $email = $request->email;
                        $emailExists = User::where('email', $email)->count();
                        if ($emailExists > 0) // if this is a valid email
                        {
                            $user = User::where('email', $email)->first(); //Fetching Specific user Data
                            // $recoveryLink = route('api.reset.password' ,['id'=>$user->id]); //making recovery link

                            $recoveryLink=\URL::to('/en/recover-password/'.$user->id.'');
                            // setting mail configuration
                            $toUser = $email;
                            $fromUser = env('MAIL_FROM_ADDRESS'); // getting data form .env file
                            $subject = 'Password Recovery : Bernays ';
                            $mailData = array('recoverLink' => $recoveryLink);

                            // Send mail
                            if(env('MAIL_SERVICE')  == 'ACTIVE'){
                                Mail::send('email.forgot-password', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                                    $sent->from($fromUser)->subject($subject);
                                    $sent->to($toUser);
                                });
                                if (Mail::failures()) // if mail sending failed
                                {
                                    $response['msg']='An error occurred while sending you the email containing the password.';
                                } else // if password could not be saved successfully
                                { 
                                    $response['has_error']=0;
                                     $response['msg']= 'Successfully Password Recovery Link has been sent to your email.';
                                }
                            } else {
                                $response['has_error']=0;
                                $response['msg']= 'Due to technical issue. We can\'t send mail.please contact with admin.';
                            }
                            
                        } else // if this email is not registered
                        {
                             $response['msg']= 'error, This email id is not registered.';
                            
                        }
                    }
                
        return $response;
            }

    /*****************************************************/
    # UserController
    # Function name : resetPassword
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Reset password
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/


 public function resetPassword(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $input=$request->all();
            
        if ($request->isMethod('post')) {
            // Checking validation
            $validationCondition = array(
                'new_password' => 'required', // validation for new password
                'confirm_password' => 'required|same:new_password',
            );
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
                $user = User::findOrFail($input['id']);
                if (!empty($user)) {
                    $user->password = $request->new_password;
                    $user->save();
                    $response['has_error']=0;
                    $response['msg']='success , Your new password successfully updated.';
                } 
            }
        }
        return $response;
    }


    public function contactUscreate(Request $request)
    {

        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $input=$request->all();

        $validationCondition ['name']                  = 'required';              
        $validationCondition['email']                  = 'required';
        $validationCondition['query']                   = 'required';
        $validationCondition['phone']                   = 'required';
       


        $validationMessage['name.required']="Name field is required.";
        $validationMessage['email.required']="Email field is required.";
        $validationMessage['query.required']="Query field is required.";
        $validationMessage['phone.required']="Phone Number is required.";

        $validator = Validator::make($request->all(),$validationCondition,$validationMessage);
        if ($validator->fails()) {
            $response['msg']=$this->formatError($validator->errors()->toArray());
        } else {
            $emailExists = ContactUs::where('email', $input['email'])->count();
            if ($emailExists == 0) {
                $contentData= new ContactUs;
                $contentData['name']=$input['name'];
                $contentData['email']=$input['email'];
                $contentData['phone']=$input['phone'];
                $contentData['account_id']=$input['account_id'];
                $contentData['query']=$input['query'];
                $contentData['created_at']=Carbon::now();
                $contentData['updated_at']=Carbon::now();
                $contentData['is_deleted']='N';
                $saveResponse=$contentData->save();
                if($saveResponse){
                    $response['has_error']=0;
                    $response['msg']='Successfully created contact us.';
                } 
            } else{
                $response['has_error']=0;
                $response['msg']='Successfully created contact us.';
            }
        }
        return $response;

    }
    /*****************************************************/
    # PressReleaseManagementController
    # Function name : pressReleaseCreate
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Press Release Create
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function subscriberCreate(Request $request)
    {

        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $input=$request->all();
        //dd($input);

        $validationCondition ['full_name']              = 'required';              
        $validationCondition['email']                    = 'required';
        $validationCondition ['media_outlet']            = 'required';
        $validationCondition ['job_type']                = 'required';
    
        $validationMessage['full_name.required']="Full Name is required.";
        $validationMessage['email.required']="Email  field is required.";
        $validationMessage['media_outlet.required']="Media Outlet  field is required.";
        $validationMessage['job_type.required']="Job Type field is requied.";
      
        $validator = Validator::make($request->all(),$validationCondition,$validationMessage);
        if ($validator->fails()) {
            $response['msg']=$this->formatError($validator->errors()->toArray());
        } else {
          
            $emailExists = Subscriber::where('email', $input['email'])->count();
            if ($emailExists == 0) {
            $subscriberObj = new Subscriber;
            $subscriberObj->full_name=$input['full_name'];
            $subscriberObj->email=$input['email'];
            $subscriberObj->media_outlet=$input['media_outlet'];
            $subscriberObj->job_type=$input['job_type'];
            

            
            $subscriberObj->created_at=Carbon::now();
            $subscriberObj->updated_at=Carbon::now();
            
            $subscriberObj->is_deleted='N';
            $saveResponse=$subscriberObj->save();

            $newsubscriberId= $subscriberObj->id;  

            foreach($input['category'] as $val) {
                $subscribercategory = new SubscriberCategory();
                $subscribercategory->subcriber_id = $newsubscriberId;
                $subscribercategory->subcriber_type_id = $val;
                $saveResponse = $subscribercategory->save();
            }

                if($saveResponse){
                    $response['has_error']= 0 ;
                    $response['msg']= 'Successfully created subscriber.' ;
                } 
            } else{
                $response['has_error']=0;
                $response['msg']='Successfully created contact us.';
            }  
        }
        return $response; 

    }

    public function masterCategoryList(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        

        $masterCategoryList= MasterCategory::select('id','name','slug')->where('status','=','A')->where('is_deleted','=','N')->get();
        if(!empty($masterCategoryList)){
            $response['has_error']=0;
            $response['msg']='Successfully showing Listing.';
            $response['data']= $masterCategoryList;
        }
        return $response;


    }
    public function uniqueEmailchecking(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='false ';
        $validationCondition ['email']  = 'email|'.Rule::unique('users')->where(function ($query)  {
            $query->whereNotIn('status',['D']);
        }).'';
        $validationMessage['email.email']="Please enter valid email.";
        $validationMessage['email.unique']="Email Already exists.Please enter another one.";
        $validator = Validator::make($request->all(),$validationCondition,$validationMessage);

        if ($validator->fails()) {
            $response['msg']=$this->formatError($validator->errors()->toArray());
        } else {
           

            $emailcheck= User::where(['status'=>'A','is_deleted'=>'N','usertype'=>'FU','email'=>$request->email])
            ->count();

            if($emailcheck){
                $response['has_error']=1;
                $response['msg']='Email Already exists';
               
            } else {
                $response['has_error'] = 0;
                $response['msg']="true";
            }
      
    }   
        return $response;

    }




}
