<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class CommonAjaxController extends Controller
{
    public function check_user_email_unique(Request $request,$user_id=null){
        if($user_id){
             $user= User::where('id','!=',$user_id)
             ->where('email',$request->email)->first();
        }else{
             $user= User::where('email',$request->email)->first();
        }
     
        if($user){
            echo "false";
        }else{
            echo "true";
        }
    }
}
