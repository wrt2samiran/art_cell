<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{Quotation,QuotationStatus,Service,PropertyType,QuotationService,State};
use App\Http\Requests\Admin\SubmitQuotationRequest;
class QuotationController extends Controller
{
    private $view_path='admin.quotations';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Quotation List';

        if($request->ajax()){

            $quotations=Quotation::with(['services','property_types','city','state','status'])
            ->when($request->service,function($query) use($request){
            	$query->whereHas('services',function($sub_query)use ($request){
            		$sub_query->where('service_id',$request->service);
            	});
            })
            ->when($request->property_type,function($query) use($request){
            	$query->whereHas('property_types',function($sub_query)use ($request){
            		$sub_query->where('property_types.id',$request->property_type);
            	});
            })
            ->select('quotations.*');
            return Datatables::of($quotations)
            ->editColumn('created_at', function ($quotation) {
                return $quotation->created_at ? with(new Carbon($quotation->created_at))->format('d/m/Y') : '';
            })
            ->editColumn('details', function ($quotation) {
                return Str::limit($quotation->details,50);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('first_name', function ($quotation) {
                return $quotation->first_name.' '.$quotation->last_name ;
            })

            ->filterColumn('first_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(first_name,last_name) like ?", ["%$keyword%"]);
            })
            ->addColumn('services', function (Quotation $quotation) {
                return $quotation->services->map(function($service) {
                    return $service->service->service_name;
                })->implode(',<br>');
            })
            ->addColumn('property_types', function (Quotation $quotation) {
                return $quotation->property_types->map(function($property_type) {
                    return $property_type->type_name;
                })->implode(',<br>');
            })
            ->addColumn('action',function($quotation){
                $delete_url=route('admin.quotations.delete',$quotation->id);
                $details_url=route('admin.quotations.show',$quotation->id);
                $action_buttons='';
                //need to check permissions later
                if(true){
                    $action_buttons=$action_buttons.'<a title="View Quotation Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>';
                }
                if(true){
                    $action_buttons=$action_buttons.'&nbsp;&nbsp;<a title="Delete quotation" href="javascript:delete_quotation('."'".$delete_url."'".')"><i class="far fa-minus-square text-danger"></i></a>';
                }
                
                if($action_buttons==''){
                    $action_buttons=$action_buttons.'<span class="text-muted">No access</span>';
                } 
                return $action_buttons;
            })
            ->rawColumns(['action','services','property_types'])
            ->make(true);
        }

        $this->data['services']=Service::whereIsActive(true)->get();
        $this->data['property_types']=PropertyType::whereIsActive(true)->get();
        return view($this->view_path.'.list',$this->data);
    }

    public function show($id){
        $quotation=Quotation::with(['services'])->findOrFail($id);
        
        $this->data['page_title']='Quotation Details';
        $this->data['quotation']=$quotation;
        $this->data['statuses']=QuotationStatus::where('is_active',true)->get();
        return view($this->view_path.'.show',$this->data);
    }
    public function delete($id){
        $quotation=Quotation::findOrFail($id);
        $quotation->update([
            'deleted_by'=>auth()->guard('admin')->id()
        ]);
        $quotation->delete();
        return response()->json(['message'=>'Quotation successfully deleted.']);
    }

    /*****************************************************/
    # Function name : submit_quotation
    # Created Date  : 03-12-2020
    # Purpose       : Store quotation
    # Params        : SubmitQuotationRequest $request
    /*****************************************************/
    public function submit_quotation(SubmitQuotationRequest $request){

        $state=State::findOrFail($request->state_id);
        $status=QuotationStatus::where('is_default',true)->first();
        if(!$status){
            return redirect()->back()->with('quotation_error','No default status found for quotation');
        }
        $quotation=Quotation::create([
            'quotation_status_id'=>$status->id,
            'first_name'=>trim($request->first_name, ' '),
            'last_name'=>trim($request->last_name, ' '),
            'email'=>strtolower(trim($request->email, ' ')),
            'contact_number'=>trim($request->contact_number, ' '),
            'state_id'=>$request->state_id,
            'city_id'=>$request->city_id,
            'country_id'=>$state->country_id,
            'landmark'=>$request->landmark,
            'details'=>$request->details,
            'contract_duration'=>$request->contract_duration.' '.$request->contract_duration_type,
            
        ]);

        $property_types=[$request->property_type_id];

        $quotation->property_types()->sync($property_types);
        
        if($request->service_id && count($request->service_id)){
            $quotation_service_data_array=[];

            foreach ($request->service_id as $key => $service_id) {

                $service=Service::find($service_id);
                if($service){
                    $quotation_service_data_array[]=[
                        'quotation_id'=>$quotation->id,
                        'service_id'=>$service->id,
                        'work_details'=>$request->work_details[$key],
                        'currency'=>$service->currency,
                        'amount'=>$service->price,
                        'created_at'=>\Carbon\Carbon::now(),
                        'updated_at'=>\Carbon\Carbon::now()
                    ];
                }

            }

            if(count($quotation_service_data_array)){
                QuotationService::insert($quotation_service_data_array);
            }
        }

        return redirect()->back()->with('quotation_success','Quotation successfully submitted');


    }

    /************************************************************************/
    # Function to update quotation status                                    #
    # Function name    : update_status                                       #
    # Created Date     : 07-12-2020                                          #
    # Modified date    : 07-12-2020                                          #
    # Purpose          : To update quotation status                          #
    # Param            : quotation_id, Request $request                      #

    public function update_status($quotation_id,Request $request){
        $quotation=Quotation::findOrFail($quotation_id);
        $quotation->update([
            'quotation_status_id'=>$request->status,
            'updated_by'=>auth()->guard('admin')->id()
        ]);
        return redirect()->back()->with('success','Quotation status successfully updated.');
    }
}
