<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedServiceCart extends Model
{
    protected $guarded=[];
    public function shared_service_details(){
    	return $this->belongsTo(SharedService::class,'shared_service_id');
    }
}
