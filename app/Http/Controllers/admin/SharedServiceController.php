<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{SharedService,SharedServiceImage};
use App\Models\ModuleFunctionality;
use Helper,Image,File;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\SharedService\{CreateSharedServiceRequest,UpdateSharedServiceRequest};
class SharedServiceController extends Controller
{

    private $view_path='admin.shared_services';

    /*****************************************************/
    # SharedServiceController
    # Function name : List
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Show Shared Service List
    # Params        : Request $request
    /*****************************************************/
    public function list(Request $request){
        $this->data['page_title']='Shared Services';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $sharedService=SharedService::select('shared_services.*');
            return Datatables::of($sharedService)
            ->editColumn('created_at', function ($sharedService) {
                return $sharedService->created_at ? with(new Carbon($sharedService->created_at))->format('d/m/Y') : '';
            })
            ->editColumn('description', function ($sharedService) {
                return Str::limit($sharedService->description,100);
            })
            ->editColumn('price', function ($sharedService) {
                if($sharedService->is_sharing){
                    return "<div><span>".$sharedService->currency.number_format($sharedService->price, 2, '.', '')."</span> for ".$sharedService->number_of_days." days</div><div>+ ".$sharedService->currency.number_format($sharedService->extra_price_per_day, 2, '.', '')."/day</div>";
                }else{
                    return '<span class="text-muted">'.__('general_sentence.not_available').'</span>';
                }
            })
            ->editColumn('selling_price', function ($sharedService) {
                if($sharedService->is_selling){
                    return "<div><span>".$sharedService->currency.number_format($sharedService->selling_price, 2, '.', '')."</span></div>";
                }else{
                    return '<span class="text-muted">'.__('general_sentence.not_available').'</span>';
                }
            })
            ->orderColumn('price', function ($query, $order) {
                 $query->orderBy('price',$order);
            })
            ->orderColumn('selling_price', function ($query, $order) {
                 $query->orderBy('selling_price',$order);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($sharedService) use($current_user){

                $disabled=(!$current_user->hasAllPermission(['shared-service-status-change']))?'disabled':'';
                if($sharedService->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the shared service" href="javascript:change_status('."'".route('admin.shared_services.change_status',$sharedService->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.' ">'.__('general_sentence.active').'</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the shared service" href="javascript:change_status('."'".route('admin.shared_services.change_status',$sharedService->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.' ">'.__('general_sentence.inactive').'</a>';
                }
            })
            ->addColumn('action',function($sharedService){
                $delete_url=route('admin.shared_services.delete',$sharedService->id);
                $details_url=route('admin.shared_services.show',$sharedService->id);
                $edit_url=route('admin.shared_services.edit',$sharedService->id);

                return '<a title="View Shared Service Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Shared Service" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete shared service" href="javascript:delete_shared_service('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active','price','selling_price'])
            ->make(true);
        }
        return view($this->view_path.'.list',$this->data);
    }


    /************************************************************************/
    # Function to load shared service create view page                       #
    # Function name    : create                                              #
    # Created Date     : 07-10-2020                                          #
    # Modified date    : 07-12-2020                                          #
    # Purpose          : To load shared service create view page             #
    public function create() {
        $this->data['page_title']     = 'Create Shared Service';
        return view($this->view_path.'.create',$this->data);
    }



    /********************************************************************************/
    # Function to store shared service data                                          #
    # Function name    : store                                                       #
    # Created Date     : 14-10-2020                                                  #
    # Modified date    : 14-10-2020                                                  #
    # Purpose          : store shared service data                                   #
    # Param            : CreateSharedServiceRequest $request                         #
    public function store(CreateSharedServiceRequest $request){
        $current_user=auth()->guard('admin')->user(); 
        $shared_service=SharedService::create([
            'name' => trim($request->name, ' '),
            'description' => $request->description,
            'is_sharing'  => ($request->is_sharing)?true:false,
            'number_of_days'  => $request->number_of_days,
            'price'  => $request->price,
            'extra_price_per_day'  => $request->extra_price_per_day,
            'is_selling'  => ($request->is_selling)?true:false,
            'selling_price'  => $request->selling_price,
            'currency'  => Helper::getSiteCurrency(),
            'created_by' => $current_user->id,
            'updated_by' => $current_user->id
        ]);

        if($request->hasFile('images')){

            $image_data=[];

            foreach ($request->file('images')  as $key=>$image) {

                $image_name = time().$key.'.'.$image->getClientOriginalExtension();

                $thumb_path = public_path('/uploads/shared_service_images/thumb');

                $img = Image::make($image->getRealPath());
                //resizing and saving resized image
                $img->resize(config("image_upload.thumb_size.width"), config("image_upload.thumb_size.height"), function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$image_name);

                $destinationPath = public_path('/uploads/shared_service_images');
                //uploading original image
                $image->move($destinationPath, $image_name);

                $image_data[]=[
                    'shared_service_id'=>$shared_service->id,
                    'image_name'=>$image_name
                ];

            }

            SharedServiceImage::insert($image_data);
        }
        return redirect()->route('admin.shared_services.list')->with('success',__('shared_service_manage_module.create_success_message'));

    }


    /************************************************************************/
    # Function to load shared service edit page                              #
    # Function name    : edit                                                #
    # Created Date     : 07-10-2020                                          #
    # Modified date    : 07-12-2020                                          #
    # Purpose          : to load shared service edit page                    #
    # Param            : id                                                  #
    public function edit($id){
        $this->data['page_title']     = 'Edit Shared Service';
        $this->data['shared_service']=SharedService::findOrFail($id);
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update property data                                                   #
    # Function name    : update                                                          #
    # Created Date     : 12-10-2020                                                      #
    # Modified date    : 12-10-2020                                                      #
    # Purpose          : to update property data                                         #
    # Param            : UpdateSharedServiceRequest $request,id                          #
    public function update(UpdateSharedServiceRequest $request,$id){
        
        $shared_service=SharedService::findOrFail($id);
        $current_user=auth()->guard('admin')->user(); 
        $shared_service->update([
            'name' => trim($request->name, ' '),
            'description' => $request->description,
            'is_sharing'  => ($request->is_sharing)?true:false,
            'number_of_days'  => $request->number_of_days,
            'price'  => $request->price,
            'extra_price_per_day'  => $request->extra_price_per_day,
            'is_selling'  => ($request->is_selling)?true:false,
            'selling_price'  => $request->selling_price,
            'currency'  => Helper::getSiteCurrency(),
            'updated_by' => $current_user->id
        ]);

        if($request->hasFile('images')){

            if(count($shared_service->images)){
                //deleting old images
                foreach ($shared_service->images as $image) {
                    //storing old path of the image in variable
                    $old_path=public_path().'/uploads/shared_service_images/'.$image->image_name;
                    //storing old thumb path of the image in variable
                    $thumb_path=public_path().'/uploads/shared_service_images/thumb/'.$image->image_name;

                    //if file exists then deleting the file from folder
                    if(File::exists($old_path)){
                    File::delete($old_path);
                    }
                    //if thumb file exists then deleting the file from folder
                    if(File::exists($thumb_path)){
                    File::delete($thumb_path);
                    }

                }
            }
            SharedServiceImage::where('shared_service_id',$shared_service->id)->delete();

            $image_data=[];
            foreach ($request->file('images')  as $key=>$image) {

                $image_name = time().$key.'.'.$image->getClientOriginalExtension();

                $thumb_path = public_path('/uploads/shared_service_images/thumb');

                $img = Image::make($image->getRealPath());
                //resizing and saving resized image
                $img->resize(config("image_upload.thumb_size.width"), config("image_upload.thumb_size.height"), function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$image_name);

                $destinationPath = public_path('/uploads/shared_service_images');
                //uploading original image
                $image->move($destinationPath, $image_name);

                $image_data[]=[
                    'shared_service_id'=>$shared_service->id,
                    'image_name'=>$image_name
                ];
            }

            SharedServiceImage::insert($image_data);
        }

        return redirect()->route('admin.shared_services.list')->with('success',__('shared_service_manage_module.edit_success_message'));

    }


    /*****************************************************/
    # Function name : change_status
    # Author        :
    # Created Date  : 07-10-2020
    # Modified date : 07-12-2020
    # Purpose       : Change Shared Service status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id)
    {
        $shared_service=SharedService::findOrFail($id);
        $change_status_to=($shared_service->is_active)?false:true;
        $message=($shared_service->is_active)?'deactivated':'activated';

         //updating gallery status
        $shared_service->update([
            'is_active'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Shared service successfully '.$message.'.']);
    }

    /*****************************************************/
    # Function name : delete
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Delete Shared Service
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id)
    {
        $shared_service=SharedService::findOrFail($id);


        if(count($shared_service->images)){
            //deleting old images
            foreach ($shared_service->images as $image) {
                //storing old path of the image in variable
                $old_path=public_path().'/uploads/shared_service_images/'.$image->image_name;
                //storing old thumb path of the image in variable
                $thumb_path=public_path().'/uploads/shared_service_images/thumb/'.$image->image_name;

                //if file exists then deleting the file from folder
                if(File::exists($old_path)){
                File::delete($old_path);
                }
                //if thumb file exists then deleting the file from folder
                if(File::exists($thumb_path)){
                File::delete($thumb_path);
                }

            }
        }
        SharedServiceImage::where('shared_service_id',$shared_service->id)->delete();
        
        $shared_service->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $shared_service->delete();
        return response()->json(['message'=>'Shared service successfully deleted.']);
    }
    
    /*****************************************************/
    # Function name : show
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Showing Shared Service details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $sharedServices=SharedService::findOrFail($id);
        $this->data['page_title']='Shared Service Details';
        $this->data['sharedServices']=$sharedServices;
        return view($this->view_path.'.show',$this->data);
    }
}
