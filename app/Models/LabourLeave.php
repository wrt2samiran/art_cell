<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabourLeave extends Model
{
    protected $guarded=[];
    
 	public function userDetails() {
        return $this->belongsTo('\App\Models\User',  'labour_id','id');
    }
    
  
    public function leave_dates(){
        return $this->hasMany(LeaveDates::class, 'leave_id', 'id');
    }
}
