<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public $data= array();

    public function __construct(Request $request)
    {
        parent::__construct($request);
        // $this->middleware('check.permission');
    }


    public function list()
    {
        $this->data['page_title']="List";
        $this->data['panel_title']="List";
        $allAttendance= Attendance::whereNotIn('status',['D'] )->orderBy('id','desc')->get();
        $this->data['viewAttendance']=$allAttendance;
        $settingObj=$this->getCurrentUserSettingsData();
        $this->data['settingObj']=$settingObj;
        // dd($this->data);
        return view('admin.attendance.list',$this->data);
    }
}
