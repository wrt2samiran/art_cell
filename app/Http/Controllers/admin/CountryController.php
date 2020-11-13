<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\ModuleFunctionality;
use Validator;
use Yajra\Datatables\Datatables;

class CountryController extends Controller
{

    private $view_path='admin.country';

    /*****************************************************/
    # CountryController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing Country List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Country List';
        if($request->ajax()){

            $country=Country::orderBy('id','DESC');
            return Datatables::of($country)
            ->editColumn('created_at', function ($country) {
                return $country->created_at ? with(new Carbon($country->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('is_active',function($country){
                if($country->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the country" href="javascript:change_status('."'".route('admin.country.change_status',$country->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the country" href="javascript:change_status('."'".route('admin.country.change_status',$country->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($country){
                $delete_url=route('admin.country.delete',$country->id);
                $details_url=route('admin.country.show',$country->id);
                $edit_url=route('admin.country.edit',$country->id);

                return '<a title="View Country Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Country" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete country" href="javascript:delete_country('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # CountryController
    # Function name : countryAdd
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Adding new Country
    # Params        : Request $request
    /*****************************************************/
    public function countryAdd(Request $request) {

        $data['page_title']     = 'Add Country';
        $data['panel_title']    = 'Add Country';
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'en_name'          => 'required|min:2|max:255',
                    'country_code'  => 'required|min:2|max:8',
                    'dial_code'     => 'required|min:2|max:5',
				);
				$validationMessages = array(
					'en_name.required'      => 'Please enter name',
					'en_name.min'           => 'Name should be should be at least 2 characters',
                    'en_name.max'           => 'Name should not be more than 255 characters',
                    'country_code.required' => 'Please enter country code',
                    'country_code.min'      => 'Country code should be should be at least 2 characters',
                    'country_code.max'      => 'Country code should not be more than 8 characters',
                    'dial_code.required'    => 'Please enter dial code',
                    'dial_code.min'         => 'Dial code should be should be at least 2 characters',
                    'dial_code.max'         => 'Dial code should not be more than 5 characters',

               
				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.country.country.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $post_data = [
                       'en' => [
                           'name'       => $request->input('en_name'),
                           'country_code' => $request->input('country_code'),
                           'dial_code' => $request->input('dial_code')
                       ],
                       'author'=>'author'
                    ];

                    if($request->input('ar_name')){
                        $post_data['ar']=[
                           'name'       => $request->input('ar_name'),
                           'country_code' => $request->input('country_code'),
                           'dial_code' => $request->input('dial_code')
                       ];
                    }

                  

                    Country::create($post_data);
                  				
					return redirect()->route('admin.country.list')->with('success','Country successfully created.');
					
				}
            }
			return view('admin.country.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.country.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # CountryController
    # Function name : edit
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Editing Country
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']     = 'Edit Country';
        $data['panel_title']    = 'Edit Country';

        try
        {           
           
            $details = Country::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
               // dd($request->all());
                if ($id == null) {
                    return redirect()->route('admin.country.list');
                }
                $validationCondition = array(
                    'en_name'          => 'required|min:2|max:255',
                    'country_code'  => 'required|min:2|max:8',
                    'dial_code'     => 'required|min:2|max:5',
                );
                $validationMessages = array(
                    'en_name.required'      => 'Please enter name',
                    'en_name.min'           => 'Name should be should be at least 2 characters',
                    'en_name.max'           => 'Name should not be more than 255 characters',
                    'country_code.required' => 'Please enter country code',
                    'country_code.min'      => 'Country code should be should be at least 2 characters',
                    'country_code.max'      => 'Country code should not be more than 8 characters',
                    'dial_code.required'    => 'Please enter dial code',
                    'dial_code.min'         => 'Dial code should be should be at least 2 characters',
                    'dial_code.max'         => 'Dial code should not be more than 5 characters',

               
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {

                    $post = [
                       'en' => [
                           $details->translate('en')->name = $request->input('en_name'),
                           $details->translate('en')->country_code = $request->input('country_code'),
                           $details->translate('en')->dial_code = $request->input('dial_code'),
                       ],
                       'author'=>'author'
                    ];

                    if($request->input('ar_name')){
                        $post['ar']=[
                           $details->translate('ar')->name = $request->input('ar_name'),
                           $details->translate('ar')->country_code = $request->input('country_code'),
                           $details->translate('ar')->dial_code = $request->input('dial_code'),
                       ];
                    }

                    $save = $details->save();                        
                    if ($save) {
                        return redirect()->route('admin.country.list')->with('success','Country successfully updated.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the state');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.country.edit', $data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.country.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CountryController
    # Function name : change_status
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Change country status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.country.list');
            }
            $details = Country::where('id', $id)->first();
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
                return redirect()->route('admin.country.list')->with('error', 'Invalid country');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.country.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CountryController
    # Function name : delete
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Delete country
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        
            if ($id == null) {
                return redirect()->route('admin.country.list');
            }

            $details = Country::where('id', $id)->first();
            
                    $delete = $details->delete();
                return response()->json(['message'=>'Country successfully deleted.']);
                    
    }
    
    /*****************************************************/
    # CountryController
    # Function name : show
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Show Country details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $country=Country::findOrFail($id);
        $this->data['page_title']='Country Details';
        $this->data['country']=$country;
        return view($this->view_path.'.show',$this->data);
    }
}
