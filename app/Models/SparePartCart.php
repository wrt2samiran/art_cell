<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePartCart extends Model
{
    protected $guarded=[];
    public function spare_part_details(){
    	return $this->belongsTo(SparePart::class,'spare_part_id');
    }

    
}
