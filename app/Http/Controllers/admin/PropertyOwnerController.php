<?php
/*********************************************************/
# Class name     : PropertyOwnerController                #
# Methods  :                                              #
#    1. list ,                                            #
#    2. create,                                           #
#    3. store                                             #
#    4. show                                              #
#    5. edit                                              #
#    6. update                                            #
#    7. delete                                            #
#    8. change_status                                     #
# Created Date   : 09-10-2020                             #
# Purpose        : Property owner management              #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{User,Role};
use Carbon\Carbon;
use App\Http\Requests\Admin\PropertyOwner\{CreatePropertyOwnerRequest,UpdatePropertyOwnerRequest};
use App\Events\User\UserCreated;
class PropertyOwnerController extends Controller
{
    //defining the view path
    private $view_path='admin.property_owners';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for property owner list and datatable ajax response           #
    # Function name    : list                                                #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : For property owner list and returning Datatables    #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Property Owner List';
        if($request->ajax()){

            $property_owners=User::with(['role'])
            ->whereHas('role',function($q){
            	$q->where('slug','property-owner');
            })
            ->whereNull('deleted_at')
            ->select('users.*');
            return Datatables::of($property_owners)
            ->editColumn('created_at', function ($property_owner) {
                return $property_owner->created_at ? with(new Carbon($property_owner->created_at))->format('m/d/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('status',function($property_owner){

                $disabled='';
                if($property_owner->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the property owner" href="javascript:change_status('."'".route('admin.property_owners.change_status',$property_owner->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the property owner" href="javascript:change_status('."'".route('admin.property_owners.change_status',$property_owner->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })
            ->addColumn('action',function($property_owner){
                $delete_url=route('admin.property_owners.delete',$property_owner->id);
                $details_url=route('admin.property_owners.show',$property_owner->id);
                $edit_url=route('admin.property_owners.edit',$property_owner->id);
                $action_buttons='';
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'<a title="View Proprty Owner Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }

                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Property Owner" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Property Owner" href="javascript:delete_property_owner('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }
                return $action_buttons;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }     
        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function to load property owner create view page                       #
    # Function name    : create                                              #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : To load property owner  create view page            #
    public function create(){
        $this->data['page_title']='Create Property Owner ';
        $roles=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','property-owner')->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store property owner data                                        #
    # Function name    : store                                                       #
    # Created Date     : 09-10-2020                                                  #
    # Modified date    : 09-10-2020                                                  #
    # Purpose          : store property owner data                                 #
    # Param            : CreatePropertyOwnerRequest $request                                  #

    public function store(CreatePropertyOwnerRequest $request){

        $property_owner_role=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','property-owner')->first();

    	$user=User::create([
    		'first_name'=>$request->first_name,
    		'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
    		'email'=>$request->email,
            'password'=>$request->password,
            'phone'=>$request->phone,
            'role_id'=>$property_owner_role->id,
            'status'=>'A',
            'created_form'=>'B',
            'created_by'=>auth()->guard('admin')->id(),
            'updated_by'=>auth()->guard('admin')->id()
    	]);
        $user->load('role');
        event(new UserCreated($user,$request->password));

        return redirect()->route('admin.property_owners.list')->with('success','Property owner successfully created.');


    }

    /************************************************************************/
    # Function to show/load details page for property owner                #
    # Function name    : show                                                #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : show/load details page for property owner         #
    # Param            : id                                                  #

    public function show($id){
        $property_owner=User::findOrFail($id);
        $this->data['page_title']='Property Owner Details';
        $this->data['property_owner']=$property_owner;
        return view($this->view_path.'.show',$this->data);

    }

    /************************************************************************/
    # Function to load property owner edit page                              #
    # Function name    : edit                                                #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : to load property owner edit page                    #
    # Param            : id                                                  #
    public function edit($id){
        $property_owner=User::findOrFail($id);
        $this->data['page_title']='Edit Property Owner';
        $this->data['property_owner']=$property_owner;
        $roles=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','property-owner')->orderBy('id','ASC')->get();
        $this->data['roles']=$roles;
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update property owner data                                             #
    # Function name    : update                                                          #
    # Created Date     : 09-10-2020                                                      #
    # Modified date    : 09-10-2020                                                      #
    # Purpose          : to update property owner data                                   #
    # Param            : UpdatePropertyOwnerRequest $request,id                          #
    public function update(UpdatePropertyOwnerRequest $request,$id){

        $property_owner_role=Role::whereStatus('A')->whereNull('parrent_id')->where('slug','property-owner')->first();
        $property_owner=User::findOrFail($id);
        $update_data=[
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'name'=>$request->first_name.' '.$request->last_name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'role_id'=>$property_owner_role->id,
            'updated_by'=>auth()->guard('admin')->id()
        ];

        if($request->password){
            $update_data['password']=$request->password;
        }

        $property_owner->update($update_data);

        

        return redirect()->route('admin.property_owners.list')->with('success','Property owner successfully updated.');

    }

    /************************************************************************/
    # Function to delete property owner                                      #
    # Function name    : delete                                              #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : to delete property owner                            #
    # Param            : id                                                  #
    public function delete($id){
        $user=User::findOrFail($id);
        $user->update([
            'email'=>$user->email.'(deleted at-'.Carbon::now().')',
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $user->delete();
        return response()->json(['message'=>'Property owner successfully deleted.']);

  
    }

    /************************************************************************/
    # Function to change status of property owner                            #
    # Function name    : change_status                                       #
    # Created Date     : 09-10-2020                                          #
    # Modified date    : 09-10-2020                                          #
    # Purpose          : to change status of property owner                  #
    # Param            : id                                                  #
    public function change_status($id){
        $property_owner=User::findOrFail($id);
        $change_status_to=($property_owner->status=='A')?'I':'A';
        $message=($property_owner->status=='A')?'deactivated':'activated';
         //updating property owner status
        $property_owner->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Property owner successfully '.$message.'.']);
    }


}
