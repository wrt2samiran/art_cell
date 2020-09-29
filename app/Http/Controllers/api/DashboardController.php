<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Storage;
use Auth;
use Image;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\OurService;
use App\Models\Cms;
use Illuminate\Support\Facades\File as FileSystem;
use App\Rules\Recaptcha;



class DashboardController extends Controller{

     /*****************************************************/
    # DashboardController
    # Function name : changePassword
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Change password after login
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function changePassword(Request $request)
    {

        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';

        if (!(Hash::check($request->get('current_password'), \Auth::guard('api')->user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
        } else {
            

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
                    $authUserDetails = \Auth::guard('api')->user();
                    $user = User::findOrFail($authUserDetails->id); 
                    $user->password = $request->new_password;
                    $saveResposne = $user->save();
                    if ($saveResposne == true) {

                        $response['has_error']=0;
                        $response['msg']='Successfully Changed Password.';
                    }
                }

        }

        return $response;

    }

    public function subscriptionsList(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $siteSettingObj=(object)json_decode(User::find(1)->setting_json,true);
        // dd($siteSettingObj);
        $subscriptionList= Subscription::with(['subscriptionTypeDetails'])->
                                            where('status','=','A')->
                                            where('is_deleted','=','N')->
                                            get();
            if(!empty($subscriptionList)){
                $response['has_error']=0;
                $response['msg']='Successfully showing Listing.';
                $response['currency_symbol']=$siteSettingObj->currency_symbol;
                $response['currency_code']=$siteSettingObj->currency_code;
                $response['data']= $subscriptionList;
            }
        return $response;

    }

    public function transactionList(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $authUserDetails = \Auth::guard('api')->user();

        $transactionList=Transaction::where('created_by',$authUserDetails->id)
                                     ->with(['paymenttypeData','UserData'])
                                     ->where('is_deleted','N')
                                     ->orderBy('id','desc')
                                     ->get();

        if(!empty($transactionList)){
            $response['has_error']=0;
            $response['msg']='Successfully showing Listing.';
            $response['data']= $transactionList;
            $response['user_data']=$authUserDetails;
        }
        return $response;


    }
    public function ourservicesList(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
       
        $ourservicesList= OurService:: where('status','=','A')
                                        ->where('is_deleted','=','N')
                                        ->get();

            if(!empty($ourservicesList)){
                $response['has_error']=0;
                $response['msg']='Successfully showing Listing.';
                $response['data']= $ourservicesList;
            }
        return $response;

    }
    public function cmsList(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        
       if(!empty($request->slug)){
            $cmsList= Cms:: where('status','=','A')
            ->where('is_deleted','=','N')
            ->where('slug',$request->slug)
            ->get();

            if(!empty($cmsList)){
                $response['has_error']=0;
                $response['msg']='Successfully showing Listing.';
                $response['content']= $cmsList;
            } else {
                $response['has_error'] = 1;
                $response['msg']="No object is required.";
            }
       } else {
            $response['has_error'] = 1;
            $response['msg']="Slug  is required";
       }
       
        return $response;

    }

}


