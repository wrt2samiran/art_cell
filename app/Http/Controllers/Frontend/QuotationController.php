<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{Quotation,Status,Service,PropertyType,QuotationService,State,City};
use App\Http\Requests\Admin\SubmitQuotationRequest;
class QuotationController extends Controller
{
    private $view_path='frontend.quotations';
    private $data=[];

	public function create_quotation(){
		$this->data['page_title']='Submit Quotation';

	    $this->data['property_types']=PropertyType::where('is_active',true)->orderBy('id','desc')->get();
        $this->data['services']=Service::where('is_active',true)->orderBy('id','desc')->get();

        $this->data['cities']=City::where('is_active',true)->orderBy('name','asc')->get();
        $this->data['states']=State::whereHas('cities',function($q){
            $q->where('is_active',true);
        })->with('cities')->where('is_active',true)->orderBy('name','asc')->get();
		return view($this->view_path.'.create',$this->data);
	}

    /*****************************************************/
    # Function name : submit_quotation
    # Created Date  : 15-12-2020
    # Purpose       : Store quotation
    # Params        : SubmitQuotationRequest $request
    /*****************************************************/
    public function store_quotation(SubmitQuotationRequest $request){

        $state=State::findOrFail($request->state_id);
        $status=Status::where('status_for','quotation')->where('is_default_status',true)->first();
        if(!$status){
            return redirect()->back()->with('quotation_error','No default status found for quotation');
        }
        $quotation=Quotation::create([
            'status_id'=>$status->id,
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
}
