<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{Status};
use Carbon\Carbon;
class StatusController extends Controller
{
 private $view_path='admin.statuses';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Status List';

        if($request->ajax()){
            $statuses=Status::select('statuses.*');
            return Datatables::of($statuses)
            ->editColumn('created_at', function ($status) {
                return $status->created_at ? with(new Carbon($status->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })

            ->filterColumn('status_name', function ($query, $keyword) {
                $query->whereTranslationLike('status_name', "%{$keyword}%");
            })
            ->orderColumn('status_name', function ($query, $order) {
                 $query->orderByTranslation('status_name',$order);
            })
            ->editColumn('color_code', function ($status) {
                return '<div>'.$status->color_code.'</div><div style="height:10px;width:100%;background:'.$status->color_code.'"></div>';
            })
            ->addColumn('is_active',function($status){
                if($status->is_active){
                   $message='deactivate';
                   return '<a title="Click to deactivate the property type" href="javascript:change_status('."'".route('admin.statuses.change_status',$status->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm " >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the property type" href="javascript:change_status('."'".route('admin.statuses.change_status',$status->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm ">Inactive</a>';
                }
            })
            ->addColumn('action',function($status){
                $delete_url=route('admin.statuses.delete',$status->id);
                $edit_url=route('admin.statuses.edit',$status->id);

                $action_buttons='';

                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit status" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete role" href="javascript:delete_status('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }
                return $action_buttons;
            })
            ->rawColumns(['action','is_active','color_code'])
            ->make(true);
        }

        return view($this->view_path.'.list',$this->data);
    }

    public function create(){
        $this->data['page_title']='Create Status';

        $this->data['status_for']=$status_for=Status::groupBy('status_for')->get();
        
        return view($this->view_path.'.create',$this->data);
    }

    public function store(Request $request){
        $request->validate([
            'en_status_name'=>'required|max:50',
            'ar_status_name'=>'required|max:50',
            'color_code'=>'required|max:255',
            'status_for'=>'required'
        ]);
        $current_user=auth()->guard('admin')->user();
        $status_data = [
            'en' => [
               'status_name'=>$request->en_status_name,
            ],
            'ar' => [
               'status_name'=>$request->ar_status_name,
            ],
            'slug'=>Str::slug($request->en_status_name),
            'color_code'=>$request->color_code,
            'status_for'=>$request->status_for,
            'created_by'=>$current_user->id,
            'updated_by'=>$current_user->id
        ];

        Status::create($status_data);
        return redirect()->route('admin.statuses.list')->with('success','Status successfully created.');
    }

    public function edit($id){
        $status=Status::findOrFail($id);
        $this->data['page_title']='Status Details';
        $this->data['status']=$status;
        $this->data['status_for']=$status_for=Status::groupBy('status_for')->get();
        return view($this->view_path.'.edit',$this->data);
    }
    public function update(Request $request,$id){
        $status=Status::findOrFail($id);
        $current_user=auth()->guard('admin')->user();
        $request->validate([
            'en_status_name'=>'required|max:50',
            'ar_status_name'=>'required|max:50',
            'color_code'=>'required|max:255'
        ]);
        $status_data = [
            'en' => [
               'status_name'=>$request->en_status_name,
            ],
            'ar' => [
               'status_name'=>$request->ar_status_name,
            ],
            'slug'=>Str::slug($request->en_status_name),
            'color_code'=>$request->color_code,
            'updated_by'=>$current_user->id
        ];
        $status->update($status_data);

        return redirect()->route('admin.statuses.list')->with('success','Status successfully updated.');
    }
    public function delete($id){
        $status=Status::findOrFail($id);
        $status->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $status->delete();
        return response()->json(['message'=>'Status successfully deleted.']);
    }

    public function change_status($id){
        $status=Status::findOrFail($id);
        $change_status_to=($status->is_active)?false:true;
        $message=($status->is_active)?'deactivated':'activated';

         //updating gallery status
        $status->update([
            'is_active'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Status successfully '.$message.'.']);
    }

    public function ajax_check_status_name_unique(Request $request,$status_id=null){

        if($status_id){
             $status= Status::where('id','!=',$status_id)
             ->when($request->status_for,function($q)use($request){
                $q->where('status_for',$request->status_for);
             })
             ->whereTranslation('status_name',$request->status_name,$request->locale)->first();
        }else{
             $status= Status::when($request->status_for,function($q)use($request){
                $q->where('status_for',$request->status_for);
             })
             ->whereTranslation('status_name',$request->status_name,$request->locale)->first();
        }
     
        if($status){
            echo "false";
        }else{
            echo "true";
        }
    }

}
