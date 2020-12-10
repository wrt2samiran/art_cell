<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskDetails extends Model
{
 	use SoftDeletes;
 	protected $guarded=[];

 	public function userDetails() {
        return $this->belongsTo('\App\Models\User',  'user_id','id');
    }
    public function task() {
        return $this->belongsTo('\App\Models\TaskLists',  'task_id','id');
    }
    public function service() {
		return $this->belongsTo('\App\Models\Service', 'service_id', 'id');
	}

	public function work_order_slot() {
		return $this->belongsTo('App\Models\WorkOrderSlot', 'work_order_slot_id', 'id');
	}
    public function contract_service()
    {
        return $this->belongsTo(ContractService::class);
    }
}
