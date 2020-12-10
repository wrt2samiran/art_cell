<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintStatusUpdate extends Model
{
    protected $guarded=[];
    public function user(){
    	return$this->belongsTo(User::class,'updated_by');
    }
    
    public function user_display_title(){
    	$user_type=$this->user->role->user_type->slug;
    	$return_text='';
    	if($user_type=='super-admin'){
    		$return_text='Help Desk';
    	}elseif ($user_type=='service-provider') {
    		$return_text='Service Provider';
    	}elseif ($user_type=='property-owner') {
    		if($this->user->created_by_admin){
    			$return_text='Property Owner';
    		}else{
    			$return_text='Property Manager';
    		}
    	}elseif ($user_type=='property-owner') {
    		$return_text='Labour';
    	}else{
    		$return_text=$this->user->role->user_type->name;
    	}

    	if($this->updated_by==auth()->guard('admin')->id()){
    		$return_text.=' (you) ';
    	}

    	return $return_text;

    }
}
