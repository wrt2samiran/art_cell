<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{Quotation,Status,Service,PropertyType,QuotationService,State};
use App\Http\Requests\Admin\SubmitQuotationRequest;
use App\Mail\Quotation\StatusUpdateMailToCustomer;
use Mail;
class QuotationController extends Controller
{
    private $view_path='admin.quotations';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Quotation List';

        if($request->ajax()){

            $quotations=Quotation::with(['services','property_types','city','state','status'])
            ->whereHas('status')
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
            ->editColumn('status.status_name', function ($quotation) {
                return '<span style="color:'.$quotation->status->color_code.'">'.$quotation->status->status_name.'<span>';
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
            ->rawColumns(['action','services','property_types','status.status_name'])
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
        $this->data['statuses']=Status::where('status_for','quotation')->where('is_active',true)->get();
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



    /************************************************************************/
    # Function to update quotation status                                    #
    # Function name    : update_status                                       #
    # Created Date     : 07-12-2020                                          #
    # Modified date    : 07-12-2020                                          #
    # Purpose          : To update quotation status                          #
    # Param            : quotation_id, Request $request                      #

    public function update_status($quotation_id,Request $request){
        $quotation=Quotation::findOrFail($quotation_id);
        $previous_status_id=$quotation->status_id;
        $quotation->update([
            'status_id'=>$request->status,
            'updated_by'=>auth()->guard('admin')->id()
        ]);



        if($quotation->email && ($previous_status_id!=$request->status)){

            $data=[
                'customer_name'=>$quotation->first_name.' '.$quotation->last_name,
                'status'=>$quotation->status->status_name,
                'quotation_id'=>$quotation->id,
                'from_name'=>env('MAIL_FROM_NAME','SMMS'),
                'from_email'=>env('MAIL_FROM_ADDRESS'),
                'subject'=>'Quotation Status Updated'
            ];

            Mail::to(strtolower(trim($quotation->email, ' ')))->send(new StatusUpdateMailToCustomer($data)); 
        }




        return redirect()->back()->with('success','Quotation status successfully updated.');
    }
}
