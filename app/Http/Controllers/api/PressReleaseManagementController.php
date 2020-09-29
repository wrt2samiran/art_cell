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
use Illuminate\Support\Facades\File as FileSystem;
use App\Models\PressReleaseMedias;
use App\Models\PressRelease;
use App\Models\MasterCategory;
use App\Models\PressReleaseCategory;
use App\Models\Addon;
use App\Models\PressReleaseAddon;
use App\Models\UserSubscription;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Payment;
use App\Models\Transaction;




class PressReleaseManagementController extends Controller
{

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

    public function pressReleaseCreate(Request $request)
    {

        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $input=$request->all();
        //dd($input);

        $validationCondition ['headline']           = 'required';              
        $validationCondition['seo_keywords']        = 'required';
        $validationCondition ['lang']               = 'required';
        $validationCondition ['body_text']          = 'required';
        $validationCondition ['caption']            = 'required';
        $validationCondition ['modaretion_note']    = 'required';
        

        $validationMessage['headline.required']="Headline is required.";
        $validationMessage['seo_keywords.required']="SEO Keywords  field is required.";
        $validationMessage['lang.required']="Please select this field.";
        $validationMessage['body_text.required']="Body text field is requied.";
        $validationMessage['caption.required']="Caption is required.";
        $validationMessage['modaretion_note.required']="Moderate note is required.";

        $validator = Validator::make($request->all(),$validationCondition,$validationMessage);
        if ($validator->fails()) {
            $response['msg']=$this->formatError($validator->errors()->toArray());
        } else {
           $contentArray['lang']=$input['lang'];
           $contentArray['body_text']=$input['body_text'];
           $contentArray['caption']=$input['caption'];
           $contentArray['modaretion_note']=$input['modaretion_note'];
           
           $constentJson=json_encode($contentArray);

           $publicationDateObj=Carbon::createFromFormat('d/m/Y', $input['publication_date']);

            $PressReleaseModelObj = new PressRelease;
            $PressReleaseModelObj->headline=$input['headline'];
            $PressReleaseModelObj->content_json=$constentJson;
            $PressReleaseModelObj->publication_date=$publicationDateObj->format('Y-m-d H:i:s');
            $PressReleaseModelObj->seo_keywords=$input['seo_keywords'];
            $PressReleaseModelObj->status='draft';
            $PressReleaseModelObj->created_at=Carbon::now();
            $PressReleaseModelObj->updated_at=Carbon::now();
            $PressReleaseModelObj->created_by=\Auth::guard('api')->user()->id;
            $PressReleaseModelObj->updated_by=\Auth::guard('api')->user()->id;
            $PressReleaseModelObj->is_deleted='N';
            $saveResponse=$PressReleaseModelObj->save();

            $newPressReleaseId= $PressReleaseModelObj->id;  

            foreach($input['category'] as $val) {
                $pressReleasecategory = new PressReleaseCategory();
                $pressReleasecategory->press_release_id = $newPressReleaseId;
                $pressReleasecategory->category_id = $val;
                $saveResponse = $pressReleasecategory->save();
            }

            if($saveResponse){
                $response['has_error']= 0 ;
                $response['id']=$newPressReleaseId;
                $response['msg']= 'Successfully created Press Release.' ;
            }
            
        }
        return $response; 

    }

    /*****************************************************/
    # PressReleaseManagementController
    # Function name : pressReleaseList
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Press Release List
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function LatestpressReleaseList(Request $request)
    {

        
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        
        //$authUserDetails = \Auth::guard('api')->user();
        $q= $request->get('q');
        $category=$request->get('category');
        $categoryId=0;

        if(!empty($category)){
            $categoryobj=MasterCategory::where('slug',$category)->first();
            $categoryId = $categoryobj != null ? $categoryobj->id : 0;
        }
        
        $pressreleaseList=PressRelease::where('headline', 'like', "%{$q}%")
                                        ->where('status','released')
                                        ->where('is_deleted','N')
                                        ->with([
                                                'UserData',
                                                'pressreleaseData',
                                                'pressreleaseCategoryData'=>function($query){
                                                                                $query->with(['categoryDetails']) ;
                                                                            },
                                                'pressreleaseAddonData'=>function($query){
                                                                            $query->with(['AddonDetails']) ;
                                                                            }
                                            ]);
                                    if($categoryId > 0 ){
                                        $pressreleaseList->whereHas('pressreleaseCategoryData', function($q)use($categoryId){
                                            $q->where('category_id', $categoryId);
                                        });
                                    } 
                                    $pressreleaseListing=$pressreleaseList->orderBy('publication_date','desc')
                                        ->paginate(4);
        if(!empty($pressreleaseListing)){
            $response['has_error']=0;
            $response['msg']='Latest Press Release  Listing.';
            $response['data']= $pressreleaseListing;
        }
        return $response;


    }

    /*****************************************************/
    # PressReleaseManagementController
    # Function name : pressReleaseDelete
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Press Release Delete
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/


    public function pressReleaseDelete(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';

        $pressreleaseId = $request->id;
            $details = PressRelease::find($pressreleaseId);
            if(!empty($details )){
            $details->deleted_at=Carbon::now();
            $details->deleted_by=\Auth::guard('api')->user()->id;
            $details->is_deleted='Y';
            $saveResponse=$details->save();
            }

            if(!empty($saveResponse)){
                $response['has_error']=0;
                $response['msg']="Press Release Succressfuuly deleted.";
            }
            else {
                $response['has_error']=1;
                $response['msg']="Sorry! Can't Deleted.Wrong Attempt.";
           }
            return $response;
      
    }
    /*****************************************************/
    # PressReleaseManagementController
    # Function name : pressReleaseMediaCreate
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : PressRelease Media Create
    #                 
    #                 
    # Params        : Request $request 
    /*****************************************************/

    public function pressReleaseMediaCreate(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $mediaobj=PressReleaseMedias::where('press_release_id',$request->press_release_id)->first();

        if($mediaobj != null)
            $new = PressReleaseMedias::find($mediaobj->id);
        else{
            $new = new PressReleaseMedias();
            $new->created_at  = Carbon::now();
        }
               
        $new->press_release_id = $request->press_release_id;
        $mediaPath_name =time();
        $file = $request->file('upload_media_path') ?? '';

        if($file){
            $extension = $file->getClientOriginalExtension()  ;  //Only retuns extention included string.
            $fullFileName = $mediaPath_name.'.'.$extension; // file name will saved in project dir as the variable value.
            $destinationPath = 'assets/pressreleasemedia/';
            $uploadResponse = $file->move($destinationPath,$fullFileName); 
            $new->upload_media_path=$fullFileName ; 
        }
        if(!empty($request->media_description ))
            $new->media_description = $request->media_description ;
        if(!empty($request->embeded_link))
            $new->embeded_link = $request->embeded_link ;
        if(!empty($request->media_link))
            $new->media_link = $request->media_link ;
        
        $new->updated_at  = Carbon::now();
        $savemediapressRelease = $new->save();

        if($savemediapressRelease){
            $response['has_error']= 0 ;
            $response['msg']= 'Successfully saved your media content.' ;
        }else{    
            $response['has_error']= 1 ;
            $response['msg']= 'Something went wrong. Please try again later.' ;
        }
        return $response;
    }

    public function pressReleaseCount(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';

        
            $totalpressRelease = PressRelease::where('is_deleted','=','N')->get()->count();
        

            if(!empty( $totalpressRelease)){
                $response['has_error']=0;
                $response['msg']="Press Release Counting .";
                $response['data']=$totalpressRelease;
            }
            else {
                $response['has_error']=1;
                $response['msg']="Sorry! Can't Deleted.Wrong Attempt.";
           }
            return $response;
      
    }


    public function pressReleaseUpdateStep1(Request $request){
       
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $input=$request->all();
        $pressreleaseId = $request->id;
        $details = PressRelease::where('is_deleted','=','N')->findOrFail($pressreleaseId);
        
        $validationCondition ['headline']           = 'required';              
        $validationCondition['seo_keywords']        = 'required';
        $validationCondition ['lang']               = 'required';
        $validationCondition ['body_text']          = 'required';
        $validationCondition ['caption']            = 'required';
        $validationCondition ['modaretion_note']    = 'required';
        $validationCondition['publication_date']    = 'required';

        $validationMessage['headline.required']="Headline is required.";
        $validationMessage['seo_keywords.required']="SEO Keywords  field is required.";
        $validationMessage['lang.required']="Please select this field.";
        $validationMessage['body_text.required']="Body text field is requied.";
        $validationMessage['caption.required']="Caption is required.";
        $validationMessage['modaretion_note.required']="Moderate note is required.";

        $validator = Validator::make($request->all(),$validationCondition,$validationMessage);
        if ($validator->fails()) {
            $response['msg']=$this->formatError($validator->errors()->toArray());
        } else {
           $contentArray['lang']=$input['lang'];
           $contentArray['body_text']=$input['body_text'];
           $contentArray['caption']=$input['caption'];
           $contentArray['modaretion_note']=$input['modaretion_note'];
           
           $constentJson=json_encode($contentArray);

           $publicationDateObj=Carbon::createFromFormat('d/m/Y', $input['publication_date']);
            
            $details->headline=$input['headline'];
            $details->content_json=$constentJson;
            $details->publication_date=$publicationDateObj->format('Y-m-d H:i:s');
            $details->seo_keywords=$input['seo_keywords'];
            $details->updated_at=Carbon::now();
          
            $details->updated_by=\Auth::guard('api')->user()->id;
            $details->is_deleted='N';
            //dd($details);
            $saveResponse=$details->save();

            if($saveResponse){
                $response['has_error']= 0 ;
                $response['msg']= 'Successfully Updated Press Release Step 1.' ;
            } 
        }
        return $response;

    }

    public function pressReleaseUpdateStep2(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';

        $pressreleaseId = $request->press_release_id;
        $details = PressReleaseMedias::findOrFail($pressreleaseId);

        if($pressreleaseId){
            $mediaPath_name =time();
            $file = $request->file('upload_media_path');   // Make a file  object in a variable.
            if(!empty($file)) {
            $extension = $file->getClientOriginalExtension(); //Only retuns extention included string.
            $fullFileName = $mediaPath_name.'.'.$extension;// file name will saved in project dir as the variable value.
            $destinationPath = 'assets/pressreleasemedia/';
            $uploadResponse = $file->move($destinationPath,$fullFileName);
            $details->upload_media_path=$fullFileName;
            $details->media_description = $request->media_description;
        }
        
        if(!empty( $request->embeded_link))
            $details->embeded_link = $request->embeded_link;
        if(!empty( $request->media_link ))
            $details->media_link = $request->media_link;

        $details->updated_at  = Carbon::now();
        $savemediapressRelease = $details->save();
        
        if($savemediapressRelease){
            $response['has_error']= 0 ;
            $response['msg']= 'Successfully Updated Press Release Step 2.' ;
        } 
    } 
        return $response;
    }


    
    public function pressReleaseAddonList(Request $request)
    {

        
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        

        $addonList=Addon::select('id','title','description','price')->
                        where('status','=','A')->
                        where('is_deleted','=','N')->
                        get();
            if(!empty($addonList)){
                $response['has_error']=0;
                $response['msg']='Successfully showing Listing.';
                $response['data']= $addonList;
            }
        return $response;


    }

    public function pressReleaseAddOnCreate(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $saveResponse= true;
       
        foreach($request['addonId'] as $val) {
            $pressReleaseaddons = new PressReleaseAddon();
            $pressReleaseaddons->press_release_id = $request->press_release_id;
            $pressReleaseaddons->addons_id = $val;
            $pressReleaseaddons->created_at  = Carbon::now();
            $pressReleaseaddons->updated_at  = Carbon::now();
            $saveResponse = $pressReleaseaddons->save();
        }

        if($saveResponse){
            $response['has_error']= 0 ;
            $response['msg']= 'Successfully created Addons.' ;
        }
       
        return $response;
    }

    public function pressReleaseDetails(Request $request){
        $response['has_error']=1 ;
        $response['msg']='Something went wrong.Please try again.';
      
        $pressReleaseId = $request->id; // get user-id After Decrypt with salt key.
        // dd($request->id);
        $detailsData=PressRelease::where('is_deleted','N')
                                //  ->where('status','released')
                                 ->with(['UserData',
                                        'pressreleaseData',
                                        'pressreleaseCategoryData'=>function($query){
                                                                $query->with(['categoryDetails']) ;
                                        },
                                        'pressreleaseAddonData'=>function($query){
                                                                $query->with(['AddonDetails']) ;
                                        }]
                                    )->find($pressReleaseId);

                // dd($detailsData);
        if(!empty($detailsData)){
            $response['has_error']=0;
            $response['msg']="Press Release details .";
            $response['data']=$detailsData;
        }
        else {
            $response['has_error']=1;
            $response['msg']="Something went wrong.Please try again.";
       }
    return $response;
    }



    public function pressReleaseList(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $authUserDetails = \Auth::guard('api')->user();

        $pressReleaseList=PressRelease::where('created_by',$authUserDetails->id)->orderBy('id','desc')->get();
        if(!empty($pressReleaseList)){
            $response['has_error']=0;
            $response['msg']='Successfully showing Listing.';
            $response['data']= $pressReleaseList;
        }
        return $response;


    }

    public function getUserPaymentStatus(Request $request){
        $response['has_error']=0;
        $response['payment']=true;
        $response['msg']="Something went wrong. Please try again later.";
        $authUserDetails = \Auth::guard('api')->user();
        $basicSubscriptionObj = Subscription::where('id',1)->first();
        $settingJson=User::findOrFail(1)->setting_json;
        $settingObj = (object) json_decode($settingJson);

        $userSubscriptionObj=UserSubscription::where('user_id',$authUserDetails->id)
                                              ->where('status','A')
                                              ->latest()
                                              ->first();

        if($userSubscriptionObj && $userSubscriptionObj->expiry_at != null ){
            $expiryTimeStamp= \Carbon::parse($userSubscriptionObj->expiry_at)->timestamp;
            if( $expiryTimeStamp > time() ){
                $response['payment']=false;
                $response['msg'] = "Sucess!";
                $response['total_count']=$userSubscriptionObj->submission_count;
                $response['remaining_count']=$userSubscriptionObj->remaining_count;
            } else{
                $response['msg'] = "Sucess!";
                $response['payment_amount'] = $basicSubscriptionObj->price;
                $response['vat_amount']=$settingObj->vat_value_for_press_release;
            }
        } else{
            $response['msg'] = "Sucess!";
            $response['payment_amount'] = $basicSubscriptionObj->price;
            $response['vat_amount']=$settingObj->vat_value_for_press_release;
        }
        return $response;
    }

    public function payment(Request $request){
        $response['has_error']=1;
        $response['msg']="Something went wrong. Please try again later.";
        $authUserDetails= \Auth::guard('api')->user();
        $basicSubscriptionObj = Subscription::where('id',1)->first();
        $settingJson=User::findOrFail(1)->setting_json;
        $settingObj = (object) json_decode($settingJson);

        $userSubscriptionObj=UserSubscription::where('user_id',$authUserDetails->id)
                                              ->where('status','A')
                                              ->latest()
                                              ->first();

        if($userSubscriptionObj && $userSubscriptionObj->expiry_at != null ){
            $expiryTimeStamp= \Carbon::parse($userSubscriptionObj->expiry_at)->timestamp;
            if( $expiryTimeStamp > time() && $userSubscriptionObj->remaining_count > 0 ){
                $userSubscriptionModelObj = UserSubscription::find($userSubscriptionObj->id);
                $userSubscriptionModelObj->remaining_count =$userSubscriptionObj->remaining_count -1;
                $userSubscriptionModelObj->updated_at =Carbon::now();
                $userSubscriptionModelObj->save();
            } else{
                $entity_id=$userSubscriptionObj->id;
                if($userSubscriptionObj->subscription_id != $basicSubscriptionObj->id){
                    UserSubscription::where('created_by',$authUserDetails->id)->update(['status'=>'I']);
                    $newUserSubscriptioModelObj = new UserSubscription();
                    $newUserSubscriptioModelObj->user_id=$suthUserDetails->id;
                    $newUserSubscriptioModelObj->subscription_id=$basicSubscriptionObj->id;
                    $newUserSubscriptioModelObj->created_at=Carbon::now();
                    $newUserSubscriptioModelObj->updated_at=Carbon::now();
                    $newUserSubscriptioModelObj->expiry_at=null;
                    $newUserSubscriptioModelObj->submission_count=1;
                    $newUserSubscriptioModelObj->remaining_count=1;
                    $newUserSubscriptioModelObj->status='A';
                    $newUserSubscriptioModelObj->save();
                    $entity_id= $newUserSubscriptioModelObj->id;
                }
                $paymentModelObj= new Payment();
                $paymentModelObj->payment_type= 'subscription';
                $paymentModelObj->entity_id = $entity_id;
                $paymentModelObj->status = 'payment_successfull';
                $paymentModelObj->created_at=Carbon::now();
                $paymentModelObj->updated_at=Carbon::now();
                $paymentModelObj->created_by=$authUserDetails->id;
                $paymentModelObj->updated_by=$authUserDetails->id;
                $paymentModelObj->save();
                $paymentId= $paymentModelObj->id;

                PressRelease::where('id',$request->press_release_id)->update(['payment_id'=>$paymentId,'status'=>'published']);

                $transactionModelObj = new Transaction();
                $transactionModelObj->payment_id =$paymentId;
                $transactionModelObj->amount = $basicSubscriptionObj->price;
                $transactionModelObj->tax_amount = $settingObj->vat_value_for_press_release;
                $transactionModelObj->total_amount = $basicSubscriptionObj->price + $settingObj->vat_value_for_press_release;
                $transactionModelObj->status ='successful';
                $transactionModelObj->created_at=Carbon::now();
                $transactionModelObj->updated_at=Carbon::now();
                $transactionModelObj->created_by=$authUserDetails->id;
                $transactionModelObj->updated_by=$authUserDetails->id;
                $finalResponse = $transactionModelObj->save();



            }
        } else{
                $paymentModelObj= new Payment();
                $paymentModelObj->payment_type= 'subscription';
                $paymentModelObj->entity_id = $userSubscriptionObj->id;
                $paymentModelObj->status = 'payment_successfull';
                $paymentModelObj->created_at=Carbon::now();
                $paymentModelObj->updated_at=Carbon::now();
                $paymentModelObj->created_by=$authUserDetails->id;
                $paymentModelObj->updated_by=$authUserDetails->id;
                $paymentModelObj->save();
                $paymentId= $paymentModelObj->id;

                PressRelease::where('id',$request->press_release_id)->update(['payment_id'=>$paymentId,'status'=>'published']);

                $transactionModelObj = new Transaction();
                $transactionModelObj->payment_id = $paymentId;
                $transactionModelObj->amount = $basicSubscriptionObj->price;
                $transactionModelObj->tax_amount = $settingObj->vat_value_for_press_release;
                $transactionModelObj->total_amount = $basicSubscriptionObj->price + $settingObj->vat_value_for_press_release;
                $transactionModelObj->status ='successful';
                $transactionModelObj->created_at=Carbon::now();
                $transactionModelObj->updated_at=Carbon::now();
                $transactionModelObj->created_by=$authUserDetails->id;
                $transactionModelObj->updated_by=$authUserDetails->id;
                $finalResponse = $transactionModelObj->save();

        }
        if($finalResponse){
            $response['has_error']= 0;
            $response['msg']= 'Published your press. wait for admin approval.';
        }

        return $response;

    }
    public function UserloginpressReleaseList(Request $request)
    {
        $response['has_error']=1;
        $response['msg']='Something went wrong.Please try again ';
        $authUserDetails = \Auth::guard('api')->user();
        $pressreleaseList=PressRelease::where('created_by',$authUserDetails->id)->orderBy('id','desc')->get();
       
        if(!empty($pressreleaseList)){
            $response['has_error']=0;
            $response['msg']='Successfully showing Listing.';
            $response['data']= $pressreleaseList;
        }
        return $response;


    }



}