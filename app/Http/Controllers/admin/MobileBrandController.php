<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MobileBrand;
use Validator;
use Yajra\Datatables\Datatables;

class MobileBrandController extends Controller
{

    private $view_path='admin.mobile_brand';

    /*****************************************************/
    # MobileBrandController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing Brand List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Brand List';
        if($request->ajax()){

            $brand=MobileBrand::orderBy('id','DESC');
            return Datatables::of($brand)
            ->editColumn('created_at', function ($brand) {
                return $brand->created_at ? with(new Carbon($brand->created_at))->format('m/d/Y') : '';
            })
            
           
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%");
            })
            ->orderColumn('name', function ($query, $order) {
                 $query->orderByTranslation('name',$order);
            })
            ->addColumn('is_active',function($brand){
                if($brand->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the brand" href="javascript:change_status('."'".route('admin.mobile_brand.change_status',$brand->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the brand" href="javascript:change_status('."'".route('admin.mobile_brand.change_status',$brand->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($brand){
                $delete_url=route('admin.mobile_brand.delete',$brand->id);
                $details_url=route('admin.mobile_brand.show',$brand->id);
                $edit_url=route('admin.mobile_brand.edit',$brand->id);

                // return '<a title="View Mobile Brand Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Mobile Brand" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete Mobile Brand" href="javascript:delete_country('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';

                return '<a title="View Mobile Brand Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Mobile Brand" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # MobileBrandController
    # Function name : mobileBrandAdd
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Adding new Mobile BrandAdd
    # Params        : Request $request
    /*****************************************************/
    public function mobileBrandAdd(Request $request) {

        $data['page_title']     = 'Add Mobile Brand';
        $data['panel_title']    = 'Add Mobile Brand';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'en_name'          => 'required|min:2|max:255',
				);
				$validationMessages = array(
					'en_name.required'      => 'Please enter name',
					'en_name.min'           => 'Name should be should be at least 2 characters',
                    'en_name.max'           => 'Name should not be more than 255 characters',
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.mobile_brand.mobile_brand.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $post_data = [
                       'en' => [
                           'name'       => $request->input('en_name'),
                           
                       ],
                       'author'=>'author'
                    ];

                    if($request->input('ar_name')){
                        $post_data['ar']=[
                           'name'       => $request->input('ar_name'),
                           
                       ];
                    }

                  

                    MobileBrand::create($post_data);
                  				
					return redirect()->route('admin.mobile_brand.list')->with('success','Mobile Brand successfully created.');
					
				}
            }
			return view('admin.mobile_brand.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.mobile_brand.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # MobileBrandController
    # Function name : edit
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Editing Mobile Brand
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']     = 'Edit Mobile Brand';
        $data['panel_title']    = 'Edit Mobile Brand';

        try
        {           
           
            $details = MobileBrand::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
               // dd($request->all());
                if ($id == null) {
                    return redirect()->route('admin.mobile_brand.list');
                }
                $validationCondition = array(
                    'en_name'          => 'required|min:2|max:255',
                );
                $validationMessages = array(
                    'en_name.required'      => 'Please enter name',
                    'en_name.min'           => 'Name should be should be at least 2 characters',
                    'en_name.max'           => 'Name should not be more than 255 characters',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {

                    $post = [
                       'en' => [
                           $details->translate('en')->name = $request->input('en_name'),
                       ],
                       'author'=>'author'
                    ];

                    if($request->input('ar_name')){
                        $post['ar']=[
                           $details->translate('ar')->name = $request->input('ar_name'),
                       ];
                    }

                    $save = $details->save();                        
                    if ($save) {
                        return redirect()->route('admin.mobile_brand.list')->with('success','Brand successfully updated.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.mobile_brand.edit', $data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.mobile_brand.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # MobileBrandController
    # Function name : change_status
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Change brand status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.mobile_brand.list');
            }
            $details = MobileBrand::where('id', $id)->first();
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
                return redirect()->route('admin.mobile_brand.list')->with('error', 'Invalid brand');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.mobile_brand.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # MobileBrandController
    # Function name : delete
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Delete Brand
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        
            if ($id == null) {
                return redirect()->route('admin.mobile_brand.list');
            }

            $details = MobileBrand::where('id', $id)->first();
            
                    $delete = $details->delete();
                return response()->json(['message'=>'Brand successfully deleted.']);
                    
    }
    
    /*****************************************************/
    # MobileBrandController
    # Function name : show
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Show Brand details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $brand=MobileBrand::findOrFail($id);
        $this->data['page_title']='Brand Details';
        $this->data['brand']=$brand;
        return view($this->view_path.'.show',$this->data);
    }
}
