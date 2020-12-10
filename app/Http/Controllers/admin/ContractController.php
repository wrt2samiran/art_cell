<?php
/*********************************************************/
# Class name     : ContractController                     #
# Methods  :                                              #
#    1. list ,                                            #
#    2. create,                                           #
#    3. store                                             #
#    4. show                                              #
#    5. edit                                              #
#    6. update                                            #
#    7. delete                                            #
#    8. download_attachment                               #
#    9. delete_attachment_through_ajax                    #
# Created Date   : 14-10-2020                             #
# Purpose        : Contract management                    #
/*********************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{ContractStatus,Contract,User,Property,Service,ContractAttachment,ContractInstallment,FrequencyType,ContractService,ContractServiceRecurrence,ContractServiceDate,WorkOrderLists};
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use Carbon\{Carbon,CarbonPeriod};
use App\Http\Requests\Admin\Contract\{CreateContractRequest,UpdateContractRequest,StoreFileRequest,StorePaymentInfoRequest,StoreServiceRequest,UpdateServiceRequest};
use File;
use Helper;
use App\Events\Contract\ContractCreated;
class ContractController extends Controller
{
    //defining the view path
    private $view_path='admin.contracts';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for contract list and datatable ajax response                 #
    # Function name    : list                                                #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : For contract list and returning Datatables          #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Contract List';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){
            $contracts=Contract::with(['property','service_provider','services','contract_status'])
            ->where(function($q)use ($current_user){
                //if logged in user is not super admin then fetch only those contracts which are crated by logged in user
                if($current_user->role->user_type->slug!='super-admin'){
                    $q->whereCreatedBy($current_user->id);
                }
            })
            ->whereHas('property')
            ->whereHas('service_provider')

            ->whereHas('contract_status')
            ->when($request->contract_status_id,function($query) use($request){
            	$query->where('contract_status_id',$request->contract_status_id);
            })
            ->when($request->daterange,function($query) use($request){
                $daterange_arr=explode('_',$request->daterange);
                $start_date = $daterange_arr[0];
                $end_date   = $daterange_arr[1];
                $query->where(function($q) use ($start_date,$end_date){
                    $q->where(function($q) use ($start_date,$end_date){
                      $q->where('end_date','>=',$start_date)->where('start_date','<=',$start_date);
                    })
                    ->orWhere(function($q) use ($start_date,$end_date){
                      $q->where('end_date','>=',$end_date)->where('start_date','<=',$end_date);
                    })
                    ->orWhere(function($q) use ($start_date,$end_date){
                      $q->where('end_date','<=',$end_date)->where('start_date','>=',$start_date);
                    });
                });
            })
            
            
            ->select('contracts.*');

            return Datatables::of($contracts)
            ->editColumn('creation_complete', function ($contract) {
                if($contract->creation_complete){
                    return '<span class="text-success">Yes</span>';
                }else{
                    $edit_url=route('admin.contracts.edit',$contract->id);
                    return '<span class="text-danger">No</span><br><a href="'.$edit_url.'">Complete Now<a>';
                }
            })
            ->editColumn('start_date', function ($contract) {
                return $contract->start_date ? with(new Carbon($contract->start_date))->format('d/m/Y') : '';
            })
            ->editColumn('end_date', function ($contract) {
                return $contract->end_date ? with(new Carbon($contract->end_date))->format('d/m/Y') : '';
            })
            ->filterColumn('start_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(start_date,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('action',function($contract) use ($current_user){
            	$delete_url=route('admin.contracts.delete',$contract->id);
                $details_url=route('admin.contracts.show',$contract->id);
                $edit_url=route('admin.contracts.edit',$contract->id);
                $action_buttons='';
                $has_details_permission=($current_user->hasAllPermission(['contract-details']))?true:false;
                if($has_details_permission && $contract->creation_complete){
                    $action_buttons=$action_buttons.'<a title="View Contract Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }

                $has_edit_permission=($current_user->hasAllPermission(['contract-edit']))?true:false;
                if($has_edit_permission && $contract->creation_complete){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit contract" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                }
                $has_delete_permission=($current_user->hasAllPermission(['contract-delete']))?true:false;
                if($has_delete_permission){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete contract" href="javascript:delete_contract('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }

                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;
            })
            ->rawColumns(['action','is_active','creation_complete'])
            ->make(true);
        }
        $this->data['ContractStatus']=ContractStatus::whereIsActive(true)->get();
        $contracts=Contract::whereNull('deleted_at');
        $this->data['contractDuration'] = $contractDuration = isset($request->contract_duration)?$request->contract_duration:'';
        if ($contractDuration != '') {
            if ($contractDuration != '') {
                if (strpos($contractDuration, ' - ') !== false) {
                    $explodedContractDuration = explode(" - ",$contractDuration);
                    $contracts = $contracts->where('start_date', '>=', strtotime($explodedContractDuration[0]))->where('end_date', '<=', strtotime($explodedContractDuration[1]));
                    
                }
            }
        }     
        return view($this->view_path.'.list',$this->data);
    }


    /************************************************************************/
    # Function to load contract create view page                             #
    # Function name    : create                                              #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : To load contract create view page                   #
    public function create(){
        $this->data['page_title']='Create contract';
        $this->data['property_owners']=User::whereStatus('A')->whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
        	$q->where('slug','property-owner');
        })->get();

        $this->data['service_providers']=User::whereStatus('A')->whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
        	$q->where('slug','service-provider');
        })->get();
 
        $this->data['frequency_types']=FrequencyType::get();
        $this->data['properties']=Property::whereIsActive(true)->get();
		$this->data['services']=Service::whereIsActive(true)->get();
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store contract data                                                #
    # Function name    : store                                                       #
    # Created Date     : 14-10-2020                                                  #
    # Modified date    : 14-10-2020                                                  #
    # Purpose          : store contract data                                         #
    # Param            : CreateContractRequest $request                              #

    public function store(CreateContractRequest $request){

    	$current_user=auth()->guard('admin')->user();
    	//system generated contract code
    	$contract_code='CONT'.Carbon::now()->timestamp;
    	$start_date=Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
    	$end_date=Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

        $default_contract_status=ContractStatus::where('is_default_status',true)->first();
        if(!$default_contract_status){
            return redirect()->back()->with('error','No default status found for contract. Please add default status for contract.');
        }

        $contract=Contract::create([
         'code'=>$contract_code,
         'title'=>$request->title,
         'description'=>$request->description,
         'property_id'=>$request->property,
         'service_provider_id'=>$request->service_provider,
         'start_date'=>$start_date,
         'end_date'=>$end_date,
         'contract_status_id'=>$default_contract_status->id,
         'created_by'=>$current_user->id,
         'updated_by'=>$current_user->id
        ]);

    	return redirect()->route('admin.contracts.services',$contract->id);

    }

    public function services($contract_id,Request $request){
        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_service',$contract);
        if($request->ajax()){
            $contract_services=ContractService::with(['service'=>function($q){
                $q->withTrashed();
            }])->where('contract_id',$contract_id)
            ->select('contract_services.*');

            return Datatables::of($contract_services)
            ->editColumn('price',function($contract_service){
                return number_format($contract_service->price, 2, '.', '');
            })
            ->filterColumn('service.service_name', function ($query, $keyword) {
                $query->whereHas('service',function($q) use ($keyword){
                    $q->withTrashed()->whereTranslationLike('service_name', "%{$keyword}%");
                });
            })
            ->orderColumn('services.service_name', function ($query, $order) {
              $query->join('service_translations','services.id','service_translations.id')->orderBy('service_translations.service_name',$order);
            })
            ->editColumn('is_enable',function($contract_service)use($contract){
                $enable_disable_url=route('admin.contracts.service_enable_disable',[$contract->id,$contract_service->id]);
                //maintenance service type can not be enable disable
                $disabled=($contract_service->service_type=='Maintenance')?'disabled':'';

                if($contract_service->is_enable){
                   $message='disable';
                   return '<a title="Click to disable the service" href="javascript:toggle_enable_disable('."'".$enable_disable_url."'".','."'".$message."'".')" class="btn btn-block btn-outline-success btn-sm '.$disabled.'"  >Enable</a>';
                    
                }else{
                   $message='enable';
                   return '<a title="Click to enable the service" href="javascript:toggle_enable_disable('."'".$enable_disable_url."'".','."'".$message."'".')" class="btn btn-block btn-outline-danger btn-sm '.$disabled.'">Disable</a>';
                }

            })
            ->addColumn('action',function($contract_service)use($contract){
                $delete_url=route('admin.contracts.service_delete',[$contract->id,$contract_service->id]);

                $details_url=route('admin.contracts.service_details',[$contract->id,$contract_service->id]);

                $edit_url=route('admin.contracts.services',['contract_id'=>$contract->id,'edit'=>$contract_service->id]);
                $action_buttons='';
                
                $action_buttons=$action_buttons.'<a title="View Service Details" href="javascript:service_details('."'".$details_url."'".')"><i class="fas fa-eye text-primary"></i></a>';

                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Edit contract" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
           
                $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete contract" href="javascript:delete_service('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                return $action_buttons;
            })
            ->rawColumns(['action','is_enable'])
            ->make(true);
        }

        if($request->edit){
            $contract_service=ContractService::findOrFail($request->edit);
            if($contract_service->contract_id!=$contract_id){
                return redirect()->route('admin.contracts.services',$contract_id)
                ->with('error','Service is not belongs to his contract.');
            }

            if($contract_service->service_type=='Maintenance'){
                //check if there is work worder and work order is assigned to labour 
                $assigned_work_order=WorkOrderLists::where('contract_service_id',$contract_service->id)->where('task_assigned','Y')->first();
                if($assigned_work_order){
                    return redirect()->route('admin.contracts.services',$contract_id)
                        ->with('error','Service already assigned by the service provider. You can not edit the service now.');
                }
            }


        }else{
            $contract_service=null;
        }

        $this->data['page_title']='Contract Services';
        $this->data['contract']=$contract;
        $this->data['contract_service']=$contract_service;
        $this->data['frequency_types']=FrequencyType::get();
        $this->data['services']=Service::whereIsActive(true)->get();
        return view($this->view_path.'.services',$this->data);
    }


    public function store_service($contract_id,StoreServiceRequest $request){

        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_service',$contract);
        $current_user=auth()->guard('admin')->user();


        /* this code will be only for maintenance type service */
        if($request->service_type=='Maintenance'){

            $interval_type=$request->interval_type;
            $reccure_every=$request->reccure_every; 
           
            $weekly_days=($interval_type=='weekly')?implode(',',$request->weekly_days) :null; 
            $number_of_times=($interval_type=='daily')?$request->number_of_times :null; 

            if(in_array($interval_type,['monthly','yearly'])){

                $on_or_on_the=($interval_type=='monthly')?$request->on_or_on_the_m:$request->on_or_on_the_y;
                
                if($interval_type=='monthly' && $on_or_on_the=='on'){
                    $day_number=$request->day_number_m;
                }elseif ($interval_type=='yearly' && $on_or_on_the=='on') {
                    $day_number=$request->day_number_y;
                }else{
                    $day_number='1';
                    //default to 1
                }

                if($interval_type=='monthly' && $on_or_on_the=='on_the'){
                    $ordinal=$request->ordinal_m;
                }elseif ($interval_type=='yearly' && $on_or_on_the=='on_the') {
                    $ordinal=$request->ordinal_y;
                }else{
                    $ordinal=null;
                }
            

                if($interval_type=='monthly' && $on_or_on_the=='on_the'){
                    $week_day_name=($interval_type=='monthly')?$request->week_day_name_m:$request->week_day_name_y;
                }else{
                    $week_day_name=null;
                }

                if($interval_type=='yearly'){
                    $month_name=($on_or_on_the=='on')?$request->month_name_y1:$request->month_name_y2;
                }else{
                    $month_name=null;
                }


            }else{
                $on_or_on_the=null;
                $day_number=null;
                $ordinal=null;
                $week_day_name=null;
                $month_name =null; 
            }

            $start_date=Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');

            $end_by_or_after=$request->end_by_or_after;
            if($end_by_or_after=='end_by'){
                $end_date=Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
                $no_of_occurrences=null;
            }else{
                $no_of_occurrences=$request->no_of_occurrences;
                $end_date=null;
            }

            $service_dates=self::get_service_dates_array($interval_type,$reccure_every,$weekly_days,$on_or_on_the,$day_number,$ordinal,$week_day_name,$month_name,$start_date,$end_by_or_after,$no_of_occurrences, $end_date);

            if(!count($service_dates)){
                return redirect()->back()->with('error','No dates found within this recurrence pattern provided');
            }else{
                if(end($service_dates)>$contract->end_date){

                    return redirect()->back()->with('error','Last date for your recurrence exceeds end date of the contract.');
                }
            }
            
        }


        $service_data=[
        'contract_id'=>$contract->id,
        'service_id'=>$request->service,
        'service_type'=>$request->service_type,
        'currency'=>Helper::getSiteCurrency(),
        'price'=>($request->service_type=='Free')?'0':$request->service_price,
        'number_of_time_can_used'=>$request->number_of_time_can_used,
        'note'=>$request->note,
        'consider_as_on_demand'=>($request->service_type=='Maintenance' && $request->consider_as_on_demand)?true:false,
        'created_by'=>$current_user->id,
        'updated_by'=>$current_user->id
        ];
        $contract_service=ContractService::create($service_data);
       
        if($request->service_type=='Maintenance'){

            $recurrence=ContractServiceRecurrence::create([
                'contract_service_id'=>$contract_service->id,
                'interval_type'=>$interval_type,
                'number_of_times'=>$number_of_times,
                'reccure_every'=>$reccure_every,
                'weekly_days'=>$weekly_days,
                'on_or_on_the'=>$on_or_on_the,
                'day_number'=>$day_number,
                'ordinal'=>$ordinal,
                'week_day_name'=>$week_day_name,
                'month_name'=>$month_name,
                'start_date'=>$start_date,
                'end_by_or_after'=>$end_by_or_after,
                'no_of_occurrences'=>$no_of_occurrences,
                'end_date'=>$end_date
            ]);

            $service_dates_array=array_map(function($date) use($recurrence,$contract_service,$number_of_times){
                return [
                    'recurrence_id'=>$recurrence->id,
                    'contract_service_id'=>$recurrence->contract_service_id,
                    'service_id'=>$contract_service->service_id,
                    'contract_id'=>$contract_service->contract_id,
                    'date'=>$date,
                    'number_of_times'=>$number_of_times
                ];
            },$service_dates);

            ContractServiceDate::insert($service_dates_array);

            $task_title=ucfirst($interval_type).' '.$contract_service->service->service_name;

            WorkOrderLists::create([
                'contract_id'=>$contract_id,
                'contract_service_id'=>$contract_service->id,
                'service_id'=>$contract_service->service_id,
                'property_id'=>$contract->property_id,
                'user_id'=>$contract->service_provider_id,
                'task_title'=>$task_title,
                'task_desc'=>$contract_service->note,
                'start_date'=>$service_dates[0],
                'created_by'=>$current_user->id
            ]);

            if($request->consider_as_on_demand){
                //when a maintenance service will create as well as an on demand service then we will create an on demand servcie 
                $on_demand_service_data=[
                'contract_id'=>$contract->id,
                'service_id'=>$request->service,
                'service_type'=>'On Demand',
                'currency'=>Helper::getSiteCurrency(),
                'price'=>$request->service_price,
                'number_of_time_can_used'=>$request->number_of_time_can_used,
                'note'=>$request->note,
                'created_along_contract_service_id'=>$contract_service->id,
                'created_by'=>$current_user->id,
                'updated_by'=>$current_user->id
                ];
                ContractService::create($on_demand_service_data);
            }

        }

        return redirect()->back()->with('success','Service added');
    }


    public function update_service($contract_id,$contract_service_id,UpdateServiceRequest $request){

        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_service',$contract);
        $current_user=auth()->guard('admin')->user();

        $contract_service=ContractService::findOrFail($contract_service_id);
        if($contract_service->contract_id!=$contract_id){
            return redirect()->route('admin.contracts.services',$contract_id)
            ->with('error','Service is not belongs to his contract.');
        }


        /* this code will be only for maintenance type service */
        if($contract_service->service_type=='Maintenance'){


            //check if there is work worder and work order is assigned to labour 
            $assigned_work_order=WorkOrderLists::where('contract_service_id',$contract_service->id)->where('task_assigned','Y')->first();
            if($assigned_work_order){
            return redirect()->route('admin.contracts.services',$contract_id)
                ->with('error','Service already assigned by the service provider. You can not edit the service now.');
            }
            $interval_type=$request->interval_type;
            $reccure_every=$request->reccure_every; 
           
            $weekly_days=($interval_type=='weekly')?implode(',',$request->weekly_days) :null;
            $number_of_times=($interval_type=='daily')?$request->number_of_times :null;  

            if(in_array($interval_type,['monthly','yearly'])){

                $on_or_on_the=($interval_type=='monthly')?$request->on_or_on_the_m:$request->on_or_on_the_y;
                
                if($interval_type=='monthly' && $on_or_on_the=='on'){
                    $day_number=$request->day_number_m;
                }elseif ($interval_type=='yearly' && $on_or_on_the=='on') {
                    $day_number=$request->day_number_y;
                }else{
                    $day_number='1';
                    //default to 1
                }

                if($interval_type=='monthly' && $on_or_on_the=='on_the'){
                    $ordinal=$request->ordinal_m;
                }elseif ($interval_type=='yearly' && $on_or_on_the=='on_the') {
                    $ordinal=$request->ordinal_y;
                }else{
                    $ordinal=null;
                }
            

                if($interval_type=='monthly' && $on_or_on_the=='on_the'){
                    $week_day_name=($interval_type=='monthly')?$request->week_day_name_m:$request->week_day_name_y;
                }else{
                    $week_day_name=null;
                }

                if($interval_type=='yearly'){
                    $month_name=($on_or_on_the=='on')?$request->month_name_y1:$request->month_name_y2;
                }else{
                    $month_name=null;
                }


            }else{
                $on_or_on_the=null;
                $day_number=null;
                $ordinal=null;
                $week_day_name=null;
                $month_name =null; 
            }

            $start_date=Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');

            $end_by_or_after=$request->end_by_or_after;
            if($end_by_or_after=='end_by'){
                $end_date=Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');
                $no_of_occurrences=null;
            }else{
                $no_of_occurrences=$request->no_of_occurrences;
                $end_date=null;
            }

            $service_dates=self::get_service_dates_array($interval_type,$reccure_every,$weekly_days,$on_or_on_the,$day_number,$ordinal,$week_day_name,$month_name,$start_date,$end_by_or_after,$no_of_occurrences, $end_date);

            if(!count($service_dates)){
                return redirect()->back()->with('error','No dates found within this recurrence pattern provided');

            }else{
                if(end($service_dates)>$contract->end_date){

                    return redirect()->back()->with('error','Last date for your recurrence exceeds end date of the contract.');
                }
            }
            
        }


        $service_data=[
        'contract_id'=>$contract->id,
        'service_id'=>$request->service,
        'service_type'=>($contract_service->service_type=='Maintenance')?$contract_service->service_type:$request->service_type,
        'currency'=>Helper::getSiteCurrency(),
        'price'=>($request->service_type=='Free')?'0':$request->service_price,
        'number_of_time_can_used'=>$request->number_of_time_can_used,
        'note'=>$request->note,
        'updated_by'=>$current_user->id
        ];
        $contract_service->update($service_data);
       
        if($contract_service->service_type=='Maintenance'){

            ContractServiceRecurrence::where('contract_service_id',$contract_service->id)->delete();
            ContractServiceDate::where('contract_service_id',$contract_service->id)->delete();

            $recurrence=ContractServiceRecurrence::create([
                'contract_service_id'=>$contract_service->id,
                'interval_type'=>$interval_type,
                'number_of_times'=>$number_of_times,
                'reccure_every'=>$reccure_every,
                'weekly_days'=>$weekly_days,
                'on_or_on_the'=>$on_or_on_the,
                'day_number'=>$day_number,
                'ordinal'=>$ordinal,
                'week_day_name'=>$week_day_name,
                'month_name'=>$month_name,
                'start_date'=>$start_date,
                'end_by_or_after'=>$end_by_or_after,
                'no_of_occurrences'=>$no_of_occurrences,
                'end_date'=>$end_date
            ]);

            $service_dates_array=array_map(function($date) use($recurrence,$contract_service,$number_of_times){
                return [
                    'recurrence_id'=>$recurrence->id,
                    'contract_service_id'=>$recurrence->contract_service_id,
                    'service_id'=>$contract_service->service_id,
                    'contract_id'=>$contract_service->contract_id,
                    'date'=>$date,
                    'number_of_times'=>$number_of_times,

                ];
            },$service_dates);

            ContractServiceDate::insert($service_dates_array);

            $work_order=WorkOrderLists::where('contract_service_id',$contract_service->id)->first();

            $task_title=ucfirst($interval_type).' '.$contract_service->service->service_name;
            if($work_order){
                $work_order->update([
                    'contract_id'=>$contract_id,
                    'service_id'=>$contract_service->service_id,
                    'property_id'=>$contract->property_id,
                    'user_id'=>$contract->service_provider_id,
                    'task_title'=>$task_title,
                    'task_desc'=>$contract_service->note,
                    'start_date'=>$service_dates[0],
                    'updated_by'=>$current_user->id
                ]);

            }else{
                WorkOrderLists::create([
                    'contract_id'=>$contract_id,
                    'contract_service_id'=>$contract_service->id,
                    'service_id'=>$contract_service->service_id,
                    'property_id'=>$contract->property_id,
                    'user_id'=>$contract->service_provider_id,
                    'task_title'=>$task_title,
                    'task_desc'=>$contract_service->note,
                    'start_date'=>$service_dates[0],
                    'created_by'=>$current_user->id
                ]);
            }

        }

        return redirect()->route('admin.contracts.services',$contract_id)
                ->with('success','Service updated.');
    }

    public function service_delete($contract_id,$service_id){
        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_service',$contract);
        $contract_service=ContractService::findOrFail($service_id);
        
        //if contract creation_complete then there should be atleast one service for the contract
        if($contract->creation_complete){
            if(count($contract->services)=='1'){
                return response()->json(['message'=>'There should be atleast one service for the contract.'],400);
            }
        }
           
        //check if there is work worder and work order is assigned to labour 
        $assigned_work_order=WorkOrderLists::where('contract_service_id',$contract_service->id)->where('task_assigned','Y')->first();
        if($assigned_work_order){
            return response()->json(['message'=>'Service already assigned by the service provider. You can not edit the service now..'],400);
        }
      
        
        if($contract_service->service_type=='Maintenance'){
            ContractServiceRecurrence::where('contract_service_id',$contract_service->id)->delete();
            ContractServiceDate::where('contract_service_id',$contract_service->id)->delete();
        }

        WorkOrderLists::where('contract_service_id',$contract_service->id)->delete();
        $contract_service->delete();

        return response()->json(['message'=>'Service successfully deleted.']);
    }

    public function service_enable_disable($contract_id,$service_id){
        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_service',$contract);
        $contract_service=ContractService::findOrFail($service_id);

        $change_status_to=($contract_service->is_enable)?false:true;
        $message=($contract_service->is_enable)?'disabled':'enabled';

         //updating gallery status
        $contract_service->update([
            'is_enable'=>$change_status_to
        ]);
        //returning json success response
        return response()->json(['message'=>'Contract service successfully '.$message.'.']);
    }

    public function service_details($contract_id,$service_id){
         
        $contract=Contract::findOrFail($contract_id);
        $contract_service=ContractService::with(['service'=>function($q){
                $q->withTrashed();
            },'recurrence_details'])->findOrFail($service_id);

        $this->data['contract']=$contract;
        $this->data['contract_service']=$contract_service;

        $view=view($this->view_path.'.ajax.contract_service_details',$this->data)->render();
        return response()->json(['html'=>$view]);
    }
    public function payment_info($contract_id){
        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_payment_info',$contract);
        if(!count($contract->services)){
            return redirect()->back()->with('error','Please add atleast one service for the contract');
        }
        $this->data['services_price_total']=$contract->services_price_total();

        $this->data['page_title']='Contract Payment Info';
        $this->data['contract']=$contract;
        return view($this->view_path.'.payment_info',$this->data);
    }

    public function store_payment_info($contract_id, StorePaymentInfoRequest $request){
        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_payment_info',$contract);
        $current_user=auth()->guard('admin')->user();


        $contract->update([
         'contract_price'=>$request->contract_price,
         'contract_price_currency'=>Helper::getSiteCurrency(),
         'is_paid'=>false,
         'in_installment'=>($request->in_installment)?true:false,
         'absolute_or_percentage'=>$request->absolute_or_percentage,
         'notify_installment_before_days'=>$request->notify_installment_before_days,
         'updated_by'=>$current_user->id
        ]);

        if($request->in_installment){
            
            if(count($request->amount)){
                foreach ($request->amount as $key=> $amount) {

                    $due_date=Carbon::createFromFormat('d/m/Y', $request->due_date[$key])->format('Y-m-d');
                    $pre_notification_date=Carbon::parse($due_date)->subDays($request->notify_installment_before_days);

                    $absolute_or_percentage=$request->absolute_or_percentage;

                    if($absolute_or_percentage=='percentage'){
                        $percentage=$amount;
                        $amount=($percentage/100)* $request->contract_price;
                    }else{
                        $percentage=null;
                        $amount=$amount;
                    }

                    if($request->installment_id[$key]!=''){
                        $installment=ContractInstallment::find($request->installment_id[$key]);
                        if($installment){
                            $installment->update([
                                'contract_id'=>$contract->id,
                                'percentage'=>$percentage,
                                'price'=>$amount,
                                'currency'=>'SAR',
                                'due_date'=>$due_date,
                                'pre_notification_date'=>$pre_notification_date,
                                'updated_by'=>$current_user->id
                            ]);    
                        }

                    }else{
                        ContractInstallment::create([
                            'contract_id'=>$contract->id,
                            'percentage'=>$percentage,
                            'price'=>$amount,
                            'currency'=>'SAR',
                            'due_date'=>$due_date,
                            'pre_notification_date'=>$pre_notification_date,
                            'created_by'=>$current_user->id,
                            'updated_by'=>$current_user->id
                        ]);  
                    }
                }
            }

        }else{
            ContractInstallment::where('contract_id',$contract->id)->delete();
        }
        return redirect()->route('admin.contracts.files',$contract->id)->with('success','Payment info updated.');

    }

    public function files($contract_id){
        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_file',$contract);
        $this->data['page_title']='Contract Files';
        $this->data['contract']=$contract;
        return view($this->view_path.'.files',$this->data);
    }

    public function store_files($contract_id,StoreFileRequest $request){

        $contract=Contract::findOrFail($contract_id);
        $this->authorize('store_file',$contract);
        $current_user=auth()->guard('admin')->user();
        if(isset($request->title) && count($request->title)){
            foreach ($request->title as $key => $value) {

                if($request->file_id[$key]!=''){
                     
                    $contract_file=ContractAttachment::find($request->file_id[$key]);

                }else{
                    $contract_file=null;
                }
                
                if($contract_file){

                    //if image is updating then remove previous imageand upload ne one
                    if($request->hasFile('contract_files') && isset($request->file('contract_files')[$key])){

                        $file=$request->file('contract_files')[$key];
                        //remove previous file if exists
                        $file_path=public_path().'/uploads/contract_attachments/'.$contract_file->file_name;
                        if(File::exists($file_path)){
                            File::delete($file_path);
                        }
                        //upload new file
                        $file_name = 'contract-file-'.time().$key.'.'.$file->getClientOriginalExtension();
     
                        $destinationPath = public_path('/uploads/contract_attachments');
                     
                        $file->move($destinationPath, $file_name);
                        $mime_type=$file->getClientMimeType();

                        $file_type=Helper::get_file_type_by_mime_type($mime_type);

                    }else{
                        $file_name=$contract_file->file_name;
                        $file_type=$contract_file->file_type;
                    }

                    $contract_file->update([
                     'file_name'=>$file_name,
                     'file_type'=>$file_type,
                     'title'=>$request->title[$key],
                     'created_by'=>$current_user->id
                    ]);

                }else{
                    //if it is new file
                    if($request->hasFile('contract_files') && isset($request->file('contract_files')[$key])){

                        $file=$request->file('contract_files')[$key];
                        //upload new file
                        $file_name = 'contract-file-'.time().$key.'.'.$file->getClientOriginalExtension();
     
                        $destinationPath = public_path('/uploads/contract_attachments');
                     
                        $file->move($destinationPath, $file_name);
                        $mime_type=$file->getClientMimeType();

                        $file_type=Helper::get_file_type_by_mime_type($mime_type);

                        ContractAttachment::create([
                            'contract_id'=>$contract->id,
                            'file_name'=>$file_name,
                            'file_type'=>$file_type,
                            'title'=>$request->title[$key],
                            'created_by'=>$current_user->id
                        ]);

                    }


                }


            }
        }
        
        //update contract status creation_complete to true
        if($contract->creation_complete){
            return redirect()->route('admin.contracts.list')->with('success','Contract successfully updated.');
        }else{
            $contract->update([
                'creation_complete'=>true
            ]);

            event(new ContractCreated($contract));

            return redirect()->route('admin.contracts.list')->with('success','Contract successfully created.');
        }
        

    }
    /************************************************************************/
    # Function to show/load details page for contract                        #
    # Function name    : show                                                #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : show/load details page for contract                 #
    # Param            : id                                                  #

    public function show($id){
        $contract=Contract::with(['property','service_provider','services'])
            ->whereHas('property')
            ->whereHas('service_provider')
            ->findOrFail($id);
        $this->data['page_title']='Contract Details';
        $this->data['contract']=$contract;
        return view($this->view_path.'.show',$this->data);

    }

    /************************************************************************/
    # Function to load contract edit page                                    #
    # Function name    : edit                                                #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : to load contract edit page                          #
    # Param            : id                                                  #
    public function edit($id){
        $contract=Contract::findOrFail($id);
        //policy is defined in App\Policies\ContractPolicy
        $this->authorize('update',$contract);
        $this->data['page_title']='Edit Contract';
        $this->data['contract']=$contract;


        $this->data['service_providers']=User::whereStatus('A')
        ->whereHas('role')
        ->whereHas('role.creator')
        ->whereHas('role.user_type',function($q){
            $q->where('slug','service-provider');
        })->get();

        $this->data['frequency_types']=FrequencyType::get();

        $this->data['properties']=Property::whereIsActive(true)->get();

        $this->data['contract_statuses']=ContractStatus::where('is_active',true)->get();
        return view($this->view_path.'.edit',$this->data);
    }

    /************************************************************************************/
    # Function to update contract data                                                   #
    # Function name    : update                                                          #
    # Created Date     : 14-10-2020                                                      #
    # Modified date    : 14-10-2020                                                      #
    # Purpose          : to update contract data                                         #
    # Param            : UpdateContractRequest $request,id                               #
    public function update(UpdateContractRequest $request,$id){

    	$contract=Contract::findOrFail($id);
        //policy is defined in App\Policies\ContractPolicy
        $this->authorize('update',$contract);
        $current_user=auth()->guard('admin')->user();

        $start_date=Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $end_date=Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

        $contract->update([
         'title'=>$request->title,
         'description'=>$request->description,
         'property_id'=>$request->property,
         'service_provider_id'=>$request->service_provider,
         'start_date'=>$start_date,
         'end_date'=>$end_date,
         'contract_status_id'=>($request->contract_status_id)?$request->contract_status_id:$contract->contract_status_id,
         'updated_by'=>$current_user->id
        ]);

        return redirect()->route('admin.contracts.services',$contract->id)->with('success','Contract info updated.');

    }

    /************************************************************************/
    # Function to delete contract                                            #
    # Function name    : delete                                              #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : to delete contract                                  #
    # Param            : id                                                  #
    public function delete($id){
        $contract=Contract::findOrFail($id);
        //todo need to check contact is running or not
        $contract->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $contract->delete();
        return response()->json(['message'=>'Contract successfully deleted.']);
    }


    /************************************************************************/
    # Function to download attachment by file id                             #
    # Function name    : download_attachment                                 #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : to download attachment by file id                   #
    # Param            : id                                                  #
    public function download_attachment($id){
        $file_data=ContractAttachment::findOrFail($id);
        $file_path=public_path().'/uploads/contract_attachments/'.$file_data->file_name;
        return response()->download($file_path,$file_data->file_name);
    }

    /************************************************************************/
    # Function to delete attachment by file id                               #
    # Function name    : delete_attachment_through_ajax                      #
    # Created Date     : 16-10-2020                                          #
    # Modified date    : 16-10-2020                                          #
    # Purpose          : to delete attachment by file id                     #
    # Param            : id                                                  #
    public function delete_attachment_through_ajax($id){
        $file_data=ContractAttachment::findOrFail($id);
        
        $file_path=public_path().'/uploads/contract_attachments/'.$file_data->file_name;
        if(File::exists($file_path)){
            File::delete($file_path);
        }
        $file_data->delete();
        return response()->json(['message'=>'Attachement file successfully deleted']);
    }



    public static function get_service_dates_array($interval_type,$reccure_every,$weekly_days,$on_or_on_the,$day_number,$ordinal,$week_day_name,$month_name,$start_date,$end_by_or_after,$no_of_occurrence, $end_date){
        
        if($no_of_occurrence){
            $no_of_occurrence=(int)$no_of_occurrence;
        }

        $service_dates_array=[];
      
        if($interval_type=='daily'){

            $day_interval=$reccure_every.' days';
            
            if($end_date){
               $dates=CarbonPeriod::create($start_date,$day_interval, $end_date);
            }else{
               $dates=CarbonPeriod::create($start_date, $no_of_occurrence,$day_interval);
            }
            
            if(count($dates)){
                foreach ($dates as $key => $date) {
                    $service_dates_array[] = $date->format('Y-m-d');
                }
            }

        }elseif($interval_type=='weekly'){

            if($end_date){

                $last_week_last_date=Carbon::parse($end_date)->endOfWeek()->format('Y-m-d');
                
                $main_start_date=$start_date;

                
                while ($start_date <= $last_week_last_date) {

                    $start_date_of_week=Carbon::parse($start_date)->startOfWeek()->format('Y-m-d');
                    $end_date_of_week=Carbon::parse($start_date)->endOfWeek()->format('Y-m-d');

                    $dates=CarbonPeriod::create($start_date_of_week,$end_date_of_week);

                    foreach ($dates as $key => $date) {
                        if(in_array($date->format('l'), explode(',', $weekly_days))){

                            if($date->format('Y-m-d')>=$main_start_date && $date->format('Y-m-d')<=$end_date){
                                $service_dates_array[] = $date->format('Y-m-d');
                            }
                            
                        }
                    }
                    $start_date=Carbon::parse($start_date)->addWeek($reccure_every);
                }


            }else{


                $main_start_date=$start_date;
                $current_number=1;
            
                while ($current_number <= $no_of_occurrence) {

                    $start_date_of_week=Carbon::parse($start_date)->startOfWeek()->format('Y-m-d');
                    $end_date_of_week=Carbon::parse($start_date)->endOfWeek()->format('Y-m-d');

                    $dates=CarbonPeriod::create($start_date_of_week,$end_date_of_week);

                    foreach ($dates as $key => $date) {
                        if(in_array($date->format('l'), explode(',', $weekly_days))){

                            if($date->format('Y-m-d')>=$main_start_date){
                                $service_dates_array[] = $date->format('Y-m-d');
                            }
                            
                        }
                    }
                    $start_date=Carbon::parse($start_date)->addWeek($reccure_every);
                    $current_number++;
                }

            }


        }elseif($interval_type=='monthly'){

 
            $main_start_date=$start_date;
            $firstMonthFirstDay = Carbon::parse($start_date)->firstOfMonth();
            $first_date=$firstMonthFirstDay->format('Y-m-d');
            
            // dd($firstDay->addMonth(2));
            if($end_date){

                $last_month_last_date=Carbon::parse($end_date)->lastOfMonth()->format('Y-m-d');

                while ($first_date <= $last_month_last_date) {
                    $month_any_year=Carbon::parse($first_date)->format('F Y');

                    if($on_or_on_the=='on'){
                        $date_string=$day_number.' '.$month_any_year;
                        $date=Carbon::parse($date_string);
                    }elseif($on_or_on_the=='on_the'){
                        $date_string=$ordinal.' '.$week_day_name.' of '.$month_any_year;
                        $date=Carbon::parse($date_string);
                    }

                    if($date->format('Y-m-d')>=$main_start_date && $date->format('Y-m-d')<=$end_date){
                        $service_dates_array[] = $date->format('Y-m-d');
                    }

                    $first_date=Carbon::parse($first_date)->addMonth($reccure_every)->format('Y-m-d');
                }

            }else{

                $current_number=1;
                while ($current_number <= $no_of_occurrence) {

                    $month_any_year=Carbon::parse($first_date)->format('F Y');

                    if($on_or_on_the=='on'){
                        $date_string=$day_number.' '.$month_any_year;
                        $date=Carbon::parse($date_string);
                    }elseif($on_or_on_the=='on_the'){
                        $date_string=$ordinal.' '.$week_day_name.' of '.$month_any_year;
                        $date=Carbon::parse($date_string);
                    }

                    if($date->format('Y-m-d')>=$main_start_date){
                        $service_dates_array[] = $date->format('Y-m-d');
                    }

                    $first_date=Carbon::parse($first_date)->addMonth($reccure_every)->format('Y-m-d');
                    $current_number++;
                }
            }


        }elseif($interval_type=='yearly'){

            $main_start_date=$start_date;

            $firstYearFirstDay = Carbon::parse($start_date)->startOfYear();
            $first_date=$firstYearFirstDay->format('Y-m-d');
            
            // dd($firstDay->addMonth(2));
            if($end_date){

                $last_year_last_date=Carbon::parse($end_date)->endOfYear()->format('Y-m-d');

                if($firstYearFirstDay->format('Y')==Carbon::parse($end_date)->endOfYear()->format('Y')){

                    $year=Carbon::parse($first_date)->format('Y');

                    if($on_or_on_the=='on'){
                        $date_string=$day_number.' '.$month_name.' '.$year;
                        $date=Carbon::parse($date_string);
                    }elseif($on_or_on_the=='on_the'){

                        $date_string=$ordinal.' '.$week_day_name.' of '.$month_name.' '.$year;
                        $date=Carbon::parse($date_string);
                    }

                    if($date->format('Y-m-d')>=$main_start_date && $date->format('Y-m-d')<=$end_date){
                        $service_dates_array[] = $date->format('Y-m-d');
                    }

                }else{
                    while ($first_date <= $last_year_last_date) {

                        $year=Carbon::parse($first_date)->format('Y');

                        if($on_or_on_the=='on'){
                            $date_string=$day_number.' '.$month_name.' '.$year;
                            $date=Carbon::parse($date_string);
                        }elseif($on_or_on_the=='on_the'){

                            $date_string=$ordinal.' '.$week_day_name.' of '.$month_name.' '.$year;
                            $date=Carbon::parse($date_string);
                        }

                        if($date->format('Y-m-d')>=$main_start_date && $date->format('Y-m-d')<=$end_date){
                            $service_dates_array[] = $date->format('Y-m-d');
                        }

                        $first_date=Carbon::parse($first_date)->addYear($reccure_every)->format('Y-m-d');
                    }
                }


            }else{

                $current_number=1;
                while ($current_number <= $no_of_occurrence) {

                    $year=Carbon::parse($first_date)->format('Y');

                    if($on_or_on_the=='on'){
                        $date_string=$day_number.' '.$month_name.' '.$year;
                        $date=Carbon::parse($date_string);
                    }elseif($on_or_on_the=='on_the'){
                        
                        $date_string=$ordinal.' '.$week_day_name.' of '.$month_name.' '.$year;
                        $date=Carbon::parse($date_string);
                    }

                    if($date->format('Y-m-d')>=$main_start_date){
                        $service_dates_array[] = $date->format('Y-m-d');
                    }

                    $first_date=Carbon::parse($first_date)->addYear($reccure_every)->format('Y-m-d');
                    $current_number++;
                }
            }

        }

        return $service_dates_array;
    }

}
