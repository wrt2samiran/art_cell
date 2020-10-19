<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\PropertyType\{CreatePropertyTypeRequest,EditPropertyTypeRequest};
use App\Models\{User,PropertyType};
use Carbon\Carbon;
class PropertyTypeController extends Controller
{
    private $view_path='admin.property_types';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Property Type List';

        if($request->ajax()){
            $property_types=PropertyType::select('property_types.*');
            return Datatables::of($property_types)
            ->editColumn('created_at', function ($property_type) {
                return $property_type->created_at ? with(new Carbon($property_type->created_at))->format('d/m/Y') : '';
            })
            ->editColumn('description', function ($property_type) {
                return Str::limit($property_type->description,50);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })

            ->filterColumn('type_name', function ($query, $keyword) {
                $query->whereTranslationLike('type_name', "%{$keyword}%");
            })
            ->orderColumn('type_name', function ($query, $order) {
                 $query->orderByTranslation('type_name',$order);
            })

            ->filterColumn('description', function ($query, $keyword) {
                $query->whereTranslationLike('description', "%{$keyword}%");
            })
            ->orderColumn('description', function ($query, $order) {
                 $query->orderByTranslation('description',$order);
            })
            ->addColumn('is_active',function($property_type){

                
                if($property_type->is_active){
                   $message='deactivate';
                   return '<a title="Click to deactivate the property type" href="javascript:change_status('."'".route('admin.property_types.change_status',$property_type->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm " >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the property type" href="javascript:change_status('."'".route('admin.property_types.change_status',$property_type->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm ">Inactive</a>';
                }
            })
            ->addColumn('action',function($property_type){
                $delete_url=route('admin.property_types.delete',$property_type->id);
                $details_url=route('admin.property_types.show',$property_type->id);
                $edit_url=route('admin.property_types.edit',$property_type->id);

                $action_buttons='';
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'<a title="View Role Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Role" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete role" href="javascript:delete_property_type('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }
                return $action_buttons;
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }

        return view($this->view_path.'.list',$this->data);
    }

    public function create(){
        $this->data['page_title']='Create Property Type';
        return view($this->view_path.'.create',$this->data);
    }

    public function store(CreatePropertyTypeRequest $request){
        $current_user=auth()->guard('admin')->user();

        $property_type_data = [
            'en' => [
               'type_name'=>$request->en_type_name,
               'description' =>$request->en_description,
            ],
            'ar' => [
               'type_name'=>$request->ar_type_name,
               'description' =>$request->ar_description,
            ],
            'slug'=>Str::slug($request->type_name),
            'created_by'=>$current_user->id,
            'updated_by'=>$current_user->id
        ];

        PropertyType::create($property_type_data);

        return redirect()->route('admin.property_types.list')->with('success','Property type successfully created.');
    }
    public function show($id){
        $property_type=PropertyType::findOrFail($id);
        $this->data['page_title']='Property Type Details';
        $this->data['property_type']=$property_type;
        return view($this->view_path.'.show',$this->data);
    }
    public function edit($id){
        $property_type=PropertyType::findOrFail($id);
        $this->data['page_title']='Edit Property Type';
        $this->data['property_type']=$property_type;
        return view($this->view_path.'.edit',$this->data);
    }
    public function update(Request $request,$id){
        $property_type=PropertyType::findOrFail($id);
        $current_user=auth()->guard('admin')->user();
       
        $property_type_data = [
            'en' => [
               'type_name'=>$request->en_type_name,
               'description' =>$request->en_description,
            ],
            'ar' => [
               'type_name'=>$request->ar_type_name,
               'description' =>$request->ar_description,
            ],
            'slug'=>Str::slug($request->type_name),
            'updated_by'=>$current_user->id
        ];
        $property_type->update($property_type_data);

        return redirect()->route('admin.property_types.list')->with('success','Property type successfully updated.');
    }
    public function delete($id){
        $property_type=PropertyType::findOrFail($id);

        $property_type->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $property_type->delete();
        return response()->json(['message'=>'Property type successfully deleted.']);
    }

    public function change_status($id){
        $property_type=PropertyType::findOrFail($id);
        $change_status_to=($property_type->is_active)?false:true;
        $message=($property_type->is_active)?'deactivated':'activated';

         //updating gallery status
        $property_type->update([
            'is_active'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Property type successfully '.$message.'.']);
    }

    public function ajax_check_type_name_unique(Request $request,$property_type_id=null){

        if($property_type_id){
             $property_type= PropertyType::where('id','!=',$property_type_id)
             ->whereTranslation('type_name',$request->type_name,$request->locale)->first();
        }else{
             $property_type= PropertyType::whereTranslation('type_name',$request->type_name,$request->locale)->first();
        }
     
        if($property_type){
            echo "false";
        }else{
            echo "true";
        }
    }

}
