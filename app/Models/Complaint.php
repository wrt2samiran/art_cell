<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Complaint extends Model
{
	use SoftDeletes;
    protected $guarded=[];
    public function contract(){
    	return $this->belongsTo(Contract::class);
    }
    public function work_order(){
    	return $this->belongsTo(WorkOrderLists::class,'work_order_list_id','id');
    }
    public function complaint_status(){
    	return $this->belongsTo(ComplaintStatus::class);
    }
    
}
