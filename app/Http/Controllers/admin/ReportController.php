<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Contract,Property,WorkOrderLists};
class ReportController extends Controller
{
    public $data = array();            

    /*****************************************************/
    # Function name : index
    # Created Date  : 18-12-2020
    # Purpose       : To Load Report View Page                
    # Params        : 
    /*****************************************************/

    public function index(Request $request)
    {
        $this->data['page_title'] = 'Report';
        $this->data['current_user']=$current_user=auth()->guard('admin')->user();
        $user_type=$current_user->role->user_type->slug;
        
        switch ($user_type) {
            case "super-admin":
                return $this->admin_report($request,$current_user);
            break;
            case "property-owner":
                return $this->customer_report($request,$current_user);
            break;
            case "service-provider":
                return $this->service_provider_report($request,$current_user);
            break;
            case "labour":
                return $this->labour_report($request,$current_user);
            break;
            default:
                return $this->default_report($request,$current_user);
        }
        
    }

    public function admin_report($request,$current_user){
    	return view('admin.report.admin.index',$this->data);
    }
    public function customer_report($request,$current_user){

        if($request->isMethod('post')){
            dd('Comming Soon');
        }
    	return view('admin.report.customer.index',$this->data);
    }
    public function service_provider_report($request,$current_user){
    	return view('admin.report.service_provider.index',$this->data);
    }
    public function labour_report($request,$current_user){
        return view('admin.report.labour.index',$this->data);
    }
    public function default_report($request,$current_user){
       return view('admin.dashboard.default',$this->data); 
    }
}
