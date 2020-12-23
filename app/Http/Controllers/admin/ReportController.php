<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Contract,Property,WorkOrderLists,User,Service};
use App\Exports\Reports\Admin\{ScheduleCompliance,WorkOrder};
use Maatwebsite\Excel\Facades\Excel;

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

    public function schedule_compliance_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function planned_maintenance_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function maintenance_backlog_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function open_preventive_maintenance_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function upcoming_weekly_maintenance_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function planned_two_weekly_maintenance_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function upcoming_schedule_maintenance_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function work_order_report(Request $request){

        $data=[];
        return Excel::download(new WorkOrder($data),'work-order-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function work_order_completed_per_month_report(Request $request){
        
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function work_order_requested_vs_completed_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }
    public function contract_status_report(Request $request){
        $data=[];
        return Excel::download(new ScheduleCompliance($data),'schedule-compliance-report.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function admin_report($request,$current_user){
        $this->data['contracts']=Contract::whereHas('property')
        ->whereHas('property.owner_details')
        ->whereHas('contract_status')
        ->whereHas('service_provider')
        ->where('creation_complete',true)
        ->orderBy('id','desc')->get();

    	$this->data['properties']=Property::whereHas('owner_details')
        ->orderBy('id','desc')->get();

        $this->data['service_providers']=User::whereStatus('A')->whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','service-provider');
        })->get();

        $this->data['labours']=User::whereStatus('A')->whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','labour');
        })->get();

        $this->data['services']=Service::whereIsActive(true)->get();

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
