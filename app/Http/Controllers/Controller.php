<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\PrCopywriter;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $currentLang;
    private $setLang;

    public function __construct(Request $request)
    {
       
    }

    public function formatError($errors) : array{
        $formatted_errors = [];
        foreach ($errors as $k => $e){
            foreach ($e as $err){
                array_push($formatted_errors, $err);
            }
        }
        return $formatted_errors;
    }

    public function getCurrentUserSettingsData(){
        $authObj=\Auth::guard('admin')->user();
        // dd($authObj);
        $settingObj= json_decode($authObj->setting_json,true);
        // dd($settingObj);
        return (object)$settingObj;
    }
    public function callFrontendRoute(){
        return view('welcome');
    }
    function generatePrCopyTicket() {
        $ticketPrefix='PR-00000';
        $lastIDofPRCopywriter =PrCopywriter::select('id')->orderBy('id','desc')->first()->id;
        $ticketNo=$ticketPrefix.$lastIDofPRCopywriter++;
        return $ticketNo;
    }

    public function compareWithCurrentTime($dateTime){
        $timeStamp=strtotime($dateTime);
        $currentTimeStamp= \Carbon::now()->timestamp;
        $twoHoursAddedTimeStamp=\Carbon::now()->addHours(-2)->timestamp;
        if(  ($timeStamp >=  $twoHoursAddedTimeStamp) && ($timeStamp <= $currentTimeStamp) )
            return true; 
        return false;           
    }
}

