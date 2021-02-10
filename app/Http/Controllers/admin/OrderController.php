<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\CityTranslation;
use App\Models\Order;
use App\Models\MobileBrand;
use App\Models\MobileBrandModel;
use Helper, Auth, Validator;
use Yajra\Datatables\Datatables;

class OrderController extends Controller
{

    private $view_path='admin.order';

    /*****************************************************/
    # CitiesController
    # Function name : List
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing City List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Order List';
        if($request->ajax()){

            $order=Order::with('state')->with('country')->with('city')->with('mobile_brand')->with('mobile_brand_model');
            return Datatables::of($order)
            ->editColumn('created_at', function ($order) {
                return $order->created_at ? with(new Carbon($order->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->filterColumn('first_name', function ($query, $keyword) {
                $query->whereTranslationLike('first_name', "%{$keyword}%");
            })
            ->orderColumn('first_name', function ($query, $order) {
                 $query->orderByTranslation('first_name',$order);
            })
            ->addColumn('is_active',function($order){
                if($order->is_active=='1'){
                   $message='deactivate';
                   return '<a title="Click to deactivate the order" href="javascript:change_status('."'".route('admin.cities.change_status',$order->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm">Active</a>';
                    
                }else{
                   $message='activate';
                   return '<a title="Click to activate the order" href="javascript:change_status('."'".route('admin.cities.change_status',$order->id)."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm">Inactive</a>';
                }
            })
            ->addColumn('action',function($order){
                $delete_url=route('admin.order.delete',$order->id);
                $details_url=route('admin.order.show',$order->id);
                $edit_url=route('admin.order.edit',$order->id);

                return '<a title="View Order Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # CityController
    # Function name : cityAdd
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Adding new City
    # Params        : Request $request
    /*****************************************************/
    public function orderAdd(Request $request) {

        $this->data['page_title']     = 'Add Order';
        $this->data['panel_title']    = 'Add Order';
    
        try
        {
            if ($request->isMethod('POST'))
            {
               // dd($request->all());
                $getMaxOrderId = Order::orderBy('id', 'desc')->first();
                $validationCondition = array(
                    'first_name'          => 'required|min:2|max:255',
                    'country_id'    => 'required',
                    'state_id'      => 'required',
                );
                $validationMessages = array(
                    'first_name.required'            => 'Please enter name',
                    'first_name.min'                 => 'Name should be should be at least 2 characters',
                    'first_name.max'                 => 'Name should not be more than 255 characters',
                    'country_id'               => 'Please select country',
                    'state_id.required'        => 'Please select state',
                );

                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->route('admin.order.add')->withErrors($Validator)->withInput();
                } else {
                    
                    
                    $new = new Order;
                    $new->job_no      = 'AC'.$getMaxOrderId->id.rand ( 10000 , 99999 );
                    $new->warrrenty_no = $request->warrrenty_no;
                    $new->mobile_brand_id = $request->mobile_brand_id;
                    $new->mobile_brand_model_id = $request->mobile_brand_model_id;
                    $new->imei_serial = $request->imei_serial;

                    $new->first_name = $request->first_name;
                    $new->last_name = $request->last_name;
                    $new->customer_contact = $request->customer_contact;
                    $new->customer_address = $request->customer_address;
                    $new->physical_condition = $request->physical_condition;
                    $new->risk_agreed_by_customer = $request->risk_agreed_by_customer;
                    $new->service_complaints = $request->service_complaints;
                    $new->estimated_price = $request->estimated_price;
                    $new->advanced_payment = $request->advanced_payment;
                    $new->due_payment = '0.00';
                    $new->actual_price = $request->estimated_price;
                    $new->total_recived = $request->advanced_payment;
                    

                    $new->country_id  = $request->country_id;
                    $new->state_id    = $request->state_id;
                    $new->city_id    = $request->city_id;
                    $new->created_by = auth()->guard('admin')->id();
                    $new->created_at = date('Y-m-d H:i:s');
                    $save = $new->save();
                
                    if ($save) { 
                        $insertedId = $new->id;

                        return redirect()->route('admin.order.list')->with('success','Order successfully created.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while adding the city');
                        return redirect()->back();
                    }
                }
            }

            $mobile_brand_data=MobileBrand::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['mobile_brand_data']=$mobile_brand_data;

            $country_list=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            $this->data['country_list']=$country_list;
            return view($this->view_path.'.create',$this->data);
        } catch (Exception $e) {
            return redirect()->route('admin.cities.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CityController
    # Function name : cityEdit
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $this->data['page_title']     = 'Edit City';
        $this->data['panel_title']    = 'Edit City';

        try
        {           

            $details = City::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {

                
                if ($id == null) {
                    return redirect()->route('admin.cities.list');
                }
                $validationCondition = array(
                    'name'          => 'required|min:2|max:255|unique:' .(new City)->getTable().',name,'.$id.'',
                    'country_id'    => 'required',
                    'state_id'      => 'required',
                );
                $validationMessages = array(
                    'name.required'            => 'Please enter name',
                    'name.min'                 => 'Name should be should be at least 2 characters',
                    'name.max'                 => 'Name should not be more than 255 characters',
                    'country_id.required'      => 'Please select country',
                    'state_id.required'        => 'Please select state',
                );
                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    $details->name        = trim($request->name, ' ');
                    $details->country_id  = $request->country_id;
                    $details->state_id    = $request->state_id;
                    $details->updated_at  = date('Y-m-d H:i:s');
                    $save = $details->save();                        
                    if ($save) {

                        CityTranslation::where('city_id', $id)->delete();
                        //$languages = \Helper::WEBITE_LANGUAGES;
                        $languages = array('en', 'ar');
                        foreach($languages as $language){
                            $newLocal                   = new CityTranslation;
                            $newLocal->city_id          = $id;
                            $newLocal->locale           = $language;
                            if ($language == 'en') {
                                $newLocal->name        = trim($request->name, ' ');
                                
                            } else {
                                $newLocal->name        = trim($request->ar_name, ' ');
                            }
                            $saveLocal = $newLocal->save();
                        }
                        
                        return redirect()->route('admin.cities.list')->with('success','City successfully updated.');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the city');
                        return redirect()->back();
                    }
                }
            }
            
            $country_list=Country::whereIsActive('1')->orderBy('id','ASC')->get();
            $state_list=State::whereIsActive('1')->whereCountryId($details->country_id)->orderBy('id','ASC')->get();
            $this->data['country_list']=$country_list;
            $this->data['state_list']=$state_list;
            return view($this->view_path.'.edit',$this->data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.cities.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CityController
    # Function name : change_status
    # Author        :
    # Created Date  : 07-10-2020
    # Purpose       : Change city status
    # Params        : Request $request
    /*****************************************************/
    public function change_status(Request $request, $id = null)
    {
        try
        {
            if ($id == null) {
                return redirect()->route('admin.cities.list');
            }
            $details = City::where('id', $id)->first();
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
                return redirect()->route('admin.cities.list')->with('error', 'Invalid city');
            }
        } catch (Exception $e) {
            return redirect()->route('admin.cities.list')->with('error', $e->getMessage());
        }
    }

    /*****************************************************/
    # CityController
    # Function name : cityDelete
    # Author        :
    # Created Date  : 13-08-2020
    # Purpose       : Showing subAdminList of users
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
       
            if ($id == null) {
                return redirect()->route('admin.cities.list');
            }

            $details = City::where('id', $id)->first();
            
                    $delete = $details->delete();
                return response()->json(['message'=>'City successfully deleted.']);
                    
    }
    

    /*****************************************************/
    # CityController
    # Function name : show
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Showing Country details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $order=Order::with('state')->with('country')->with('city')->with('mobile_brand')->with('mobile_brand_model')->with('createdby')->whereId($id)->first();
        $this->data['page_title']='Order Details';
        $this->data['order']=$order;
        return view($this->view_path.'.show',$this->data);
    }
    /*****************************************************/
    # CityController
    # Function name : getStates
    # Author        :
    # Created Date  : 06-10-2020
    # Purpose       : Get Country wise State List
    # Params        : Request $request
    /*****************************************************/

   

    public function getStates(Request $request)
    {

        $allStates = State::whereIsActive('1')->where('country_id', $request->country_id)->get();
        return response()->json(['status'=>true, 'allStates'=>$allStates,],200);
    }




    /************************************************************************/
    # Function to get country and state wise active cities                   #
    # Function name    : getCityList                                         #
    # Created Date     : 10-12-2020                                          #
    # Modified date    : 10-12-2020                                          #
    # Purpose          : to get country and state wise active cities         #
    # Param            : id                                                  #
    public function getCityList(Request $request)
    {
    
        $allCity = City::whereIsActive('1')->where('state_id', $request->state_id)->get();
        return response()->json(['status'=>true, 'allCity'=>$allCity,],200);
    }

    public function getModelList(Request $request)
    {
    
        $allModels = MobileBrandModel::whereIsActive('1')->where('mobile_brand_id', $request->brand_id)->get();
        return response()->json(['status'=>true, 'allModels'=>$allModels,],200);
    }

    
}
