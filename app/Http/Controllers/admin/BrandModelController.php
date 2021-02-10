<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MobileBrand;
use App\Models\MobileBrandModel;
use App\Models\MobileBrandModelTranslation;
use Helper, Auth, Validator;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class BrandModelController extends Controller
{

    private $view_path='admin.mobile_brand_model';

    /*****************************************************/
    # BrandModelController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing State List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Model List';
        if($request->ajax()){

            $mobile_brand_model=MobileBrandModel::with('brand');
            return Datatables::of($mobile_brand_model)
            ->editColumn('created_at', function ($mobile_brand_model) {
                return $mobile_brand_model->created_at ? with(new Carbon($mobile_brand_model->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%");
            })
            ->orderColumn('name', function ($query, $order) {
                 $query->orderByTranslation('name',$order);
            })
            ->addColumn('is_active',function($mobile_brand_model){
                if($mobile_brand_model->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the model" href="javascript:change_status('."'".route('admin.mobile_brand_model.change_status',$mobile_brand_model->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the model" href="javascript:change_status('."'".route('admin.mobile_brand_model.change_status',$mobile_brand_model->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($mobile_brand_model){
                $delete_url=route('admin.mobile_brand_model.delete',$mobile_brand_model->id);
                $details_url=route('admin.mobile_brand_model.show',$mobile_brand_model->id);
                $edit_url=route('admin.mobile_brand_model.edit',$mobile_brand_model->id);

                return '<a title="View Model Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit model" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete model" href="javascript:delete_state('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }

        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # BrandModelController
    # Function name : stateAdd
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Adding new Country
    # Params        : Request $request
    /*****************************************************/
    public function stateAdd(Request $request) {

        $this->data['page_title']     = 'Add Model';
        $this->data['panel_title']    = 'Add Model';
    
        try
        {
            if ($request->isMethod('POST'))
            {
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:'.(new MobileBrandModelTranslation)->getTable().',name',
                    'mobile_brand_id'    => 'required',
                );
                $validationMessages = array(
                    'name.required'            => 'Please enter name',
                    'name.min'                 => 'Name should be should be at least 2 characters',
                    'name.max'                 => 'Name should not be more than 255 characters',
                    'mobile_brand_id.required' => 'Please select brand',

                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('admin.mobile_brand_model.add')->withErrors($Validator)->withInput();
                } else {
                    
                    $new = new MobileBrandModel;
                    $new->name = trim($request->name, ' ');
                    $new->mobile_brand_id  = $request->mobile_brand_id;
                    
                    $new->created_at = date('Y-m-d H:i:s');
                    $save = $new->save();
                
                    if ($save) {
                        
                        $insertedId = $new->id;

                       // $languages = \Helper::WEBITE_LANGUAGES;
                        $languages = array('en', 'ar');
                        foreach ($languages as $language) {
                            $newLocal                   = new MobileBrandModelTranslation;
                            $newLocal->mobile_brand_model_id          = $insertedId;
                            $newLocal->locale           = $language;
                            if ($language == 'en') {
                                $newLocal->name        = trim($request->name, ' ');
                                
                            } else{
                                $newLocal->name        = trim($request->ar_name, ' ');
                            } 
                            $saveLocal = $newLocal->save();
                        }
                        return redirect()->route('admin.mobile_brand_model.list')->with('success','Model successfully created.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while adding the model');
                        return redirect()->back();
                    }
                }
            }

            $mobile_brand_data=MobileBrand::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['mobile_brand_data']=$mobile_brand_data;
            return view($this->view_path.'.add',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.mobile_brand_model.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CountryController
    # Function name : cityEdit
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $this->data['page_title']     = 'Edit Model';
        $this->data['panel_title']    = 'Edit Model';
        try
        {           

            $details = MobileBrandModel::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {

                
                if ($id == null) {
                    return redirect()->route('admin.mobile_brand_model.list');
                }
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:' .(new MobileBrandModel)->getTable().',name,'.$id.'',
                    'mobile_brand_id'    => 'required',
                );
                $validationMessages = array(
                    'name.required'            => 'Please enter name',
                    'name.min'                 => 'Name should be should be at least 2 characters',
                    'name.max'                 => 'Name should not be more than 255 characters',
                    'mobile_brand_id.required'      => 'Please select country',

                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->name        = trim($request->name, ' ');
                    $details->mobile_brand_id  = $request->mobile_brand_id;
                    $details->updated_at      = date('Y-m-d H:i:s');
                    $save = $details->save();                        
                    if ($save) {
                        MobileBrandModelTranslation::where('mobile_brand_model_id', $id)->delete();
                        //$languages = \Helper::WEBITE_LANGUAGES;
                        $languages = array('en', 'ar');
                        foreach($languages as $language){
                            $newLocal                   = new MobileBrandModelTranslation;
                            $newLocal->mobile_brand_model_id          = $id;
                            $newLocal->locale           = $language;
                            if ($language == 'en') {
                                $newLocal->name        = trim($request->name, ' ');
                                
                            } else {
                                $newLocal->name        = trim($request->ar_name, ' ');
                            }
                            $saveLocal = $newLocal->save();
                        }
                        return redirect()->route('admin.mobile_brand_model.list')->with('success','Model successfully updated.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the Model');
                        return redirect()->back();
                    }
                }
            }
            
            $mobile_brand_data=MobileBrand::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['mobile_brand_data']=$mobile_brand_data;
            return view($this->view_path.'.edit',$this->data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.mobile_brand_model.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # BrandModelController
    # Function name : change_status
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Change state status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.mobile_brand_model.list');
            }
            $details = MobileBrandModel::where('id', $id)->first();
            if ($details != null) {
                if ($details->is_active == 1) {
                    
                    $details->is_active = '0';
                    $details->save();
                        
                    $request->session()->flash('alert-success', 'Status updated successfully');                 
                     } else if ($details->status == 0) {
                    $details->is_active = '1';
                    $details->save();
                    $request->session()->flash('alert-success', 'Status updated successfully');
                   
                } else {
                    $request->session()->flash('alert-danger', 'Something went wrong');
                    
                }
                return redirect()->back();
            } else {
                return redirect()->route('admin.mobile_brand_model.list')->with('error', 'Invalid Model');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.mobile_brand_model.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # BrandModelController
    # Function name : stateDelete
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing subAdminList of state
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        
            if ($id == null) {
                return redirect()->route('admin.mobile_brand_model.list');
            }

            $details = MobileBrandModel::where('id', $id)->first();
                    $delete = $details->delete();
            return response()->json(['message'=>'Model successfully deleted.']);           
    }
    

    /*****************************************************/
    # BrandModelController
    # Function name : show
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing State details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){

        $mobile_brand_data=MobileBrandModel::findOrFail($id);
        $this->data['page_title']='Sate Details';
        $this->data['mobile_brand_data']=$mobile_brand_data;
        return view($this->view_path.'.show',$this->data);
    }
}
