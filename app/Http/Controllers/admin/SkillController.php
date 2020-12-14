<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Skill\{CreateSkillRequest,EditSkillRequest};
use App\Models\{User,Skills, Role};
use Carbon\Carbon;
class SkillController extends Controller
{
    private $view_path='admin.skill_management';
    private $data=[];

  

    public function list(Request $request){
        $this->data['page_title']='Skill List';

        if($request->ajax()){
            $skill=Skills::select('skills.*')->with('role');
            return Datatables::of($skill)
            ->editColumn('created_at', function ($skill) {
                return $skill->created_at ? with(new Carbon($skill->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('skill_title', function ($query, $keyword) {
                $query->whereTranslationLike('skill_title', "%{$keyword}%");
            })
            ->orderColumn('skill_title', function ($query, $order) {
                 $query->orderByTranslation('skill_title',$order);
            })

            
            ->addColumn('status',function($skill){

                
                if($skill->status){
                   $message='deactivate';
                   return '<a title="Click to deactivate the skill" href="javascript:change_status('."'".route('admin.skills.change_status',$skill->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm " >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the skill" href="javascript:change_status('."'".route('admin.skills.change_status',$skill->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm ">Inactive</a>';
                }
            })
            ->addColumn('action',function($skill){
                $delete_url=route('admin.skills.delete',$skill->id);
                $details_url=route('admin.skills.show',$skill->id);
                $edit_url=route('admin.skills.edit',$skill->id);

                $action_buttons='';
               
                //********Permission also need to check
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Skill" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }
                //********Permission also need to check
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Skill" href="javascript:delete_skill('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }

                return $action_buttons;
                
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        return view($this->view_path.'.list',$this->data);
    }


    public function create(){
        $this->data['page_title']='Create Skill';
        $this->data['user_role'] = Role::orderBy('role_name', 'Asc')->get();
        return view($this->view_path.'.add',$this->data);
    }

    public function store(CreateSkillRequest $request){
        $current_user=auth()->guard('admin')->user();

        $skill_data = [
            'en' => [
               'skill_title'=>$request->en_skill_title,
            ],
            'ar' => [
               'skill_title'=>$request->ar_skill_title,
            ],
            
            'role_id'=>$request->role_id,
            'created_by'=>$current_user->id,
            'updated_by'=>$current_user->id
        ];

        Skills::create($skill_data);

        return redirect()->route('admin.skills.list')->with('success','Skill successfully created.');
    }
   
    public function edit($id){
        $skills=Skills::findOrFail($id);
        $this->data['page_title']='Edit Skill';
        $this->data['skill']=$skills;
        $this->data['user_role'] = Role::orderBy('role_name', 'Asc')->get();
        return view($this->view_path.'.edit',$this->data);
    }
    public function update(EditSkillRequest $request,$id){
        $skill=Skills::findOrFail($id);
        $current_user=auth()->guard('admin')->user();

        $skill_data = [
            'en' => [
               'skill_title'=>$request->en_skill_title,
            ],
            'ar' => [
               'skill_title'=>$request->ar_skill_title,
            ],
            
            'role_id'=>$request->role_id,
            'updated_by'=>$current_user->id
        ];

        $skill->update($skill_data);

        return redirect()->route('admin.skills.list')->with('success','Skill successfully updated.');
    }
    public function delete($id){
        $skill=Skills::findOrFail($id);

        $skill->update([
            'status'=>0,
            'is_deleted'=>'Y',
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $skill->delete();
        return response()->json(['message'=>'Skill successfully deleted.']);
    }

    public function change_status($id){
        $skill=Skills::findOrFail($id);
        $change_status_to=($skill->status)?false:true;
        $message=($skill->status)?'deactivated':'activated';

         //updating gallery status
        $skill->update([
            'status'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Skill successfully '.$message.'.']);
    }


}
