<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationService extends Model
{
    public function service(){
    	return $this->belongsTo(Service::class);
    }
}
