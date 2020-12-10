<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLists extends Model
{
    protected $guarded=[];
    public function get_status_name(){
        if($this->status=='0'){
            return 'Pending';
        }elseif($this->status=='1'){
            return 'Overdue';
        }elseif ($this->status=='2') {
            return 'Completed';
        }else{
            return '';
        }
    }
 	public function userDetails() {
        return $this->belongsTo('\App\Models\User',  'user_id','id');
    }
    public function country() {
        return $this->belongsTo('\App\Models\Country',  'country_id','id');
    }
    public function state() {
        return $this->belongsTo('\App\Models\State',  'state_id','id');
    }
    public function city() {
        return $this->belongsTo('\App\Models\City',  'city_id','id');
    }

    public function property() {
		return $this->belongsTo('\App\Models\Property', 'property_id', 'id');
	}
	public function service() {
		return $this->belongsTo('\App\Models\Service', 'service_id', 'id');
	}
    public function contract_services() {
        return $this->belongsTo('\App\Models\ContractService', 'contract_service_id', 'id');
    }

    public function contract() {
        return $this->belongsTo('\App\Models\Contract', 'contract_id', 'id');
    }

    public function work_order() {
        return $this->belongsTo('\App\Models\WorkOrderLists', 'work_order_id', 'id');
    }

    public function task_details() {
        return $this->hasMany(TaskDetails::class, 'task_id', 'id');
    }
}
