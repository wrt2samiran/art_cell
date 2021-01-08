<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\{UnitMaster,SparePart};


class UnitController extends Controller
{

    private $view_path='admin.unit';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Unit List';
        if($request->ajax()){

            $sqlUnitMaster=UnitMaster::select('unit_masters.*');
            return Datatables::of($sqlUnitMaster)
            ->editColumn('created_at', function ($sqlUnitMaster) {
                return $sqlUnitMaster->created_at ? with(new Carbon($sqlUnitMaster->created_at))->format('d/m/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('unit_name', function ($query, $keyword) {
                $query->whereTranslationLike('unit_name', "%{$keyword}%");
            })
            ->orderColumn('unit_name', function ($query, $order) {
                 $query->orderByTranslation('unit_name',$order);
            })
            ->addColumn('is_active',function($sqlUnitMaster){

                if($sqlUnitMaster->is_active){
                   $message='deactivate';
                   return '<a title="Click to deactivate the unit" href="javascript:change_status('."'".route('admin.unit.change_status',$sqlUnitMaster->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm" >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the unit" href="javascript:change_status('."'".route('admin.unit.change_status',$sqlUnitMaster->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            
            ->addColumn('action',function($sqlUnitMaster){
                $delete_url=route('admin.unit.delete',$sqlUnitMaster->id);
                $edit_url=route('admin.unit.edit',$sqlUnitMaster->id);
                $action_buttons='';
                
            
            //need to check permissions later
            if(true){
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Unit" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
            }
            //need to check permissions later
            if(true){
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Unit" href="javascript:delete_user('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
            }

            return $action_buttons;

                
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);

        }


        return view($this->view_path.'.list',$this->data);
    }



    public function add(Request $request){

        $this->data['page_title']     = 'Create Unit';
        $logedin_user=auth()->guard('admin')->user();
    
        if($request->isMethod('POST'))
        {
            $request->validate([
                'en_unit_name'=>'required|min:2|max:100',
                'ar_unit_name'=>'required|min:2|max:100',
            ]);

        $unit_data = [
            'en' => [
               'unit_name'=>$request->en_unit_name,
            ],
            'ar' => [
               'unit_name'=>$request->ar_unit_name,
            ],
            'created_by'=>$logedin_user->id,
            'updated_by'=>$logedin_user->id
        ];

        UnitMaster::create($unit_data);
        return redirect()->route('admin.unit.list')->with('success','Unit successfully created.');

        }else{
           return view($this->view_path.'.create', $this->data); 
        }
    }

    

    public function edit($id){
        $unit=UnitMaster::findOrFail($id);
        
        $this->data['page_title']='Edit Unit';
        $this->data['unit']=$unit;
       
        return view($this->view_path.'.edit',$this->data);
    }


    public function update(Request $request,$id){
        $logedin_user=auth()->guard('admin')->user();
        $unit=UnitMaster::findOrFail($id);
        $unit_data = [
            'en' => [
               'unit_name'=>$request->en_unit_name,
            ],
            'ar' => [
               'unit_name'=>$request->ar_unit_name,
            ],
            'updated_by'=>$logedin_user->id
        ];

        $unit->update($unit_data);

        return redirect()->route('admin.unit.list')->with('success','Unit successfully updated.');

    }

    
    public function change_status($id = null)
    {
        $unit=UnitMaster::findOrFail($id);
        $change_status_to=($unit->is_active)?false:true;
        $message=($unit->is_active)?'deactivated':'activated';
         //updating unit status
        $unit->update([
            'is_active'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Unit successfully '.$message.'.']);
    }


    public function delete($id){
        $unit=UnitMaster::findOrFail($id);
        
        $spare_part_exist=SparePart::where('unit_master_id',$id)->first();
        if($spare_part_exist){
            return response()->json(['message'=>'You can not delete this unit because there are spare parts containing this unit'],400);
        }
        $unit->delete();
        return response()->json(['message'=>'Unit successfully deleted.']);

    }

    public function show($id){
        $user=UnitMaster::findOrFail($id);
        $this->data['page_title']='Unit Details';
        $this->data['user']=$user;
        return view($this->view_path.'.show',$this->data);

    }


    public function ajax_check_unit_name_unique(Request $request,$unit_master_id=null){

        if($unit_master_id){
             $unit= UnitMaster::where('id','!=',$unit_master_id)
             ->whereTranslation('unit_name',$request->unit_name,$request->locale)->first();
        }else{
             $unit= UnitMaster::whereTranslation('unit_name',$request->unit_name,$request->locale)->first();
        }
     
        if($unit){
            echo "false";
        }else{
            echo "true";
        }
    }

    
}
