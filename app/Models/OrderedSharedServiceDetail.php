<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderedSharedServiceDetail extends Model
{
    protected $guarded=[];

    public function shared_service(){
    	return $this->belongsTo(SharedService::class);
    }
}
