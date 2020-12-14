<?php
/*******************************************************************/
# Class name     : UserPropertyController                 			#
# Methods  :                                              			#
#    1. list ,                                            			#
#    2. show                                              			#
# Created Date   : 21-10-2020                             			#
# Purpose        : Contract in which logged in user connected       #
/*******************************************************************/
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Status,Contract,User,Property,Service,ContractAttachment,ContractInstallment};
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use Carbon\Carbon;
use File;
class UserContractController extends Controller
{
       //defining the view path
    private $view_path='admin.user_contracts';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for contract list and datatable ajax response                 #
    # Function name    : list                                                #
    # Created Date     : 21-10-2020                                          #
    # Modified date    : 21-10-2020                                          #
    # Purpose          : For contract list and returning Datatables          #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Contract List';
        $current_user=auth()->guard('admin')->user();
        if($request->ajax()){
            $contracts=Contract::with(['property','service_provider','services','contract_status'])
            ->whereHas('property')
            ->whereHas('service_provider')
            ->whereHas('services')
            ->whereHas('contract_status')
            ->where(function($q) use ($current_user){
          
                // if logged in user is the service_provider of the contract 
                $q->where('service_provider_id',$current_user->id)
                //OR if logged in user is the property_owner or added as property manager of the property related to this contract 
                ->orWhereHas('property',function($q1) use ($current_user){
                    $q1->where('property_owner',$current_user->id)
                    ->orWhere('property_manager',$current_user->id);
                });
            })
            ->when($request->contract_status_id,function($query) use($request){
            	$query->where('status_id',$request->contract_status_id);
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
            ->editColumn('created_at', function ($contract) {
                return $contract->created_at ? with(new Carbon($contract->created_at))->format('d/m/Y') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
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
            ->filterColumn('end_date', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(end_date,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('action',function($contract) use ($current_user){
                $details_url=route('admin.user_contracts.show',$contract->id);
                $action_buttons='';
                $action_buttons=$action_buttons.'<a title="View Contract Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';

                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }
        $this->data['ContractStatus']=Status::where('status_for','contract')->whereIsActive(true)->get();
     
        return view($this->view_path.'.list',$this->data);
    }




    /************************************************************************/
    # Function to show/load details page for contract                        #
    # Function name    : show                                                #
    # Created Date     : 21-10-2020                                          #
    # Modified date    : 21-10-2020                                          #
    # Purpose          : show/load details page for contract                 #
    # Param            : id                                                  #

    public function show($id){
        $contract=Contract::with(['property','service_provider','services'])
            ->whereHas('property')
            ->whereHas('services')->findOrFail($id);

        $current_user=auth()->guard('admin')->user();
        //check if the logged in user authorize to view the contract

        //policy is defined in App\Policies\ContractPolicy
        $this->authorize('view_user_connected_contract',$contract);


        $this->data['page_title']='Contract Details';
        $this->data['contract']=$contract;
        return view($this->view_path.'.show',$this->data);

    }



}
