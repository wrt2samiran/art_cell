<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\{SparePart,UnitMaster,SparePartImage};
use Helper, Image,File;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\SparePart\{CreateSparePartRequest,UpdateSparePartRequest};
class SparePartsController extends Controller
{

    private $view_path='admin.spare_parts';

    /*****************************************************/
    # SparePartsController
    # Function name : List
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Show Spare Parts List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Spare Parts';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){

            $spareParts=SparePart::with('unitmaster')->orderBy('id','Desc');
            return Datatables::of($spareParts)
            ->editColumn('created_at', function ($spareParts) {
                return $spareParts->created_at ? with(new Carbon($spareParts->created_at))->format('m/d/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($spareParts)use($current_user){
                $disabled=(!$current_user->hasAllPermission(['spare-part-status-change']))?'disabled':'';
                if($spareParts->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the Spare Parts" href="javascript:change_status('."'".route('admin.spare_parts.change_status',$spareParts->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the Spare Parts" href="javascript:change_status('."'".route('admin.spare_parts.change_status',$spareParts->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Inactive</a>';
                }
            })
            ->addColumn('action',function($spareParts){
                $delete_url=route('admin.spare_parts.delete',$spareParts->id);
                $details_url=route('admin.spare_parts.show',$spareParts->id);
                $edit_url=route('admin.spare_parts.edit',$spareParts->id);

                return '<a title="View Spare Parts Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Spare Parts" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete Spare Parts" href="javascript:delete_shared_service('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }


    /************************************************************************/
    # Function to load spare part create view page                           #
    # Function name    : create                                              #
    # Created Date     : 07-10-2020                                          #
    # Modified date    : 07-12-2020                                          #
    # Purpose          : To load spare part create view page                 #
    public function create() {
        $this->data['page_title']     = 'Create Spare Part';
        $unit_list = UnitMaster::where('is_active',true)->orderBy('unit_name', 'Asc')->get();
        $this->data['unit_list'] = $unit_list;
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store shared service data                                          #
    # Function name    : store                                                       #
    # Created Date     : 14-10-2020                                                  #
    # Modified date    : 14-10-2020                                                  #
    # Purpose          : store shared service data                                   #
    # Param            : CreateSharedServiceRequest $request                         #
    public function store(CreateSparePartRequest $request){
        $current_user=auth()->guard('admin')->user(); 
        $spare_part=SparePart::create([
            'name' => trim($request->name, ' '),
            'manufacturer'  => $request->manufacturer,
            'description'  => $request->description,
            'unit_master_id'  => $request->unit_master_id,
            'price'  => $request->price,
            'currency'  => Helper::getSiteCurrency(),
            'created_by' => $current_user->id,
            'updated_by' => $current_user->id
        ]);

        if($request->hasFile('images')){

            $image_data=[];

            foreach ($request->file('images')  as $key=>$image) {

                $image_name = time().$key.'.'.$image->getClientOriginalExtension();

                $thumb_path = public_path('/uploads/spare_part_images/thumb');

                $img = Image::make($image->getRealPath());
                //resizing and saving resized image
                $img->resize(config("image_upload.thumb_size.width"), config("image_upload.thumb_size.height"), function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$image_name);

                $destinationPath = public_path('/uploads/spare_part_images');
                //uploading original image
                $image->move($destinationPath, $image_name);

                $image_data[]=[
                    'spare_part_id'=>$spare_part->id,
                    'image_name'=>$image_name
                ];

            }

            SparePartImage::insert($image_data);
        }
        return redirect()->route('admin.spare_parts.list')->with('success','Spare part successfully created.');

    }


    /************************************************************************/
    # Function to load spare part edit page                                  #
    # Function name    : edit                                                #
    # Created Date     : 07-10-2020                                          #
    # Modified date    : 07-12-2020                                          #
    # Purpose          : to load spare part edit page                        #
    # Param            : id                                                  #
    public function edit($id){
        $this->data['page_title']     = 'Edit Spare Part';
        $this->data['spare_part']=SparePart::findOrFail($id);
        $unit_list = UnitMaster::where('is_active',true)->orderBy('unit_name', 'Asc')->get();
        $this->data['unit_list'] = $unit_list;
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update property data                                                   #
    # Function name    : update                                                          #
    # Created Date     : 12-10-2020                                                      #
    # Modified date    : 12-10-2020                                                      #
    # Purpose          : to update property data                                         #
    # Param            : UpdateSparePartRequest $request,id                              #
    public function update(UpdateSparePartRequest $request,$id){
        
        $spare_part=SparePart::findOrFail($id);
        $current_user=auth()->guard('admin')->user(); 
        $spare_part->update([
            'name' => trim($request->name, ' '),
            'manufacturer'  => $request->manufacturer,
            'description'  => $request->description,
            'unit_master_id'  => $request->unit_master_id,
            'price'  => $request->price,
            'currency'  => Helper::getSiteCurrency(),
            'updated_by' => $current_user->id
        ]);

        if($request->hasFile('images')){

            if(count($spare_part->images)){
                //deleting old images
                foreach ($spare_part->images as $image) {
                    //storing old path of the image in variable
                    $old_path=public_path().'/uploads/spare_part_images/'.$image->image_name;
                    //storing old thumb path of the image in variable
                    $thumb_path=public_path().'/uploads/spare_part_images/thumb/'.$image->image_name;

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
            SparePartImage::where('spare_part_id',$spare_part->id)->delete();

            $image_data=[];
            foreach ($request->file('images')  as $key=>$image) {

                $image_name = time().$key.'.'.$image->getClientOriginalExtension();

                $thumb_path = public_path('/uploads/spare_part_images/thumb');

                $img = Image::make($image->getRealPath());
                //resizing and saving resized image
                $img->resize(config("image_upload.thumb_size.width"), config("image_upload.thumb_size.height"), function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$image_name);

                $destinationPath = public_path('/uploads/spare_part_images');
                //uploading original image
                $image->move($destinationPath, $image_name);

                $image_data[]=[
                    'spare_part_id'=>$spare_part->id,
                    'image_name'=>$image_name
                ];
            }

            SparePartImage::insert($image_data);
        }

        return redirect()->route('admin.spare_parts.list')->with('success','Spare part successfully updated.');

    }

    /*****************************************************/
    # Function name : change_status
    # Created Date  : 08-10-2020
    # Purpose       : Change Spare Parts status
    # Params        : Request $request,id
    /*****************************************************/
    public function change_status(Request $request, $id)
    {
        $spare_part=SparePart::findOrFail($id);
        $change_status_to=($spare_part->is_active)?false:true;
        $message=($spare_part->is_active)?'deactivated':'activated';

         //updating gallery status
        $spare_part->update([
            'is_active'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Spare part successfully '.$message.'.']);
    }

    /*****************************************************/
    # Function name : delete
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Delete Spare Parts
    # Params        : Request $request,id
    /*****************************************************/
    public function delete(Request $request, $id)
    {
        $spare_part=SparePart::findOrFail($id);
        if(count($spare_part->images)){
            //deleting old images
            foreach ($spare_part->images as $image) {
                //storing old path of the image in variable
                $old_path=public_path().'/uploads/spare_part_images/'.$image->image_name;
                //storing old thumb path of the image in variable
                $thumb_path=public_path().'/uploads/spare_part_images/thumb/'.$image->image_name;

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
        SparePartImage::where('spare_part_id',$spare_part->id)->delete();
        
        $spare_part->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $spare_part->delete();
        return response()->json(['message'=>'Spare part successfully deleted.']);
    }
    
    /*****************************************************/
    # SparePartsController
    # Function name : show
    # Author        :
    # Created Date  : 08-10-2020
    # Purpose       : Showing Spare Parts details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $spareParts=SparePart::findOrFail($id);
        $this->data['page_title']='Spare Parts Details';
        $this->data['spareParts']=$spareParts;
        return view($this->view_path.'.show',$this->data);
    }
}
