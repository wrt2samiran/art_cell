<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use App\Models\Complaint;
use App\Http\Requests\Admin\Complaint\{CreateComplaintRequest,UpdateComplaintRequest};
class ComplaintController extends Controller
{
    //defining the view path
    private $view_path='admin.complaints';
    //defining data array
    private $data=[];

    /************************************************************************/
    # Function for complaints list and datatable ajax response               #
    # Function name    : list                                                #
    # Created Date     : 20-11-2020                                          #
    # Modified date    : 20-11-2020                                          #
    # Purpose          : For complaints list and returning Datatables        #
    # ajax response                                                          #

    public function list(Request $request){
        $this->data['page_title']='Complaints List';
        if($request->ajax()){

            $complaints=Complaint::orderBy('id','DESC');
            return Datatables::of($country)
            ->editColumn('created_at', function ($country) {
                return $country->created_at ? with(new Carbon($country->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->addColumn('action',function($country){
            	return 'action';
            })
            ->rawColumns(['action','is_active'])
            ->make(true);
        }


        return view($this->view_path.'.list',$this->data);
    }

    /************************************************************************/
    # Function to load complaint create view page                            #
    # Function name    : create                                              #
    # Created Date     : 20-11-2020                                          #
    # Modified date    : 14-11-2020                                          #
    # Purpose          : To load complaint create view page                  #
    public function create(){
        $this->data['page_title']='Create Complaint';
        return view($this->view_path.'.create',$this->data);
    }

    /********************************************************************************/
    # Function to store complaint data                                               #
    # Function name    : store                                                       #
    # Created Date     : 20-11-2020                                                  #
    # Modified date    : 20-11-2020                                                  #
    # Purpose          : store complaint data                                         #
    # Param            : CreateContractRequest $request                              #

    public function store(CreateComplaintRequest $request){


    }


}
