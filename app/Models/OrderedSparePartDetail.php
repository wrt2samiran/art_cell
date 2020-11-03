<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderedSparePartDetail extends Model
{
   protected $guarded=[];
   public function spare_part()
   {
   		return $this->belongsTo(SparePart::class,'spare_part_id');
   }
}
