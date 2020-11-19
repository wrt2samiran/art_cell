<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\Contract;
use App\Models\User;
use Auth, Validator;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class MessageController extends Controller
{

    private $view_path='admin.message';

    /*****************************************************/
    # MessageController
    # Function name : List
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Show Shared Service List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Message List';
        if($request->ajax()){

            $sqlMessage=Message::orderBy('id','ASC')->orderBy('id','DESC');
            return Datatables::of($sqlMessage)
            ->editColumn('created_at', function ($sqlMessage) {
                return $sqlMessage->created_at ? with(new Carbon($sqlMessage->created_at))->format('m/d/Y') : '';
            })
            // ->editColumn('role_description', function ($role) {
            //     return Str::limit($country->role_description,100);
            // })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })          
            ->addColumn('status',function($sqlMessage){
               

                if($sqlMessage->status=='A'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the Message" href="javascript:change_status('."'".route('admin.message.change_status',$sqlMessage->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the Message" href="javascript:change_status('."'".route('admin.message.change_status',$sqlMessage->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($sqlMessage){
                $details_url=route('admin.message.show',$sqlMessage->id);

                return '<a title="View Message Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                
            })
            ->rawColumns(['action','status'])
            ->make(true);

        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # MessageController
    # Function name : messageAdd
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Add new Shared Service
    # Params        : Request $request
    /*****************************************************/
    public function messageAdd(Request $request) {

        $data['page_title']     = 'Add Message';
        $current_user=auth()->guard('admin')->user();
    
        try
        {
            $userData = [];
            $contract = [];
            if ($current_user->role->user_type->slug == 'super-admin'){
                $userData = User::where('role_id','!=', '1')->get();
            } else{
                $contract = Contract::with(['property','customer','service_provider','property_manager','services','contract_status'])
                ->whereHas('property')
                ->whereHas('customer')
                ->whereHas('service_provider')
                ->whereHas('property_manager')
                ->whereHas('services')
                ->whereHas('contract_status')
                ->where(function($q) use ($current_user){
                    //if logged in user is the customer of the contract 
                    $q->where('customer_id',$current_user->id)
                    //OR if logged in user is the service_provider of the contract 
                    ->orWhere('service_provider_id',$current_user->id)
                    //OR if logged in user is the property manager of the contract 
                    ->orWhere('property_manager_id',$current_user->id);
                    
                })->get();
            }
            $data['userData'] = $userData;
            $data['contract'] = $contract;
            // $data['userData'] = User::whereHas('role',function($query){
            //         $query->whereIn('id', ['3','4']);
            // })->where('is_deleted','N')->get();

        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'name'         => 'required|min:2|max:255|unique:'.(new Message)->getTable().',name',
                    'description'  => 'required|min:2',
				);
				$validationMessages = array(
					'name.required'                => 'Please enter name',
					'name.min'                     => 'Name should be should be at least 2 characters',
                    'name.max'                     => 'Name should not be more than 255 characters',
                    'description.required'         => 'Please enter message',
                    'description.min'              => 'Message should be should be at least 2 characters',

				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.message.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new Message;
                    $new->name = trim($request->name, ' ');
                    $new->to_user = $request->user_id;
                    $new->from_user = $current_user->id;
                    $new->description  = $request->description;
                    $new->created_at = Carbon::now();
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Message has been added successfully');
						return redirect()->route('admin.message.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the state');
						return redirect()->back();
					}
				}
            }
			return view('admin.message.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.message.list')->with('error', $e->getMessage());
		}
    }

    
    /*****************************************************/
    # MessageController
    # Function name : change_status
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Change Message status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.message.list');
            }
            $details = Message::where('id', $id)->first();
            if ($details != null) {
                if ($details->status == 'A') {
                    
                    $details->status = 'I';
                    $details->save();
                        
                    $request->session()->flash('alert-success', 'Status updated successfully');                 
                     } else if ($details->status == 'I') {
                    $details->status = 'A';
                    $details->save();
                    $request->session()->flash('alert-success', 'Status updated successfully');
                   
                } else {
                    $request->session()->flash('alert-danger', 'Something went wrong');
                    
                }
                return redirect()->back();
            } else {
                return redirect()->route('admin.message.list')->with('error', 'Invalid Message');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.message.list')->with('error', $e->getMessage());
        }
    }

    
    
    /*****************************************************/
    # MessageController
    # Function name : show
    # Author        :
    # Created Date  : 09-10-2020
    # Purpose       : Showing Message details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $messageSql=Message::findOrFail($id);
        $this->data['page_title']='Message Details';
        $this->data['messages']=$messageSql;
        return view($this->view_path.'.show',$this->data);
    }

    public function getUser(Request $request)
    {
      
        $userIds = explode(' ', $request->userId);
        $contractUser = User::whereIn('id', $userIds)->with('role')->get();
        return response()->json(['status'=>true, 'contractUser'=>$contractUser],200);
    }
}
