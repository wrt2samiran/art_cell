<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User,Message,Contract,Property,Notification,MessageAttachment};
use Carbon\Carbon;
use Illuminate\Support\Str;
use File,Helper;
class MessageController extends Controller
{
    //defining the view path
    private $view_path='admin.messages';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for messages list                                             #
    # Function name    : list                                                #
    # Created Date     : 02-12-2020                                          #
    # Modified date    : 02-12-2020                                          #
    # Purpose          : For messages list                                   #

    public function list(Request $request){
        $this->data['page_title']='Messages';
        $current_user=auth()->guard('admin')->user();
        $messages=Message::where('message_to',$current_user->id)
        ->when($request->keyword,function($q)use ($request){
        	$q->where('subject','LIKE','%'.$request->keyword.'%')
        	->orWhere('message','LIKE','%'.$request->keyword.'%')
        	->orWhereHas('from_user',function($q) use($request){
        		$q->where('name','LIKE','%'.$request->keyword.'%')
        		->orWhere('email','LIKE','%'.$request->keyword.'%');
        	});
        })
        ->orderBy('id','DESC')
        ->simplePaginate(20);

        $this->data['messages']=$messages;
        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function for sent messages list                                        #
    # Function name    : list                                                #
    # Created Date     : 02-12-2020                                          #
    # Modified date    : 02-12-2020                                          #
    # Purpose          : For sent messages list                              #

    public function sent(Request $request){
        $this->data['page_title']='Sent Messages';
        $current_user=auth()->guard('admin')->user();
        $this->data['messages']=Message::where('message_from',$current_user->id)
        ->whereHas('to_user')
        ->with('to_user')
        ->orderBy('id','DESC')
        ->simplePaginate(20);
        return view($this->view_path.'.sent',$this->data);
    }

    

    /************************************************************************/
    # Function for display compose message page                              #
    # Function name    : list                                                #
    # Created Date     : 02-12-2020                                          #
    # Modified date    : 02-12-2020                                          #
    # Purpose          : For display compose message page                    #

    public function compose(){
        $this->data['page_title']='Compose Message';
        $current_user=auth()->guard('admin')->user();

        $current_user_type=$current_user->role->user_type->slug;


        if($current_user_type=='super-admin'){
        	$users=User::whereHas('role.user_type')->with('role.user_type')->where('id','!=',$current_user->id)->get();
        }else if($current_user_type=='property-owner'){

    	    $contracts=Contract::with('property')->whereHas('property',function($q) use($current_user){
    	    	if($current_user->created_by_admin){
    	    		$q->where('property_owner',$current_user->id);
					
    	    	}else{
    	    		$q->where('property_manager',$current_user->id);
    	    	}
    			
    		})->get();

    	    $contract_ids_array=$contracts->pluck('id')->toArray();

    		
    		$property_manager_ids=Contract::with('property')->whereIn('id',$contract_ids_array)->whereHas('property',function($q){
    			$q->whereNotNull('property_manager');
    		})->get()->pluck('property.property_manager')->toArray();


    		$property_owner_ids=Contract::with('property')->whereIn('id',$contract_ids_array)->get()->pluck('property.property_owner')->toArray();

    		$service_provider_ids=Contract::whereIn('id',$contract_ids_array)->get()->pluck('service_provider_id')->toArray();


    		$users_ids=array_unique(array_merge($property_manager_ids,$property_owner_ids,$service_provider_ids));
    		

        	$users=User::whereHas('role.user_type')->with('role.user_type')->where('id','!=',$current_user->id)
        	->where(function($q) use($users_ids){
        		$q->whereIn('id',$users_ids)
        		->orWhere(function($q1){
        			$q1->whereHas('role.user_type',function($q2){
        				$q2->where('slug','super-admin');
        			});
        		});
        	})
        	->get();

        }else if($current_user_type=='service-provider'){

    		$contracts=Contract::where('service_provider_id',$current_user->id)->get();
    		$contract_ids_array=$contracts->pluck('id')->toArray();

    		
    		$property_manager_ids=Contract::with('property')->whereIn('id',$contract_ids_array)->whereHas('property',function($q){
    			$q->whereNotNull('property_manager');
    		})->get()->pluck('property.property_manager')->toArray();


    		$property_owner_ids=Contract::with('property')->whereIn('id',$contract_ids_array)->get()->pluck('property.property_owner')->toArray();

    		$service_provider_ids=Contract::whereIn('id',$contract_ids_array)->get()->pluck('service_provider_id')->toArray();




    		$users_ids=array_unique(array_merge($property_manager_ids,$property_owner_ids,$service_provider_ids));
    		

        	$users=User::whereHas('role.user_type')->with('role.user_type')->where('id','!=',$current_user->id)
        	->where(function($q) use($users_ids,$current_user){
        		$q->whereIn('id',$users_ids)
                ->orWhere('created_by',$current_user->id)
        		->orWhere(function($q1){
        			$q1->whereHas('role.user_type',function($q2){
        				$q2->where('slug','super-admin');
        			});
        		});
        	})
        	->get();


        }else if($current_user_type=='labour'){
        	//labour can only send message to his service provider
        	$users=User::whereHas('role.user_type')->with('role.user_type')->where('id','!=',$current_user->id)->where('id',$current_user->created_by)->get();

        }else{
        	$users=collect();
        }
        
        $this->data['users']=$users;
        $this->data['upload_unique_key']=Str::random(200);
        return view($this->view_path.'.compose',$this->data);
    }

    /************************************************************************/
    # Function to store message                                              #
    # Function name    : list                                                #
    # Created Date     : 02-12-2020                                          #
    # Modified date    : 02-12-2020                                          #
    # Purpose          : To store message                                    #

    public function store(Request $request){
        $request->validate([
        	'message_to'=>'required',
        	'subject'=>'required|max:100',
        	'message'=>'required'
        ]);

        $current_user=auth()->guard('admin')->user();
        $message=Message::create([
        	'message_from'=>$current_user->id,
        	'message_to'=>$request->message_to,
        	'subject'=>$request->subject,
        	'message'=>$request->message
        ]);

        $upload_unique_key=$request->upload_unique_key;
        MessageAttachment::where('upload_unique_key',$upload_unique_key)->update([
            'upload_unique_key'=>'',
            'message_id'=>$message->id
        ]);



        $user_type=$current_user->role->user_type;

        if($user_type->slug=='property-owner'){
            if($current_user->created_by_admin){
                $user_type_name=$user_type->name;
            }else{
                $user_type_name='Property Manager';
            }
        }else{
            $user_type_name=$user_type->name;
        }

        $notification_message='New message from '.$current_user->name.'('.$user_type_name.')';
        $redirect_path=route('admin.messages.details',['message_id'=>$message->id],false);
        
        Notification::create([
            'notificable_id'=>$message->id,
            'notificable_type'=>'App\Models\Message',
            'user_id'=>$request->message_to,
            'message'=>$notification_message,
            'redirect_path'=>$redirect_path,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);


        return redirect()->route('admin.messages.list')->with('success','Message successfully sent.');

    }

    /************************************************************************/
    # Function to display message details                                    #
    # Function name    : list                                                #
    # Created Date     : 02-12-2020                                          #
    # Modified date    : 02-12-2020                                          #
    # Purpose          : To display message details                          #
	# Param            : message_id                                          #
    public function details($message_id){

        $this->data['page_title']='Message Details';
        $current_user=auth()->guard('admin')->user();
        $message=Message::findOrFail($message_id);

        if($message->message_to!= $current_user->id && $message->message_from!= $current_user->id){
        	return redirect()->route('admin.messages.list')->with('error','Message not belongs to you.');
        }

        if($message->message_to== $current_user->id){
	        $message->update([
	        	'is_read'=>true
	        ]);
        }

        $this->data['message']=$message;
        return view($this->view_path.'.details',$this->data);

    }

    /************************************************************************/
    # Function to upload message attachments                                 #
    # Function name    : upload_attachments                                  #
    # Created Date     : 17-12-2020                                          #
    # Modified date    : 17-12-2020                                          #
    # Purpose          : To upload message attachments                       #

    public function upload_attachments(Request $request){

        $attachments=[];

    
        if($request->file('attachments') && count($request->file('attachments'))){
            
            foreach ($request->file('attachments')  as $key=>$file) {

              
                //upload new file
                $file_name = time().$key.'.'.$file->getClientOriginalName();

                $destinationPath = public_path('/uploads/message_attachments');
             
                $file->move($destinationPath, $file_name);
                $mime_type=$file->getClientMimeType();

                $file_type=Helper::get_file_type_by_mime_type($mime_type);

                $attachment=MessageAttachment::create([
                    'upload_unique_key'=>$request->upload_unique_key,
                    'file_name'=>$file_name,
                    'file_type'=>$file_type,
                ]);

                $attachments[]=$attachment;
            }
        }

        return response()->json($attachments);

    }

    /************************************************************************/
    # Function to remove message file                                        #
    # Function name    : remove_attachment                                   #
    # Created Date     : 17-12-2020                                          #
    # Modified date    : 17-12-2020                                          #
    # Purpose          : to remove message file                              #
    # Param            : attachment_id                                       #
    public function remove_attachment($attachment_id,Request $request){
        
        $attachment=MessageAttachment::find($attachment_id);

        if($attachment){
            $upload_unique_key=$request->upload_unique_key;
            if($attachment->upload_unique_key!=$upload_unique_key){
                return response()->json(['message'=>'Invalid attachment.'],400);
            }else{

                $file_path=public_path().'/uploads/message_attachments/'.$attachment->file_name;
                if(File::exists($file_path)){
                    File::delete($file_path);
                }
                $attachment->delete();
                return response()->json(['message'=>'Attachment removed.'],200);
            }
        }else{
            return response()->json(['message'=>'Attachment not found.'],400);
        }
    }

    /************************************************************************/
    # Function to download message attachment                                #
    # Function name    : download_attachment                                 #
    # Created Date     : 17-12-2020                                          #
    # Modified date    : 17-12-2020                                          #
    # Purpose          : to download message attachment                      #
    # Param            : attachment_id                                       #
    public function download_attachment($attachment_id){
        
        $attachment=MessageAttachment::find($attachment_id);

        if($attachment){
         $file_path=public_path().'/uploads/message_attachments/'.$attachment->file_name;
         return response()->download($file_path,$attachment->file_name);
        }else{
            return response()->json(['message'=>'Attachment not found.'],400);
        }
    }

}
