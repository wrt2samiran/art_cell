<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderLists extends Model
{
    protected $guarded=[];
    
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

    public function service_provider(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function contract_service_dates(){
        return $this->hasMany(ContractServiceDate::class, 'contract_service_id', 'contract_service_id');
    }

    
    public function contract_service_recurrence(){
        return $this->belongsTo(ContractServiceRecurrence::class,'contract_service_id','contract_service_id');
    }
}
