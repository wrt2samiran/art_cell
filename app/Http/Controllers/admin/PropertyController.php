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
use App\Models\{Country,State,City,User,PropertyType,PropertyAttachment};
use App\Http\Requests\Admin\Property\{CreatePropertyRequest,UpdatePropertyRequest};
use Illuminate\Support\Str;
use File;
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

            $properties=Property::whereHas('city')->whereHas('property_type')->with('city')->select('properties.*');
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
        $this->data['property_managers']=User::whereHas('role',function($q){
        	$q->where('role_type','property-manager');
        })->whereStatus('A')->get();
        $this->data['property_owners']=User::whereHas('role',function($q){
        	$q->where('role_type','property-owner');
        })->whereStatus('A')->get();

        $this->data['property_types']=PropertyType::whereIsActive(true)->get();
        
        $days_array=[];
        for ($i=1; $i < 31; $i++) { 
            $days_array[]= $i;
        }
        $this->data['days_array']=$days_array;
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
    	$property=Property::create([
    		'code'=>$property_code,
    		'property_name'=>$request->property_name,
            'property_type_id'=>$request->property_type_id,
    		'description'=>$request->description,
    		'no_of_units'=>$request->no_of_units,
    		'country_id'=>$city->country_id,
    		'state_id'=>$city->state_id,
    		'city_id'=>$request->city_id,
    		'address'=>$request->address,
    		'location'=>$request->location,
    		'contact_number'=>$request->contact_number,
            'contact_email'=>$request->contact_email,
    		'property_owner'=>$request->property_owner,
    		'property_manager'=>$request->property_manager,
    		'electricity_account_day'=>$request->electricity_account_day,
    		'water_account_day'=>$request->water_account_day,
    		'created_by'=>auth()->guard('admin')->id(),
    		'updated_by'=>auth()->guard('admin')->id()
    	]);

       if($request->hasFile('property_files')){

            foreach ($request->file('property_files')  as $key=>$property_file) {
              
                    $file_name = 'property-file-'.time().$key.'.'.$property_file->getClientOriginalExtension();
                 
                    $destinationPath = public_path('/uploads/property_attachments');
                 
                    $property_file->move($destinationPath, $file_name);
                    
                    $mime_type=$property_file->getClientMimeType();

                    if(in_array($mime_type,['image/jpeg','image/png','image/jpg'])){
                        $file_type='image';
                    }elseif (in_array($mime_type,['application/pdf'])) {
                        $file_type='pdf';
                    }elseif (in_array($mime_type,['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword'])) {
                        $file_type='doc';
                    }elseif(in_array($mime_type,['text/plain'])){
                         $file_type='text';
                    }else{
                         $file_type='file';
                    }

                    PropertyAttachment::create([
                     'property_id'=>$id,
                     'file_name'=>$file_name,
                     'file_type'=>$file_type,
                     'created_by'=>auth()->guard('admin')->id()
                    ]);
            }
        }

    	return redirect()->route('admin.properties.list')->with('success','Property successfully created');

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
        $this->data['page_title']='Edit Property';
        $this->data['property']=$property;

        $this->data['cities']=City::whereHas('state')->whereHas('country')->whereIsActive(true)->get();
        $this->data['property_managers']=User::whereHas('role',function($q){
        	$q->where('role_type','property-manager');
        })->whereStatus('A')->get();
        $this->data['property_owners']=User::whereHas('role',function($q){
        	$q->where('role_type','property-owner');
        })->whereStatus('A')->get();
        
        $this->data['property_types']=PropertyType::whereIsActive(true)->get();

        $days_array=[];
        for ($i=1; $i < 31; $i++) { 
            $days_array[]= $i;
        }
        $this->data['days_array']=$days_array;
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

    	$city=City::find($request->city_id);
        if(!$city){
           return redirect()->back()->with('error','City not found.'); 
        }

    	$property->update([
            'property_name'=>$request->property_name,
            'property_type_id'=>$request->property_type_id,
            'description'=>$request->description,
            'no_of_units'=>$request->no_of_units,
            'country_id'=>$city->country_id,
            'state_id'=>$city->state_id,
            'city_id'=>$request->city_id,
            'address'=>$request->address,
            'location'=>$request->location,
            'contact_number'=>$request->contact_number,
            'contact_email'=>$request->contact_email,
            'property_owner'=>$request->property_owner,
            'property_manager'=>$request->property_manager,
            'electricity_account_day'=>$request->electricity_account_day,
            'water_account_day'=>$request->water_account_day,
            'updated_by'=>auth()->guard('admin')->id()
        ]);

        if($request->hasFile('property_files')){

            foreach ($request->file('property_files')  as $key=>$property_file) {
          
                $file_name = 'property-file-'.time().$key.'.'.$property_file->getClientOriginalExtension();
             
                $destinationPath = public_path('/uploads/property_attachments');
             
                $property_file->move($destinationPath, $file_name);
                
                $mime_type=$property_file->getClientMimeType();

                if(in_array($mime_type,['image/jpeg','image/png','image/jpg'])){
                    $file_type='image';
                }elseif (in_array($mime_type,['application/pdf'])) {
                    $file_type='pdf';
                }elseif (in_array($mime_type,['application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword'])) {
                    $file_type='doc';
                }elseif(in_array($mime_type,['text/plain'])){
                     $file_type='text';
                }else{
                     $file_type='file';
                }


                PropertyAttachment::create([
                 'property_id'=>$id,
                 'file_name'=>$file_name,
                 'file_type'=>$file_type,
                 'created_by'=>auth()->guard('admin')->id()
                ]);
            }
        }
        
        return redirect()->route('admin.properties.list')->with('success','Property successfully updated.');

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
