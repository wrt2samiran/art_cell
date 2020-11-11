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
use App\Models\{ContractStatus,Contract,User,Property,Service,ContractAttachment,ContractInstallment,FrequencyType,ContractService};
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Requests\Admin\Contract\{CreateContractRequest,UpdateContractRequest};
use File;
use Helper;
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


    public function store_service($contract_id,Request $request){
        $contract=Contract::findOrFail($contract_id);
        $current_user=auth()->guard('admin')->user();
        if($request->frequency_type){
            $frequency_type=FrequencyType::findOrFail($request->frequency_type);
            $interval_days=$frequency_type->no_of_days;
        }else{
            $interval_days=null;
        }

        $service_data=[
        'contract_id'=>$contract->id,
        'service_id'=>$request->service,
        'service_type'=>$request->service_type,
        'currency'=>Helper::getSiteCurrency(),
        'price'=>($request->service_type=='Free')?'0':$request->service_price,
        'frequency_type_id'=>$request->frequency_type,
        'frequency_number'=>$request->frequency_number,
        'interval_days'=>$interval_days,
        'number_of_time_can_used'=>$request->number_of_time_can_used,
        'note'=>$request->note,
        'created_by'=>$current_user->id,
        'updated_by'=>$current_user->id
       ];

       ContractService::create($service_data);
       return redirect()->back()->with('success','Service added');
    }

    public function service_delete($contract_id,$service_id){
        $contract=Contract::findOrFail($contract_id);
        $contract_service=ContractService::findOrFail($service_id);
        
        //if contract creation_complete then there should be atleast one service for the contract
        if($contract->creation_complete){
            if(count($contract->services)=='1'){
                return response()->json(['message'=>'There should be atleast one service for the contract.'],400);
            }
        }
        //todo
        //1)need to check service is belongs to that contract
        //2)need to there is already task to this contract service
 
        $contract_service->delete();
        return response()->json(['message'=>'Service successfully deleted.']);
    }

    public function service_enable_disable($contract_id,$service_id){
        $contract=Contract::findOrFail($contract_id);
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
            }])->findOrFail($service_id);

        $this->data['contract']=$contract;
        $this->data['contract_service']=$contract_service;

        $view=view($this->view_path.'.ajax.contract_service_details',$this->data)->render();
        return response()->json(['html'=>$view]);
    }
    public function payment_info($contract_id){
        $contract=Contract::findOrFail($contract_id);
        if(!count($contract->services)){
            return redirect()->back()->with('error','Please add atleast one service for the contract');
        }
        $this->data['page_title']='Contract Payment Info';
        $this->data['contract']=$contract;
        return view($this->view_path.'.payment_info',$this->data);
    }

    public function store_payment_info($contract_id, Request $request){
        $contract=Contract::findOrFail($contract_id);
        $current_user=auth()->guard('admin')->user();

        $contract->update([
         'contract_price'=>$request->contract_price,
         'contract_price_currency'=>Helper::getSiteCurrency(),
         'is_paid'=>false,
         'in_installment'=>($request->in_installment)?true:false,
         'notify_installment_before_days'=>$request->notify_installment_before_days,
         'updated_by'=>$current_user->id
        ]);

        if($request->in_installment){

            if(count($request->amount)){
                foreach ($request->amount as $key=> $amount) {

                    $due_date=Carbon::createFromFormat('d/m/Y', $request->due_date[$key])->format('Y-m-d');
                    $pre_notification_date=Carbon::parse($due_date)->subDays($request->notify_installment_before_days);

                    if($request->installment_id[$key]!=''){
                        $installment=ContractInstallment::find($request->installment_id[$key]);
                        if($installment){
                            $installment->update([
                                'contract_id'=>$contract->id,
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

        }
        return redirect()->route('admin.contracts.files',$contract->id)->with('success','Payment info updated.');

    }

    public function files($contract_id){

        $contract=Contract::findOrFail($contract_id);
        $this->data['page_title']='Contract Files';
        $this->data['contract']=$contract;
        return view($this->view_path.'.files',$this->data);

    }

    public function store_files($contract_id,Request $request){

        $contract=Contract::findOrFail($contract_id);
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
                        $file_path=public_path().'/uploads/contract_attachments/'.$file->file_name;
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
        //policy is defined in App\Policies\ContractPolicy
        $this->authorize('view',$contract);
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


}
