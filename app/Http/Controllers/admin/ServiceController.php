<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\Service\{CreateServiceRequest,EditServiceRequest};
use App\Models\{User,Service};
use Carbon\Carbon;
class ServiceController extends Controller
{
    private $view_path='admin.services';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Service List';

        if($request->ajax()){
            $services=Service::select('services.*');
            return Datatables::of($services)
            ->editColumn('created_at', function ($service) {
                return $service->created_at ? with(new Carbon($service->created_at))->format('d/m/Y') : '';
            })
            ->editColumn('description', function ($service) {
                return Str::limit($service->description,50);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('service_name', function ($query, $keyword) {
                $query->whereTranslationLike('service_name', "%{$keyword}%");
            })
            ->orderColumn('service_name', function ($query, $order) {
                 $query->orderByTranslation('service_name',$order);
            })

            ->filterColumn('description', function ($query, $keyword) {
                $query->whereTranslationLike('description', "%{$keyword}%");
            })
            ->orderColumn('description', function ($query, $order) {
                 $query->orderByTranslation('description',$order);
            })
            ->addColumn('is_active',function($service){

                
                if($service->is_active){
                   $message='deactivate';
                   return '<a title="Click to deactivate the service" href="javascript:change_status('."'".route('admin.services.change_status',$service->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm " >Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the service" href="javascript:change_status('."'".route('admin.services.change_status',$service->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm ">Inactive</a>';
                }
            })
            ->addColumn('action',function($service){
                $delete_url=route('admin.services.delete',$service->id);
                $details_url=route('admin.services.show',$service->id);
                $edit_url=route('admin.services.edit',$service->id);

                $action_buttons='';
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'<a title="View Service Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit Service" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete Service" href="javascript:delete_service('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }

                return $action_buttons;
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }

        return view($this->view_path.'.list',$this->data);
    }

    public function create(){
        $this->data['page_title']='Create Service';
        return view($this->view_path.'.create',$this->data);
    }

    public function store(CreateServiceRequest $request){
        $current_user=auth()->guard('admin')->user();

        $service_data = [
            'en' => [
               'service_name'=>$request->en_service_name,
               'description' =>$request->en_description,
            ],
            'ar' => [
               'service_name'=>$request->ar_service_name,
               'description' =>$request->ar_description,
            ],
            'slug'=>Str::slug($request->service_name),
            'created_by'=>$current_user->id,
            'updated_by'=>$current_user->id
        ];

        Service::create($service_data);

        return redirect()->route('admin.services.list')->with('success','Service successfully created.');
    }
    public function show($id){
        $service=Service::findOrFail($id);
        $this->data['page_title']='Service Details';
        $this->data['service']=$service;
        return view($this->view_path.'.show',$this->data);
    }
    public function edit($id){
        $service=Service::findOrFail($id);
        $this->data['page_title']='Edit Service';
        $this->data['service']=$service;
        return view($this->view_path.'.edit',$this->data);
    }
    public function update(EditServiceRequest $request,$id){
        $service=Service::findOrFail($id);
        $current_user=auth()->guard('admin')->user();

        $service_data = [
            'en' => [
               'service_name'=>$request->en_service_name,
               'description' =>$request->en_description,
            ],
            'ar' => [
               'service_name'=>$request->ar_service_name,
               'description' =>$request->ar_description,
            ],
            'slug'=>Str::slug($request->service_name),
            'updated_by'=>$current_user->id
        ];

        $service->update($service_data);

        return redirect()->route('admin.services.list')->with('success','Service successfully updated.');
    }
    public function delete($id){
        $service=Service::findOrFail($id);

        $service->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $service->delete();
        return response()->json(['message'=>'Service successfully deleted.']);
    }

    public function change_status($id){
        $service=Service::findOrFail($id);
        $change_status_to=($service->is_active)?false:true;
        $message=($service->is_active)?'deactivated':'activated';

         //updating gallery status
        $service->update([
            'is_active'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Service successfully '.$message.'.']);
    }

    public function ajax_check_service_name_unique(Request $request,$service_id=null){
        if($service_id){
             $service= Service::where('id','!=',$service_id)
             ->whereTranslation('service_name',$request->service_name,$request->locale)->first();
        }else{
             $service= Service::whereTranslation('service_name',$request->service_name,$request->locale)->first();
        }
     
        if($service){
            echo "false";
        }else{
            echo "true";
        }
    }

}
