<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\EmailTemplate;
use App\Models\ModuleFunctionality;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View, Config;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class EmailTemplateController extends Controller
{

    private $view_path='admin.email_template';

    /*****************************************************/
    # EmailTemplateController
    # Function name : List
    # Author        :
    # Created Date  : 21-10-2020
    # Purpose       : Show Shared Service List
    # Params        : Request $request
    /*****************************************************/
    

    public function list(Request $request){
        $this->data['page_title']='Email Template List';
        if($request->ajax()){

            $sqlEmailTemplate=EmailTemplate::orderBy('id','ASC')->orderBy('id','DESC');
            return Datatables::of($sqlEmailTemplate)
            ->editColumn('created_at', function ($sqlEmailTemplate) {
                return $sqlEmailTemplate->created_at ? with(new Carbon($sqlEmailTemplate->created_at))->format('m/d/Y') : '';
            })
            
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })          
            
            ->addColumn('action',function($sqlEmailTemplate){
                $delete_url=route('admin.email.delete',$sqlEmailTemplate->id);
                $details_url=route('admin.email.show',$sqlEmailTemplate->id);
                $edit_url=route('admin.email.edit',$sqlEmailTemplate->id);

                return '<a title="View Email Details" href="'.$details_url.'"><i class="fas fa-eye text-primary"></i></a>&nbsp;&nbsp;<a title="Edit Email" href="'.$edit_url.'"><i class="fas fa-pen-square text-success"></i></a>&nbsp;&nbsp;<a title="Delete message"  href="javascript:delete_message('.$sqlEmailTemplate->id.')"><i class="far fa-minus-square text-danger"></i></a>';
                
            })
            ->rawColumns(['action'])
            ->make(true);

        }


        return view($this->view_path.'.list',$this->data);
    }

   /*****************************************************/
    # EmailTemplateController
    # Function name : emailAdd
    # Author        :
    # Created Date  : 21-10-2020
    # Purpose       : Add new Shared Service
    # Params        : Request $request
    /*****************************************************/
    public function emailAdd(Request $request) {

        $data['page_title']     = 'Add Email Template';
        $logedin_user=auth()->guard('admin')->user();
    
        try
        {
        	if ($request->isMethod('POST'))
        	{
				$validationCondition = array(
                    'template_name'          => 'required|min:2|max:255|unique:'.(new EmailTemplate)->getTable().',template_name',
                    'content'  => 'required|min:2',
				);
				$validationMessages = array(
					'template_name.required'                => 'Please enter name',
					'template_name.min'                     => 'Name should be should be at least 2 characters',
                    'template_name.max'                     => 'Name should not be more than 255 characters',
                    'content.required'                      => 'Please enter message',
                    'content.min'                           => 'Message should be should be at least 2 characters',

				);

				$Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
				if ($Validator->fails()) {
					return redirect()->route('admin.email.add')->withErrors($Validator)->withInput();
				} else {
                    
                    $new = new EmailTemplate;
                    $new->template_name     = trim($request->template_name, ' ');
                    $new->content           = $request->content;
                    $new->variable_name     = $request->variable_name;
                    $new->slug              = Str::slug($request->template_name);
                    $save = $new->save();
                
					if ($save) {						
						$request->session()->flash('alert-success', 'Email Template has been added successfully');
						return redirect()->route('admin.email.list');
					} else {
						$request->session()->flash('alert-danger', 'An error occurred while adding the email');
						return redirect()->back();
					}
				}
            }
			return view('admin.email_template.add', $data);
		} catch (Exception $e) {
			return redirect()->route('admin.email.list')->with('error', $e->getMessage());
		}
    }

    /*****************************************************/
    # EmailTemplateController
    # Function name : edit
    # Author        :
    # Created Date  : 21-10-2020
    # Purpose       : Edit Message
    # Params        : Request $request
    /*****************************************************/
    public function edit(Request $request, $id = null) {
        $data['page_title']     = 'Edit Email Template';
        $data['panel_title']    = 'Edit Email Template';
        $logedin_user=auth()->guard('admin')->user();

        try
        { 
           
            $details = EmailTemplate::find($id);
            $data['id'] = $id;

            if ($request->isMethod('POST')) {
                if ($id == null) {
                    return redirect()->route('admin.email.list');
                }
               
                $validationCondition = array(
                    'template_name'          => 'required|min:2|max:255|unique:'.(new EmailTemplate)->getTable().',template_name,'.$id.'',
                    'content'  => 'required|min:2',
                );
                $validationMessages = array(
                    'template_name.required'                => 'Please enter name',
                    'template_name.min'                     => 'Name should be should be at least 2 characters',
                    'template_name.max'                     => 'Name should not be more than 255 characters',
                    'content.required'                      => 'Please enter message',
                    'content.min'                           => 'Message should be should be at least 2 characters',

                );

                
                $Validator = \Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    
                    return redirect()->back()->withErrors($Validator)->withInput();
                } else {
                    
                    $details->template_name     = trim($request->template_name, ' ');
                    $details->content           = $request->content;
                    $details->variable_name     = $request->variable_name;
                    $details->slug              = Str::slug($request->template_name);
                    $save = $details->save();                        
                    if ($save) {
                        $request->session()->flash('alert-success', 'Email Template has been updated successfully');
                        return redirect()->route('admin.email.list');
                    } else {
                        $request->session()->flash('alert-danger', 'An error occurred while updating the email');
                        return redirect()->back();
                    }
                }
            }
            
            
            return view('admin.email_template.edit', $data)->with(['details' => $details]);

        } catch (Exception $e) {
            return redirect()->route('admin.email.list')->with('error', $e->getMessage());
        }
    }

    
    /*****************************************************/
    # EmailTemplateController
    # Function name : delete
    # Author        :
    # Created Date  : 21-10-2020
    # Purpose       : Delete Message
    # Params        : Request $request
    /*****************************************************/
    public function delete(Request $request, $id = null)
    {
        
            if ($id == null) {
                return redirect()->route('admin.email.list');
            }

            $details = EmailTemplate::where('id', $id)->first();
           
                    $delete = $details->delete();
        //             if ($delete) {
        //                 $request->session()->flash('alert-danger', 'Email Template has been deleted successfully');
        //             } else {
        //                 $request->session()->flash('alert-danger', 'An error occurred while deleting the email');
        //             }
        //     } else {
        //         $request->session()->flash('alert-danger', 'Invalid email');
                
        //     }
        //     return redirect()->back();
        // } catch (Exception $e) {
        //     return redirect()->route('admin.email.list')->with('error', $e->getMessage());
        return response()->json(['message'=>'Email Template successfully deleted.']);
            
       
    }
    
    /*****************************************************/
    # EmailTemplateController
    # Function name : show
    # Author        :
    # Created Date  : 21-10-2020
    # Purpose       : Showing Message details
    # Params        : Request $request
    /*****************************************************/

    public function show($id){
        $emailSql=EmailTemplate::findOrFail($id);
        $this->data['page_title']='Email Template Details';
        $this->data['email']=$emailSql;
        return view($this->view_path.'.show',$this->data);
    }

    public function sendMail(Request $request){
        $mail = EmailTemplate::where('id','1')->first();
        $message = $mail->content;
        $message = str_replace("##USERNAME##", nl2br('SMMS'), $message);
	    $message = str_replace("##Address##", nl2br('Kolkata'), $message);
        $message = str_replace("##Employ-code##", ('000-421'), $message); 
        \Mail::send('emails.admin.blankmail',
        [
            'templateForntend'=>$message,
        ], function ($m) use ($message) {
            $m->to('banksbi@yopmail.com')->subject('User Credential');
        });
    }
}
