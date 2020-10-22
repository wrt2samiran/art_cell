<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAllocationManagement extends Model
{

	protected $guarded=[];
 	
 	public function property() {
		return $this->belongsTo('\App\Models\Property', 'property_id', 'id');
	}

	public function service() {
		return $this->belongsTo('\App\Models\Service', 'service_name', 'id');
	}

	public function tasks_list() {
		return $this->belongsTo('\App\Models\TaskLists', 'service_name', 'service_allocation_id');
	}

	public function service_provider(){
    	return $this->belongsTo(User::class,'service_provider_id','id');
    }

	public function contract() {
		return $this->belongsTo('\App\Models\Contract', 'contract_id', 'id');
	}

}
