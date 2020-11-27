<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\Models\{Complaint,Contract,ComplaintStatus,WorkOrderLists,ComplaintNote,ComplaintStatusUpdate};
use App\Http\Requests\Admin\Complaint\{CreateComplaintRequest,UpdateComplaintRequest};
use Illuminate\Support\Str;
use File;
class ComplaintController extends Controller
{
    //defining the view path
    private $view_path='admin.complaints';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for complaints list and datatable ajax response               #
    # Function name    : list                                                #
    # Created Date     : 20-11-2020                                          #
    # Modified date    : 20-11-2020                                          #
    # Purpose          : For complaints list and returning Datatables        #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Complaints List';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $complaints=Complaint::with(['contract','work_order','complaint_status'])
            ->whereHas('contract')
            ->where(function($query)use($current_user){
                if($current_user->role->slug!='super-admin'){
                    $query->whereHas('contract.property',function($sub_query)use($current_user) {
                        $sub_query->where('property_owner',$current_user->id)
                        ->orWhere('property_manager',$current_user->id);
                    });
                }
            })
            ->when($request->contract_id,function($query) use($request){
                $query->where('contract_id',$request->contract_id);
            })
            ->select('complaints.*');
            return Datatables::of($complaints)
            ->editColumn('created_at', function ($complaint) {
                return $complaint->created_at ? with(new Carbon($complaint->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('details', function ($complaint) {
                return Str::limit($complaint->details,100);
            })
            
            ->addColumn('work_order_title',function($complaint){
                return $complaint->work_order? Str::limit($complaint->work_order->task_title,100):'';
            })
            ->addColumn('action',function($complaint){
                $delete_url=route('admin.complaints.delete',$complaint->id);
                $details_url=route('admin.complaints.show',$complaint->id);
                $edit_url=route('admin.complaints.edit',$complaint->id);
                $action_buttons='';
 
                $action_buttons=$action_buttons.'<a title="View Complaint Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';


                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Complaint" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';

                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Complaint" href="javascript:delete_complaint('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
       

                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }

        $this->data['contracts']=Contract::whereHas('property',function($query)use($current_user){
            // if($current_user->role->slug!='super-admin'){
            //     $query->where('property_owner',$current_user->id)
            //     ->orWhere('property_manager',$current_user->id);
            // }
        })
        ->orderBy('id','DESC')->get();

        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function to load complaint create view page                            #
    # Function name    : create                                              #
    # Created Date     : 20-11-2020                                          #
    # Modified date    : 14-11-2020                                          #
    # Purpose          : To load complaint create view page                  #
    public function create(){
        $this->data['page_title']='Create Complaint';
        $current_user=auth()->guard('admin')->user();

        $this->data['contracts']=Contract::whereHas('property',function($query)use($current_user){
            $query->where('property_owner',$current_user->id)
            ->orWhere('property_manager',$current_user->id);
        })
        ->with(['work_orders'])
        ->orderBy('id','DESC')->get();

        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store complaint data                                               #
    # Function name    : store                                                       #
    # Created Date     : 20-11-2020                                                  #
    # Modified date    : 20-11-2020                                                  #
    # Purpose          : store complaint data                                        #
    # Param            : CreateContractRequest $request                              #

    public function store(CreateComplaintRequest $request){
        $current_user=auth()->guard('admin')->user();
        $default_status=ComplaintStatus::where('is_default',true)->first();
        if(!$default_status){
            return redirect()->back()->with('error','No default status found for complaint');
        }

        if($request->hasFile('file')){

            $file=$request->file('file');
            //upload new file
            $file_name = 'complaint-file-'.time().'.'.$file->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/complaint_files');
         
            $file->move($destinationPath, $file_name);
        }else{
            $file_name =null;
        }
        Complaint::create([
            'created_by'=>$current_user->id,
            'contract_id'=>$request->contract_id,
            'work_order_list_id'=>$request->work_order_id,
            'subject'=>$request->subject,
            'details'=>$request->details,
            'file'=>$file_name,
            'complaint_status_id'=>$default_status->id,
        ]);
        return redirect()->route('admin.complaints.list')->with('success','Complaint successfully posted.');
    }

    /********************************************************************************/
    # Function to show complaint details                                             #
    # Function name    : show                                                        #
    # Created Date     : 20-11-2020                                                  #
    # Modified date    : 20-11-2020                                                  #
    # Purpose          : show complaint details                                      #
    # Param            : $complaint_id                                               #
    public function show($complaint_id){
        $current_user=auth()->guard('admin')->user();
        $this->data['page_title']='Complaint Details';
        $this->data['complaint']=$complaint=Complaint::findOrFail($complaint_id);
        $this->data['notes']=ComplaintNote::where('complaint_id',$complaint_id)->orderBy('id','desc')->paginate(5);

        $this->data['complaint_status_updates']=ComplaintStatusUpdate::where('complaint_id',$complaint_id)->orderBy('id','DESC')->get();

        $this->data['complaint_statusses']=ComplaintStatus::get();

        return view($this->view_path.'.show',$this->data);
    }


    /********************************************************************************/
    # Function to add note to this complaint                                         #
    # Function name    : add_note                                                    #
    # Created Date     : 23-11-2020                                                  #
    # Modified date    : 23-11-2020                                                  #
    # Purpose          : add note to this complaint                                  #
    # Param            : complaint_id                                                #
    public function add_note($complaint_id,Request $request){
        $request->validate([
            'note'=>'required|max:1000'
        ]);

        $current_user=auth()->guard('admin')->user();
        $complaint=Complaint::findOrFail($complaint_id);

        if($request->hasFile('file')){

            $file=$request->file('file');
            //upload new file
            $file_name = 'complaint-note-file-'.time().'.'.$file->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/complaint_files');
         
            $file->move($destinationPath, $file_name);
        }else{
            $file_name =null;
        }

        ComplaintNote::create([
            'complaint_id'=>$complaint_id,
            'user_id'=>$current_user->id,
            'note'=>$request->note,
            'file'=>$file_name
        ]);

        return redirect()->back()->with('success','Note successfully added.');

    }

    /********************************************************************************/
    # Function to update note to this complaint                                      #
    # Function name    : update_note                                                 #
    # Created Date     : 23-11-2020                                                  #
    # Modified date    : 23-11-2020                                                  #
    # Purpose          : update note to this complaint                               #
    # Param            : complaint_id,note_id                                        #
    public function update_note($complaint_id,$note_id,Request $request){
        $request->validate([
            'note'=>'required|max:1000'
        ]);
        $current_user=auth()->guard('admin')->user();
        $complaint=Complaint::findOrFail($complaint_id);
        $complaint_note=ComplaintNote::findOrFail($note_id);

        if($request->hasFile('file')){

            if($complaint_note->file){
                //remove previous file if exists
                $old_file_path=public_path().'/uploads/complaint_files/'.$complaint_note->file;
                if(File::exists($old_file_path)){
                    File::delete($old_file_path);
                } 
            }

            $file=$request->file('file');
            //upload new file
            $file_name = 'complaint-note-file-'.time().'.'.$file->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/complaint_files');
         
            $file->move($destinationPath, $file_name);
        }else{
            $file_name =$complaint_note->file;
        }

        $complaint_note->update([
            'note'=>$request->note,
            'file'=>$file_name
        ]);

        return redirect()->back()->with('success','Note successfully added.');

    }

    /********************************************************************************/
    # Function to delete note to this complaint                                      #
    # Function name    : delete_note                                                 #
    # Created Date     : 23-11-2020                                                  #
    # Modified date    : 23-11-2020                                                  #
    # Purpose          : update note to this complaint                               #
    # Param            : complaint_id,note_id                                        #
    public function delete_note($complaint_id,$note_id,Request $request){
        $current_user=auth()->guard('admin')->user();
        $complaint=Complaint::findOrFail($complaint_id);
        $complaint_note=ComplaintNote::findOrFail($note_id);

        if($complaint_note->file){
            //remove previous file if exists
            $old_file_path=public_path().'/uploads/complaint_files/'.$complaint_note->file;
            if(File::exists($old_file_path)){
                File::delete($old_file_path);
            } 
        }

        $complaint_note->update([
            'deleted_at'=>$current_user->id
        ]);
        $complaint_note->delete();

        return response()->json(['message'=>'Note successfully deleted.']);

    }


    /********************************************************************************/
    # Function to update complaint status                                            #
    # Function name    : update_status                                               #
    # Created Date     : 23-11-2020                                                  #
    # Modified date    : 23-11-2020                                                  #
    # Purpose          : update complaint status                                     #
    # Param            : complaint_id                                                #
    public function update_status($complaint_id,Request $request){
        $current_user=auth()->guard('admin')->user();
        $complaint=Complaint::findOrFail($complaint_id);
        
        $complaint_status=ComplaintStatus::findOrFail($request->status);
        if($complaint->complaint_status_id!=$complaint_status->id){
           $complaint->update([
            'complaint_status_id'=>$complaint_status->id
           ]);
        }
        return redirect()->back()->with('success','Status successfully updated.');

    }

    /************************************************************************/
    # Function to load complaint edit view page                              #
    # Function name    : create                                              #
    # Created Date     : 20-11-2020                                          #
    # Modified date    : 14-11-2020                                          #
    # Purpose          : To load complaint edit view page                    #
    # Param            : $complaint_id                                       #
    public function edit($complaint_id){
        $current_user=auth()->guard('admin')->user();
        $this->data['page_title']='Edit Complaint';
        $this->data['complaint']=$complaint=Complaint::findOrFail($complaint_id);

        $this->data['contracts']=Contract::whereHas('property',function($query)use($current_user){
            $query->where('property_owner',$current_user->id)
            ->orWhere('property_manager',$current_user->id);
        })->orderBy('id','DESC')->get();
        
        $this->data['work_orders']=WorkOrderLists::where('contract_id',$complaint->contract_id)->get();

        return view($this->view_path.'.edit',$this->data);
    }

    /********************************************************************************/
    # Function to store complaint data                                               #
    # Function name    : store                                                       #
    # Created Date     : 20-11-2020                                                  #
    # Modified date    : 20-11-2020                                                  #
    # Purpose          : update complaint data                                       #
    # Param            : UpdateComplaintRequest $request, $complaint_id              #

    public function update(UpdateComplaintRequest $request,$complaint_id){

        $complaint=Complaint::findOrFail($complaint_id);
        if($request->hasFile('file')){

            if($complaint->file){
                //remove previous file if exists
                $old_file_path=public_path().'/uploads/complaint_files/'.$complaint->file;
                if(File::exists($old_file_path)){
                    File::delete($old_file_path);
                } 
            }
            $file=$request->file('file');
            //upload new file
            $file_name = 'complaint-file-'.time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/complaint_files');
            $file->move($destinationPath, $file_name);
        }else{
            $file_name =$complaint->file;
        }

        $complaint->update([
            'work_order_list_id'=>$request->work_order_id,
            'subject'=>$request->subject,
            'details'=>$request->details,
            'file'=>$file_name,
        ]);
        return redirect()->route('admin.complaints.list')->with('success','Complaint successfully posted.');
    }

    /************************************************************************/
    # Function to delete complaint                                           #
    # Function name    : delete                                              #
    # Created Date     : 20-11-2020                                          #
    # Modified date    : 20-11-2020                                          #
    # Purpose          : to delete complaint                                 #
    # Param            : complaint_id                                        #
    public function delete($complaint_id){
        $complaint=Complaint::findOrFail($complaint_id);
        $complaint->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $complaint->delete();
        return response()->json(['message'=>'Complaint successfully deleted.']);
    }


}
