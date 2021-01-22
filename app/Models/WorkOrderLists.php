<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderLists extends Model
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

    public function service_provider(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function contract_service_dates(){
        return $this->hasMany(ContractServiceDate::class, 'contract_service_id', 'contract_service_id');
    }
    public function tasks(){
        return $this->hasMany(TaskLists::class,'work_order_id','id');
    }
    
    public function contract_service_recurrence(){
        return $this->belongsTo(ContractServiceRecurrence::class,'contract_service_id','contract_service_id');
    }

    public function permisions_slug_array(){
        $permissions_slug_array=[];
        if($this->role){
            if(count($this->role->permissions)){
                foreach ($this->role->permissions as $permission) {
                    if($permission->functionality){
                        array_push($permissions_slug_array, $permission->functionality->slug);
                    }
                }
            }
        }
        return array_unique($permissions_slug_array);
    }
    
    public function hasAllPermission(array $permissions){
        foreach ($permissions as $permission) {
            if(!in_array($permission, $this->permisions_slug_array())){
                return false;
            }
        }
        return true;
    }

    
    public function work_order_status(){
        return $this->belongsTo(WorkOrderStatus::class,'status','is_default_status');
    }
}
