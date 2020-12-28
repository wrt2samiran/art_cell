<?php

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User,Setting, Property, Contract,Complaint,WorkOrderLists,SparePartOrder,SharedServiceOrder,TaskLists,Status};
use Yajra\Datatables\Datatables;
use Config;
use Carbon\Carbon;
use DB;
class DashboardController extends Controller
{
    public $data = array();             // set global class object
    
    /*****************************************************/
    # DashboardController
    # Function name : changeLanguage
    # Author        :
    # Created Date  : 01-10-2020
    # Purpose       : To change language              
    # Params        : locale
    /*****************************************************/
    public function changeLanguage($locale){
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }


    /*****************************************************/
    # DashboardController
    # Function name : index
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Dashboard View                
    # Params        : 
    /*****************************************************/

    public function index(Request $request)
    {
        $this->data['page_title'] = 'Dashboard';
        $this->data['current_user']=$current_user=auth()->guard('admin')->user();
        $user_type=$current_user->role->user_type->slug;
        
        switch ($user_type) {
            case "super-admin":
                return $this->admin_dashboard($request,$current_user);
            break;
            case "property-owner":
                return $this->customer_dashboard($request,$current_user);
            break;
            case "service-provider":
                return $this->service_provider_dashboard($request,$current_user);
            break;
            case "labour":
                return $this->labour_dashboard($request,$current_user);
            break;
            default:
                return $this->default_dashboard($request,$current_user);
        }
        
    }

    public function admin_dashboard($request,$current_user){

        $this->data['total_customers']=User::whereHas('role',function($q){
            $q->where('slug','property-owner');
        })->count();

        $this->data['total_contracts']=Contract::where('creation_complete',true)->count();

        $this->data['total_service_providers']=User::whereHas('role.user_type',function($q){
            $q->where('slug','service-provider');
        })->count();

        $this->data['complaints']=Complaint::whereHas('contract')
        ->whereHas('complaint_status')
        ->orderBy('id','desc')->take(5)->get();

        $this->data['work_orders']=WorkOrderLists::whereHas('contract')
        ->whereHas('service')
        ->orderBy('id','desc')->take(5)->get();


        $spare_part_orders=SparePartOrder::select(DB::raw('count(*) as total_orderrs,  DATE_FORMAT(created_at, "%m-%Y") as month_year'))->where("created_at",">", Carbon::now()->subMonths(6))->groupBy('month_year')->pluck('total_orderrs','month_year')->toArray();

        $shared_service_orders=SharedServiceOrder::select(DB::raw('count(*) as total_orderrs,  DATE_FORMAT(created_at, "%m-%Y") as month_year'))->where("created_at",">", Carbon::now()->subMonths(6))->groupBy('month_year')->pluck('total_orderrs','month_year')->toArray();

       

        $current_monyh_year=Carbon::now()->format('m-Y');

        $last_six_month_array[]=$current_monyh_year;

        $six_months_spare_part_orders[]=(array_key_exists($current_monyh_year,$spare_part_orders))?$spare_part_orders[$current_monyh_year]:0;

        $six_months_shared_service_orders[]=(array_key_exists($current_monyh_year,$shared_service_orders))?$shared_service_orders[$current_monyh_year]:0;
        
        for ($i = 1; $i < 6; $i++) {
          
          $month_year=date('m-Y', strtotime("-$i month"));
          $last_six_month_array[]=$month_year;

          $six_months_spare_part_orders[]=(array_key_exists($month_year,$spare_part_orders))?$spare_part_orders[$month_year]:0;
        
          $six_months_shared_service_orders[]=(array_key_exists($month_year,$shared_service_orders))?$shared_service_orders[$month_year]:0;
        }

        $this->data['last_six_month_array']=$last_six_month_array;
        $this->data['six_months_spare_part_orders']=$six_months_spare_part_orders;

        $this->data['six_months_shared_service_orders']=$six_months_shared_service_orders;

        $this->data['tasks']=TaskLists::where('start_date','>=',date('Y-m-d'))
        ->whereHas('contract')
        ->whereHas('property')
        ->orderBy('start_date','asc')->take(10)->get();


        $this->data['complaint_statuses']=Status::where('status_for','complaint')->get();


        $this->data['complaint_contracts']=Contract::where('creation_complete',true)
        ->whereHas('complaints')
        ->get();

        $this->data['work_order_contracts']=Contract::where('creation_complete',true)
        ->whereHas('work_orders')
        ->get();


        if($request->ajax()){
            //complaint filter
            if($request->complaint_filter){

                $this->data['complaints']=Complaint::whereHas('contract')
                ->whereHas('complaint_status')
                ->when($request->complaint_status_id && $request->complaint_status_id!='all' ,function($q)use ($request){
                    $q->where('status_id',$request->complaint_status_id);
                })
                ->when($request->complaint_contract_id && $request->complaint_contract_id!='all' ,function($q)use ($request){
                    $q->where('contract_id',$request->complaint_contract_id);
                })
                ->orderBy('id','desc')->take(5)->get();

                $complaint_view=view('admin.dashboard.admin.ajax.complaints',$this->data)->render();

                return  response()->json([
                    'html'=>$complaint_view
                ]);
            }





        }


        return view('admin.dashboard.admin.index',$this->data);
    }
    public function customer_dashboard($request,$current_user){

        $this->data['total_contracts']=Contract::where('creation_complete',true)
        ->whereHas('property',function($query)use($current_user){
            if($current_user->created_by_admin){
                $query->where('property_owner',$current_user->id);
            }else{
                $query->where('property_manager',$current_user->id);
            }
        })
        ->count();

        $this->data['total_properties']=Property::where(function($query)use($current_user){
            if($current_user->created_by_admin){
                $query->where('property_owner',$current_user->id);
            }else{
                $query->where('property_manager',$current_user->id);
            }
        })
        ->count();

        $this->data['total_users']=User::whereHas('role')->where('created_by',$current_user->id)->count();

        $this->data['complaints']=Complaint::whereHas('contract')
        ->with('complaint_status')
        ->whereHas('complaint_status')
        ->whereHas('contract.property',function($query) use($current_user){
            if($current_user->created_by_admin){
                $query->where('property_owner',$current_user->id);
            }else{
                $query->where('property_manager',$current_user->id);
            }
        })
        ->orderBy('id','desc')->take(5)->get();

        $this->data['work_orders']=WorkOrderLists::whereHas('contract')
        ->whereHas('service')
        ->whereHas('contract.property',function($query) use($current_user){
            if($current_user->created_by_admin){
                $query->where('property_owner',$current_user->id);
            }else{
                $query->where('property_manager',$current_user->id);
            }
        })
        ->orderBy('id','desc')->take(5)->get();

        $this->data['tasks']=TaskLists::where('start_date','>=',date('Y-m-d'))
        ->whereHas('contract')
        ->whereHas('property',function($query) use($current_user){
            if($current_user->created_by_admin){
                $query->where('property_owner',$current_user->id);
            }else{
                $query->where('property_manager',$current_user->id);
            }
        })
        ->orderBy('id','asc')->take(10)->get();

        return view('admin.dashboard.customer.index',$this->data);
    }
    public function service_provider_dashboard($request,$current_user){
        $this->data['total_labours']=User::where('created_by', $current_user->id)->where('is_deleted', 'N')->whereNull('deleted_by')
        ->whereHas('role',function($q){
            $q->where('slug','labour');
        })->count();

        $this->data['total_contracts']=Contract::where('creation_complete',true)->whereNull('deleted_at')
        ->whereHas('work_orders',function($query) use($current_user){
                $query->where('user_id',$current_user->id);
        })
        ->count();
        $this->data['total_work_orders']=WorkOrderLists::where('user_id',$current_user->id)->where('is_deleted', 'N')->whereNull('deleted_by')->count();

        $this->data['complaints']=Complaint::
        with('complaint_status')
        ->whereHas('contract')
        ->whereHas('complaint_status')
        ->whereHas('work_order',function($query) use($current_user){
                $query->where('user_id',$current_user->id);
        })
        ->orderBy('id','desc')->take(5)->get();

        $this->data['work_orders']=WorkOrderLists::where('user_id', $current_user->id)->with('contract', 'contract_services', 'service')
        ->orderBy('id','desc')->take(5)->get();

        $this->data['tasks']=TaskLists::with('work_order')->where('created_by', $current_user->id)
        ->whereHas('contract')
        ->whereHas('property')
        ->orderBy('id','DESC')->take(10)->get();

        $work_orders=WorkOrderLists::select(DB::raw('count(*) as total_orderrs,  DATE_FORMAT(created_at, "%m-%Y") as month_year'))->where("created_at",">", Carbon::now()->subMonths(12))->where('user_id', $current_user->id)->where('is_deleted', 'N')->groupBy('month_year')->pluck('total_orderrs','month_year')->toArray();

        for ($i = 0; $i < 12; $i++) {

            $month_year=date('m-Y', strtotime("-$i month"));
            $last_twelve_month_work_order_array[]=$month_year;

            

            $twelve_months_work_orders[]=(array_key_exists($month_year,$work_orders))?$work_orders[$month_year]:0;
        }
        $this->data['last_twelve_month_work_order_array']=$last_twelve_month_work_order_array;
        $this->data['twelve_months_work_orders']=$twelve_months_work_orders;
        
        return view('admin.dashboard.service_provider.index',$this->data);
    }
    public function labour_dashboard($request,$current_user){
        return view('admin.dashboard.labour.index',$this->data);
    }
    public function default_dashboard($request,$current_user){
        return view('admin.dashboard.default',$this->data);
    }






   /**************************************************************************/
    # Function to load admin setting page                                      #
    # Function name    : editSetting                                           #
    # Created Date     : 25-05-2020                                            #
    # Modified date    : 25-05-2020                                            #
    # Purpose          : to load admin setting page                            #
    public function editSetting(){
    $this->data['page_title']='Site settings';
     $settings=Setting::get();
     //return view('admin.setting.edit',compact('settings'));
     return view('admin.setting.edit',$this->data)->with(['settings' => $settings]);
    }

    /**************************************************************************/
    # Function to update admin setting                                         #
    # Function name    : editSetting                                           #
    # Created Date     : 25-05-2020                                            #
    # Modified date    : 25-05-2020                                            #
    # Purpose          : to update admin setting                               #
    # Param            : \Illuminate\Http\Request $request                     #
    public function updateSetting(Request $request){
     
     $settings=Setting::get();
     $validations=[];
     if(count($settings)){
       foreach ($settings as $setting) {
        $validations[$setting->slug]='required';
       }
     }

     $request->validate($validations);
     
     //updating all setting data by foreach loop
     if(count($settings)){
       foreach ($settings as $setting) {
        
        if($request->has($setting->slug)){
          $setting->update([
           'value'=>$request->get($setting->slug)
          ]);
        }

       }
     }
     
     //redirecting back with success message
     return redirect()->back()->with('success','Setting successfully updated');

    }

}
