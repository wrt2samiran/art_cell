<?php
/*********************************************************/
# Class name     : PropertyController                     #
# Methods  :                                              #
#    1. list ,                                            #
#    2. create,                                           #
#    3. store                                             #
#    4. show                                              #
#    5. edit                                              #
#    6. update                                            #
#    7. delete                                            #
#    8. change_status                                     #
#    9. download_attachment                               #
#    10. delete_attachment_through_ajax                   #
# Created Date   : 12-10-2020                             #
# Purpose        : Property management                    #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Carbon;
use Yajra\Datatables\Datatables;
use App\Models\{Country,State,City,User,PropertyType,PropertyAttachment,Notification};
use App\Http\Requests\Admin\Property\{CreatePropertyRequest,UpdatePropertyRequest};
use Illuminate\Support\Str;
use File,Auth,Helper;

use App\Events\Property\PropertyCreated;
use App\Mail\Admin\Property\PropertyCreationMailToOwner;
use Mail;
class PropertyController extends Controller
{


    //defining the view path
    private $view_path='admin.properties';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for property list and datatable ajax response                 #
    # Function name    : list                                                #
    # Created Date     : 12-10-2020                                          #
    # Modified date    : 12-10-2020                                          #
    # Purpose          : For property list and returning Datatables          #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Property List';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $properties=Property::whereHas('city')
            ->where(function($q)use ($current_user){
                //if logged in user is not super admin then fetch only those properties which are crated by logged in user
                if($current_user->role->user_type->slug!='super-admin'){
                    if($current_user->created_by_admin){
                        $q->where('property_owner',$current_user->id);
                    }else{
                        $q->where('property_manager',$current_user->id);
                    }
                }
            })
            ->when($request->city_id,function($query) use($request){
            	$query->where('city_id',$request->city_id);
            })
            ->when($request->property_name,function($query) use($request){
            	$query->where('property_name',$request->property_name);
            })
            
            ->whereHas('property_type')->with('city')->select('properties.*');
            return Datatables::of($properties)
            ->editColumn('created_at', function ($property) {
                return $property->created_at ? with(new Carbon($property->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($property)use ($current_user){

                $disabled=(!$current_user->hasAllPermission(['property-status-change']))?'disabled':'';
                if($property->is_active){
                   $message='deactivate';
                   return '<a title="Click to deactivate the property" href="javascript:change_status('."'".route('admin.properties.change_status',$property->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the property" href="javascript:change_status('."'".route('admin.properties.change_status',$property->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })
            ->addColumn('action',function($property)use ($current_user){
                $delete_url=route('admin.properties.delete',$property->id);
                $details_url=route('admin.properties.show',$property->id);
                $edit_url=route('admin.properties.edit',$property->id);
                $action_buttons='';

                $has_details_permission=($current_user->hasAllPermission(['property-details']))?true:false;
                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Proprty Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }
                $has_edit_permission=($current_user->hasAllPermission(['property-edit']))?true:false;
                if($has_edit_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Property" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }

                $has_delete_permission=($current_user->hasAllPermission(['property-delete']))?true:false;
                if($has_delete_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Property" href="javascript:delete_property('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }

                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }
        $this->data['propertyCity']=City::whereIsActive('1')->get();
        $this->data['propertyName']=Property::whereIsActive('1')->get();   
        return view($this->view_path.'.list',$this->data);
    }


    /************************************************************************/
    # Function to load property create view page                             #
    # Function name    : create                                              #
    # Created Date     : 12-10-2020                                          #
    # Modified date    : 12-10-2020                                          #
    # Purpose          : To load property create view page                   #
    public function create(){
        $this->data['page_title']='Create Property';
        $this->data['cities']=City::whereHas('state')->whereHas('country')->whereIsActive(true)->get();
        $current_user=auth()->guard('admin')->user();

        //property manager can add when property owner logged in and he can add a user created by himself as a property manager
        //property manager is also user type=property-owner and not created by admin
        $this->data['property_managers']=User::whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','property-owner');
        })
        ->where('created_by_admin',false)
        ->where('created_by',$current_user->id)
        ->whereStatus('A')->get();

        $this->data['property_owners']=User::whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','property-owner');
        })
        ->where('created_by_admin',true)
        ->whereStatus('A')->get();

        $this->data['property_types']=PropertyType::whereIsActive(true)->get();
        
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store property data                                                #
    # Function name    : store                                                       #
    # Created Date     : 12-10-2020                                                  #
    # Modified date    : 12-10-2020                                                  #
    # Purpose          : store property data                                         #
    # Param            : CreatePropertyRequest $request                              #

    public function store(CreatePropertyRequest $request){

    	$city=City::find($request->city_id);
        if(!$city){
           return redirect()->back()->with('error','City not found.'); 
        }
    	//system generated property code
    	$property_code='PROP'.Carbon::now()->timestamp;

        //if logged in user type super-admin then property owner will come through form else property owner will be logged in user (i.e property owner creating property);
        $current_user=auth()->guard('admin')->user();

        if($current_user->role->user_type->slug == 'property-owner'){
            $property_owner=$current_user->id;
        }else{
            $property_owner=$request->property_owner;
        }

    	$property=Property::create([
    		'code'=>$property_code,
    		'property_name'=>$request->property_name,
            'property_type_id'=>$request->property_type_id,
    		'description'=>$request->description,
            'no_of_active_units'=>$request->no_of_active_units,
            'no_of_inactive_units'=>$request->no_of_inactive_units,
    		'country_id'=>$city->country_id,
    		'state_id'=>$city->state_id,
    		'city_id'=>$request->city_id,
    		'address'=>$request->address,
    		'location'=>$request->location,
    		'contact_number'=>$request->contact_number,
            'contact_email'=>$request->contact_email,
            'property_owner'=>$property_owner,
            'property_manager'=>$request->property_manager,
    		'electricity_account_number'=>$request->electricity_account_number,
    		'water_account_number'=>$request->water_account_number,
    		'created_by'=>auth()->guard('admin')->id(),
    		'updated_by'=>auth()->guard('admin')->id()
    	]);
        
        if(isset($request->title) && count($request->title)){
            foreach ($request->title  as $key=>$title) {
               
                    if($request->hasFile('property_files') && isset($request->file('property_files')[$key])){

                        $file=$request->file('property_files')[$key];
                        //upload new file
                        $file_name = 'property-file-'.time().$key.'.'.$file->getClientOriginalExtension();
     
                        $destinationPath = public_path('/uploads/property_attachments');
                     
                        $file->move($destinationPath, $file_name);
                        $mime_type=$file->getClientMimeType();

                        $file_type=Helper::get_file_type_by_mime_type($mime_type);

                        PropertyAttachment::create([
                            'property_id'=>$property->id,
                            'file_name'=>$file_name,
                            'file_type'=>$file_type,
                            'title'=>$title,
                            'created_by'=>$current_user->id
                        ]);

                    }


            }
        }



        $data=[
            'user'=>$property->owner_details,
            'property'=>$property,
            'from_name'=>env('MAIL_FROM_NAME','SMMS'),
            'from_email'=>env('MAIL_FROM_ADDRESS'),
            'subject'=>'New Property Created'
        ];
        Mail::to($property->owner_details->email)->send(new PropertyCreationMailToOwner($data)); 


        event(new PropertyCreated($property));

    	return redirect()->route('admin.properties.list')->with('success',__('property_manage_module.create_success_message'));

    }

    /************************************************************************/
    # Function to show/load details page for property                        #
    # Function name    : show                                                #
    # Created Date     : 12-10-2020                                          #
    # Modified date    : 12-10-2020                                          #
    # Purpose          : show/load details page for property                 #
    # Param            : id                                                  #

    public function show($id){
        $property=Property::findOrFail($id);
        //policy is defined in App\Policies\PropertyPolicy
        $this->authorize('view',$property);
        $this->data['page_title']='Property Details';
        $this->data['property']=$property;
        return view($this->view_path.'.show',$this->data);

    }

    /************************************************************************/
    # Function to load property edit page                                    #
    # Function name    : edit                                                #
    # Created Date     : 12-10-2020                                          #
    # Modified date    : 12-10-2020                                          #
    # Purpose          : to load property edit page                          #
    # Param            : id                                                  #
    public function edit($id){

        $property=Property::findOrFail($id);
        //policy is defined in App\Policies\PropertyPolicy
        $this->authorize('update',$property);
        $this->data['page_title']='Edit Property';
        $this->data['property']=$property;
        $current_user=auth()->guard('admin')->user();
        $this->data['cities']=City::whereHas('state')->whereHas('country')->whereIsActive(true)->get();

        //property manager can add when property owner logged in and he can add a user created by himself as a property manager
        //property manager is also user type=property-owner and not created by admin
        $this->data['property_managers']=User::whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','property-owner');
        })
        ->where('created_by_admin',false)
        ->where('created_by',$current_user->id)
        ->whereStatus('A')->get();

        $this->data['property_owners']=User::whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','property-owner');
        })
        ->where('created_by_admin',true)
        ->whereStatus('A')->get();
        
        $this->data['property_types']=PropertyType::whereIsActive(true)->get();

        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update property data                                                   #
    # Function name    : update                                                          #
    # Created Date     : 12-10-2020                                                      #
    # Modified date    : 12-10-2020                                                      #
    # Purpose          : to update property data                                         #
    # Param            : UpdatePropertyRequest $request,id                               #
    public function update(UpdatePropertyRequest $request,$id){

    	$property=Property::findOrFail($id);
        $current_user=auth()->guard('admin')->user();
        //policy is defined in App\Policies\PropertyPolicy
        $this->authorize('update',$property);

    	$city=City::find($request->city_id);
        if(!$city){
           return redirect()->back()->with('error','City not found.'); 
        }

        if($current_user->role->user_type->slug == 'property-owner'){
            $property_owner=$current_user->id;
        }else{
            $property_owner=$request->property_owner;
        }

        if($current_user->role->user_type->slug == 'property-owner' && $current_user->created_by_admin){
            $property_manager=$request->property_manager;
        }else{
            $property_manager=$property->property_manager;
        }


        if($request->property_manager && $property->property_manager!=$request->property_manager){

            $property_manager_updated=true;
        }else{
            $property_manager_updated=false;
        }
        
    	$property->update([
            'property_name'=>$request->property_name,
            'property_type_id'=>$request->property_type_id,
            'description'=>$request->description,
            'no_of_active_units'=>$request->no_of_active_units,
            'no_of_inactive_units'=>$request->no_of_inactive_units,
            'country_id'=>$city->country_id,
            'state_id'=>$city->state_id,
            'city_id'=>$request->city_id,
            'address'=>$request->address,
            'location'=>$request->location,
            'contact_number'=>$request->contact_number,
            'contact_email'=>$request->contact_email,
            'property_owner'=>$property_owner,
            'property_manager'=>$property_manager,
            'electricity_account_number'=>$request->electricity_account_number,
            'water_account_number'=>$request->water_account_number,
            'updated_by'=>auth()->guard('admin')->id()
        ]);

        if(isset($request->title) && count($request->title)){
            foreach ($request->title as $key => $value) {

                if($request->file_id[$key]!=''){
                     
                    $property_file=PropertyAttachment::find($request->file_id[$key]);

                }else{
                    $property_file=null;
                }
                
                if($property_file){

                    //if image is updating then remove previous image and upload ne one
                    if($request->hasFile('property_files') && isset($request->file('property_files')[$key])){

                        $file=$request->file('property_files')[$key];
                        //remove previous file if exists
                        $file_path=public_path().'/uploads/property_attachments/'.$property_file->file_name;
                        if(File::exists($file_path)){
                            File::delete($file_path);
                        }
                        //upload new file
                        $file_name = 'property-file-'.time().$key.'.'.$file->getClientOriginalExtension();
     
                        $destinationPath = public_path('/uploads/property_attachments');
                     
                        $file->move($destinationPath, $file_name);
                        $mime_type=$file->getClientMimeType();

                        $file_type=Helper::get_file_type_by_mime_type($mime_type);

                    }else{
                        $file_name=$property_file->file_name;
                        $file_type=$property_file->file_type;
                    }

                    $property_file->update([
                     'file_name'=>$file_name,
                     'file_type'=>$file_type,
                     'title'=>$request->title[$key],
                     'created_by'=>$current_user->id
                    ]);

                }else{
                    //if it is new file
                    if($request->hasFile('property_files') && isset($request->file('property_files')[$key])){

                        $file=$request->file('property_files')[$key];
                        //upload new file
                        $file_name = 'property-file-'.time().$key.'.'.$file->getClientOriginalExtension();
     
                        $destinationPath = public_path('/uploads/property_attachments');
                     
                        $file->move($destinationPath, $file_name);
                        $mime_type=$file->getClientMimeType();

                        $file_type=Helper::get_file_type_by_mime_type($mime_type);

                        PropertyAttachment::create([
                            'property_id'=>$property->id,
                            'file_name'=>$file_name,
                            'file_type'=>$file_type,
                            'title'=>$request->title[$key],
                            'created_by'=>$current_user->id
                        ]);

                    }


                }


            }
        }


        if($property_manager_updated){
            $notification_data=[];
            //if property owner added a manager to the property then send notification to the manager
            if($property->property_manager){

                $property_manager_user=User::find($property->property_manager);
                if($property_manager_user && $property_manager_user->hasAnyPermission(['property-details','users-property-details'])){

                        $notification_message=$property->owner_details->name.' added you as a property manager.';

                        if($property_manager_user->hasAllPermission(['property-details'])){
                            $redirect_path=route('admin.properties.show',['id'=>$property->id],false);
                        }else{
                            $redirect_path=route('admin.user_properties.show',['id'=>$property->id],false);
                        }
                        
                        $notification_data[]=[
                            'notificable_id'=>$property->id,
                            'notificable_type'=>'App\Models\Property',
                            'user_id'=>$property->property_manager,
                            'message'=>$notification_message,
                            'redirect_path'=>$redirect_path,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ];

                }

            }

            if(count($notification_data)){
            Notification::insert($notification_data);
            }
        }

        
        return redirect()->route('admin.properties.list')->with('success',__('property_manage_module.edit_success_message'));

    }

    /************************************************************************/
    # Function to delete property                                            #
    # Function name    : delete                                              #
    # Created Date     : 12-10-2020                                          #
    # Modified date    : 12-10-2020                                          #
    # Purpose          : to delete property                                  #
    # Param            : id                                                  #
    public function delete($id){
        $property=Property::findOrFail($id);
        $property->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $property->delete();
        return response()->json(['message'=>'Property successfully deleted.']);

    }

    /************************************************************************/
    # Function to change status of property                                  #
    # Function name    : change_status                                       #
    # Created Date     : 12-10-2020                                          #
    # Modified date    : 12-10-2020                                          #
    # Purpose          : to change status of property                        #
    # Param            : id                                                  #
    public function change_status($id){
        $property=Property::findOrFail($id);
        $change_status_to=($property->is_active)?false:true;
        $message=($property->is_active)?'deactivated':'activated';
         //updating property  status
        $property->update([
            'is_active'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Property successfully '.$message.'.']);
    }

    /************************************************************************/
    # Function to download attachment by file id                             #
    # Function name    : download_attachment                                 #
    # Created Date     : 12-10-2020                                          #
    # Modified date    : 12-10-2020                                          #
    # Purpose          : to download attachment by file id                   #
    # Param            : id                                                  #
    public function download_attachment($id){
        $file_data=PropertyAttachment::findOrFail($id);
        $file_path=public_path().'/uploads/property_attachments/'.$file_data->file_name;
        return response()->download($file_path,$file_data->file_name);
    }


    /************************************************************************/
    # Function to delete attachment by file id                               #
    # Function name    : delete_attachment_through_ajax                      #
    # Created Date     : 16-10-2020                                          #
    # Modified date    : 16-10-2020                                          #
    # Purpose          : to delete attachment by file id                     #
    # Param            : id                                                  #
    public function delete_attachment_through_ajax($id){
        $file_data=PropertyAttachment::findOrFail($id);
        $file_path=public_path().'/uploads/property_attachments/'.$file_data->file_name;
        if(File::exists($file_path)){
            File::delete($file_path);
        }
        $file_data->delete();
        return response()->json(['message'=>'Attachement file successfully deleted']);
    }



}
