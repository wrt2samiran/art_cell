<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveDates extends Model
{
    protected $guarded=[];
    
 	public function labour_leaves() {
        return $this->belongsTo(LabourLeave::class,  'leave_id','id');
    }
    
}
