<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationService extends Model
{
	protected $guarded=[];
    public function service(){
    	return $this->belongsTo(Service::class);
    }
}
