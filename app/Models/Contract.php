<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Contract extends Model
{

    use SoftDeletes;
    protected $guarded=[];

    public function services(){
    	return $this->hasMany(ContractService::class);
    }

    public function service_provider(){
    	return $this->belongsTo(User::class,'service_provider_id','id');
    }
    public function property(){
    	return $this->belongsTo(Property::class,'property_id','id');
    }
    public function contract_attachments(){
        return $this->hasMany(ContractAttachment::class);
    }
    public function contract_installments(){
        return $this->hasMany(ContractInstallment::class);
    }

    public function services_id_array(){
        $array=[];
        if(count($this->services)){
            foreach ($this->services as $service) {
                $array[]=$service->id;
            }
        }
        return $array;
    }

    public function contract_status(){
        return $this->belongsTo(ContractStatus::class);
    }

    public function services_price_total(){
        $price_array=[];
        if(count($this->services)){
            foreach ($this->services as $service) {
                $price_array[]=$service->price;
            }
        }
        return array_sum($price_array);
    }
    
    public function work_orders(){
        return $this->hasMany(WorkOrderLists::class,'contract_id','id');
    }
}
