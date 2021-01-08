<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SmsTemplate;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;
class SmsTemplateController extends Controller
{
    private $view_path='admin.sms_templates';

   
    public function list(Request $request){
        $this->data['page_title']='Sms Template List';
        if($request->ajax()){

            $sms_templates=SmsTemplate::select('sms_templates.*');
            return Datatables::of($sms_templates)
            ->editColumn('created_at', function ($sms_template) {
                return $sms_template->created_at ? with(new Carbon($sms_template->created_at))->format('d/m/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%d/%m/%Y') like ?", ["%$keyword%"]);
            })          
            
            ->addColumn('action',function($sms_template){
                $details_url=route('admin.sms_templates.show',$sms_template->id);
                $edit_url=route('admin.sms_templates.edit',$sms_template->id);

                return '<a title="View Email Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Email" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>';
                
            })
            ->rawColumns(['action'])
            ->make(true);

        }

        return view($this->view_path.'.list',$this->data);
    }

    public function create(){
        $this->data['page_title']='Create Sms Template';
        return view($this->view_path.'.create',$this->data);
    }
    public function store(Request $request){
    	$request->validate([
	        'template_name' => 'required|min:2|max:255|unique:'.(new SmsTemplate)->getTable().',template_name',
	        'variable_name'=>'required',
            'content'       => 'required|min:2',
            'slug'          => 'required',  
    	]);
    	SmsTemplate::create([
    		'template_name'=>$request->template_name,
    		'slug'=>$request->slug,
    		'variable_name'=>$request->variable_name,
    		'content'=>$request->content
    	]);

    	return redirect()->route('admin.sms_templates.list')->with('success',__('sms_template_manage_module.create_success_message'));
    }

    public function show($id){
        $sms_template=SmsTemplate::findOrFail($id);
        $this->data['page_title']='Sms Template Details';
        $this->data['sms_template']=$sms_template;
        return view($this->view_path.'.show',$this->data);
    }
    public function edit($id){
        $sms_template=SmsTemplate::findOrFail($id);
        $this->data['page_title']='Edit Sms Template';
        $this->data['sms_template']=$sms_template;
        return view($this->view_path.'.edit',$this->data);
    }

    public function update($id,Request $request){
    	$sms_template=SmsTemplate::findOrFail($id);
    	$request->validate([
			'content'=> 'required',
    	]);
    	$sms_template->update([
    		'content'=>$request->content
    	]);

    	return redirect()->route('admin.sms_templates.list')->with('success',__('sms_template_manage_module.edit_success_message'));
    }


}
