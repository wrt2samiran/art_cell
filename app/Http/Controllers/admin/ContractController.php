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
use App\Models\{ContractStatus,Contract,User,Property,Service,ContractAttachment,ContractInstallment};
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Requests\Admin\Contract\{CreateContractRequest,UpdateContractRequest};
use File;
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
            $contracts=Contract::with(['property','customer','service_provider','services','contract_status'])
            ->whereHas('property')
            ->whereHas('customer')
            ->whereHas('service_provider')
            ->whereHas('services')
            ->whereHas('contract_status')
            ->select('contracts.*');
            return Datatables::of($contracts)
            ->editColumn('created_at', function ($contract) {
                return $contract->created_at ? with(new Carbon($contract->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('start_date', function ($contract) {
                return $contract->start_date ? with(new Carbon($contract->start_date))->format('d/m/Y') : '';
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
                if($has_details_permission){
                    $action_buttons=$action_buttons.'<a title="View Contract Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }

                $has_edit_permission=($current_user->hasAllPermission(['contract-edit']))?true:false;
                if($has_edit_permission){
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
            ->rawColumns(['action','is_active'])
            ->make(true);
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
        $this->data['property_owners']=User::whereStatus('A')->whereHas('role',function($q){
        	$q->where('role_type','property-owner');
        })->get();

        $this->data['service_providers']=User::whereStatus('A')->whereHas('role',function($q){
        	$q->where('role_type','service-provider');
        })->get();

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
        	'description'=>$request->description,
        	'customer_id'=>$request->property_owner,
        	'property_id'=>$request->property,
        	'service_provider_id'=>$request->service_provider,
        	'start_date'=>$start_date,
        	'end_date'=>$end_date,
        	'contract_price'=>$request->contract_price,
        	'contract_price_currency'=>'SAR',
        	'is_paid'=>false,
        	'in_installment'=>($request->in_installment)?true:false,
        	'notify_installment_before_days'=>$request->notify_installment_before_days,
            'contract_status_id'=>$default_contract_status->id,
        	'created_by'=>$current_user->id,
        	'updated_by'=>$current_user->id
        ]);

        $contract->services()->sync($request->services);

        if($request->hasFile('contract_files')){

            foreach ($request->file('contract_files')  as $key=>$contract_file) {

	            $file_name = time().$key.'.'.$contract_file->getClientOriginalExtension();
	         
	            $destinationPath = public_path('/uploads/contract_attachments');
	         
	            $contract_file->move($destinationPath, $file_name);
	            
	            ContractAttachment::create([
	             'contract_id'=>$contract->id,
	             'file_name'=>$file_name,
	             'created_by'=>auth()->guard('admin')->id()
	            ]);

            }
        }

        if($request->in_installment){

        	if(count($request->amount)){
        		foreach ($request->amount as $key=> $amount) {

        			$due_date=Carbon::createFromFormat('d/m/Y', $request->due_date[$key])->format('Y-m-d');

                    $pre_notification_date=Carbon::parse($due_date)->subDays($request->notify_installment_before_days);
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

    	return redirect()->route('admin.contracts.list')->with('success','Contract successfully created');

    }

    /************************************************************************/
    # Function to show/load details page for contract                        #
    # Function name    : show                                                #
    # Created Date     : 14-10-2020                                          #
    # Modified date    : 14-10-2020                                          #
    # Purpose          : show/load details page for contract                 #
    # Param            : id                                                  #

    public function show($id){
        $contract=Contract::with(['property','customer','service_provider','services'])
            ->whereHas('property')
            ->whereHas('customer')
            ->whereHas('service_provider')
            ->whereHas('services')->findOrFail($id);
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
        $this->data['page_title']='Edit Contract';
        $this->data['contract']=$contract;
        $this->data['property_owners']=User::whereStatus('A')->whereHas('role',function($q){
            $q->where('role_type','property-owner');
        })->get();

        $this->data['service_providers']=User::whereStatus('A')->whereHas('role',function($q){
            $q->where('role_type','service-provider');
        })->get();

        $this->data['properties']=Property::whereIsActive(true)->get();
        $this->data['services']=Service::whereIsActive(true)->get();
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

        $current_user=auth()->guard('admin')->user();

        $start_date=Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $end_date=Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

        $contract->update([
            'description'=>$request->description,
            'customer_id'=>$request->property_owner,
            'property_id'=>$request->property,
            'service_provider_id'=>$request->service_provider,
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'contract_price'=>$request->contract_price,
            'contract_price_currency'=>'SAR',
            'is_paid'=>false,
            'in_installment'=>($request->in_installment)?true:false,
            'notify_installment_before_days'=>$request->notify_installment_before_days,
            'contract_status_id'=>($request->contract_status_id)?$request->contract_status_id:$contract->contract_status_id,
            'updated_by'=>$current_user->id
        ]);

        $contract->services()->sync($request->services);

        if($request->hasFile('contract_files')){

            foreach ($request->file('contract_files')  as $key=>$contract_file) {

                $file_name = time().$key.'.'.$contract_file->getClientOriginalExtension();
             
                $destinationPath = public_path('/uploads/contract_attachments');
             
                $contract_file->move($destinationPath, $file_name);
                
                ContractAttachment::create([
                 'contract_id'=>$contract->id,
                 'file_name'=>$file_name,
                 'created_by'=>auth()->guard('admin')->id()
                ]);

            }
        }

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

        return redirect()->route('admin.contracts.list')->with('success','Contract successfully updated.');

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
