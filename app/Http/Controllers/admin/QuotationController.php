<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
use App\Models\{Quotation,Service,PropertyType};
class QuotationController extends Controller
{
    private $view_path='admin.quotations';
    private $data=[];

    public function list(Request $request){
        $this->data['page_title']='Quotation List';

        if($request->ajax()){

            $quotations=Quotation::with(['services','property_types','city'])
            ->when($request->service,function($query) use($request){
            	$query->whereHas('services',function($sub_query)use ($request){
            		$sub_query->where('services.id',$request->service);
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
                return $quotation->created_at ? with(new Carbon($quotation->created_at))->format('m/d/Y') : '';
            })
            ->editColumn('details', function ($quotation) {
                return Str::limit($quotation->details,50);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->editColumn('first_name', function ($quotation) {
                return $quotation->first_name.' '.$quotation->last_name ;
            })
            ->filterColumn('first_name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(first_name,last_name) like ?", ["%$keyword%"]);
            })
            ->addColumn('services', function (Quotation $quotation) {
                return $quotation->services->map(function($service) {
                    return $service->service_name;
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
        $quotation=Quotation::findOrFail($id);
        $this->data['page_title']='Quotation Details';
        $this->data['quotation']=$quotation;
        return view($this->view_path.'.show',$this->data);
    }
    public function delete($id){
        $quotation=Quotation::findOrFail($id);

        $quotation->delete();
        return response()->json(['message'=>'Quotation successfully deleted.']);
    }
}