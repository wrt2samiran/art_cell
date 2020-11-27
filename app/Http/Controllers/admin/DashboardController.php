<?php

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{User,Setting,Country,State,City,TaskLists, TaskDetails, Property, Contract, ContractService};
use Yajra\Datatables\Datatables;
use Config;
use Carbon\Carbon;

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
        $current_user=auth()->guard('admin')->user();
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
            case "labourr":
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

        return view('admin.dashboard.admin.index',$this->data);
    }
    public function customer_dashboard($request,$current_user){
        return view('admin.dashboard.customer.index',$this->data);
    }
    public function service_provider_dashboard($request,$current_user){
        return view('admin.dashboard.service_provider.index',$this->data);
    }
    public function labour_dashboard($request,$current_user){
        return view('admin.dashboard.labour.index',$this->data);
    }
    public function default_dashboard($request,$current_user){
        return view('admin.dashboard.default.index',$this->data);
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
