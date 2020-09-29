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
use Config;
use Illuminate\Validation\Rule;
use App\Models\PrCopywriter;
use App\Models\PrCopywriterContent;

use App\Models\User;

class PrCopywriteManagementController extends Controller
{

    /*****************************************************/
    # PrCopywriteManagementController
    # Function name : prCopywritingCreate
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : PR Copywriting Create
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function prCopywritingCreate(Request $request)
    {

        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $input=$request->all();

        $validationCondition ['name']  = 'required';              
        $validationCondition['business_purpose'] = 'required|min:2|max:255';
        $validationCondition['aim']             = 'required|min:2|max:255';
        $validationCondition['overview']        = 'required|min:2|max:255';
        $validationCondition['audience']        = 'required|min:2|max:255';
        $validationCondition['benefits']        = 'required|min:2|max:255';
        $validationCondition['spokesperson']    = 'required|min:2|max:255';
        $validationCondition['examples']        = 'required|min:2|max:255';
        $validationCondition['additional_notes'] = 'required|min:2|max:255';


        $validationMessage['name.required']="Name field is required.";
        $validationMessage['business_purpose.required']="Business Purpose field is required.";
        $validationMessage['business_purpose.min']="Business Purpose should be should be at least 2 characters.";
        $validationMessage['business_purpose.max']="Business Purpose  should not be more than 255 characters.";
        $validationMessage['aim.required']="Aim field is required.";
        $validationMessage['aim.min']="Aim should be should be at least 2 characters.";
        $validationMessage['aim.max']="Aim should not be more than 255 characters.";
        $validationMessage['overview.required']="Overview field is required.";
        $validationMessage['overview.min']="Overview should be should be at least 2 characters.";
        $validationMessage['overview.max']="Overview should not be more than 255 characters.";
        $validationMessage['audience.required']="Audience field is required.";
        $validationMessage['audience.min']="Audience should be should be at least 2 characters.";
        $validationMessage['audience.max']="Audience should not be more than 255 characters.";
        $validationMessage['benefits.required']="Benefits field is required.";
        $validationMessage['benefits.min']="Benefits should be should be at least 2 characters.";
        $validationMessage['benefits.max']="Benefits should not be more than 255 characters.";
        $validationMessage['spokesperson.required']="Spokesperson field is required.";
        $validationMessage['spokesperson.min']="Spokesperson should be should be at least 2 characters.";
        $validationMessage['spokesperson.max']="Spokesperson should not be more than 255 characters.";
        $validationMessage['examples.required']="examples field is required.";
        $validationMessage['examples.min']="examples should be should be at least 2 characters.";
        $validationMessage['examples.max']="examples should not be more than 255 characters.";
        $validationMessage['additional_notes.required']="'Additional notes field is required.";
        $validationMessage['additional_notes.min']="Additional notes should be should be at least 2 characters.";
        $validationMessage['additional_notes.max']="Additional notes should not be more than 255 characters.";

        $validator = Validator::make($request->all(),$validationCondition,$validationMessage);
        if ($validator->fails()) {
            $response['msg']=$this->formatError($validator->errors()->toArray());
        } else {
           $contentArray['business_purpose']=$input['business_purpose'];
           $contentArray['aim']             =$input['aim'];
           $contentArray['overview']        =$input['overview'];
           $contentArray['audience']        =$input['audience'];
           $contentArray['benefits']        =$input['benefits'];
           $contentArray['spokesperson']    =$input['spokesperson'];
           $contentArray['examples']        =$input['examples'];
           $contentArray['additional_notes']=$input['additional_notes'];
           $constentJson=json_encode($contentArray);


            $contentData['ticket_no']=$this->generatePrCopyTicket();
            $contentData['user_id']=\Auth::guard('api')->user()->id;
            $contentData['status']='requested';
            $contentData['name']=$input['name'];
            $contentData['content_json']=$constentJson;
            $contentData['created_at']=Carbon::now();
            $contentData['updated_at']=Carbon::now();
            $contentData['created_by']=\Auth::guard('api')->user()->id;
            $contentData['updated_by']=\Auth::guard('api')->user()->id;
            
            $saveResponse=PrCopywriter::insert($contentData);  // saving to table
            //dd($contentData);
            if($saveResponse){
                $response['has_error']=0;
                $response['msg']='Successfully Requested your PR copywrite.';
                $response['succressfully created ticket no']=$contentData['ticket_no'];
            } 
        }
        return $response;

    }
    /*****************************************************/
    # PrCopywriteManagementController
    # Function name : prCopywritingList
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : PR Copywriting List
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function prCopywritingList(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $authUserDetails = \Auth::guard('api')->user();

        $prCopywriteList=PrCopywriter::where('user_id',$authUserDetails->id)->orderBy('id','desc')->get();
        if(!empty($prCopywriteList)){
            $response['has_error']=0;
            $response['msg']='Successfully showing Listing.';
            $response['data']= $prCopywriteList;
        }
        return $response;


    }
    /*****************************************************/
    # PrCopywriteManagementController
    # Function name : prCopywritingDelete
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : PR Copywriting Delete
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/
    public function prCopywritingDelete(Request $request){
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
    
        $prCopywriteId = $request->id;
        $details = PrCopywriter::find($prCopywriteId);
           if(!empty($details )){
                $details->deleted_at=Carbon::now();
                $details->deleted_by=\Auth::guard('api')->user()->id;
                $details->is_deleted='Y';
                $saveResponse=$details->save();
           } 

            if(!empty($saveResponse)){
                $response['has_error']=0;
                $response['msg']="PR Copywrite Succressfuuly deleted.";
            }
            else {
                $response['has_error']=1;
                $response['msg']="Sorry! Can't Deleted.Wrong Attempt.";
           }
        return $response;
    }
    /*****************************************************/
    # PrCopywriteManagementController
    # Function name : prCopywritingRequest
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : PR Copywriting Request
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function prCopywritingRequest(Request $request){
        $response['has_error']=1 ;
        $response['msg']='Something went wrong.Please try again.';
        $superadminSettingJson = User::find(1)->setting_json;
        $superadminSettingObj = (object) json_decode($superadminSettingJson);

        $returnPermission = $superadminSettingObj->return_request == "yes" ? true : false;
        $prCopywritedetails=PrCopywriter::where('ticket_no',$request->ticket_no)->first();


        if($prCopywritedetails){
            $prCopyWriteContentCount = PrCopywriterContent::where('pr_copy_writers_id',$prCopywritedetails->id)
                                                            ->where('status','submitted')
                                                            ->count();

            if(($returnPermission == true) &&  ($superadminSettingObj->limitation_count > $prCopyWriteContentCount) ){
                $msg['msg']="You amximum submission quota exceed";
            } else {
                $saveResponse=PrCopywriter::where('ticket_no',$request->ticket_no)->update([
                                                    'status'=>'return_requested',
                                                    'updated_by'=>\Auth::guard('api')->user()->id,
                                                    'updated_at'=>Carbon::now()
                                                ]);
    
                if($saveResponse){
                    $response['has_error']=0;
                    $response['msg']="PR Copywrite request successfully Updated.";
                }
            }

        } else {
            $response['msg']= "Sorry something went wrong. Please try again later.";
        } 
        return $response;
    }

    public function prCopywritingDetails(Request $request){
        $response['has_error']=1 ;
        $response['msg']='Something went wrong.Please try again.';
      
        $prcopywriteId = $request->id; // get user-id After Decrypt with salt key.
        $detailsData=PrCopywriter::with(['Allcontent'])->find($prcopywriteId);
        if(!empty($detailsData)){
            $response['has_error']=0;
            $response['msg']="PR Copywrite details listing.";
            $response['msg']=$detailsData;
        }
        else {
            $response['has_error']=1;
            $response['msg']="Something went wrong.Please try again.";
       }
    return $response;
    }


}