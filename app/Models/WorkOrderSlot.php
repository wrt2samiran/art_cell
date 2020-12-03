<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderSlot extends Model
{
    protected $guarded=[];
    
 	
    public function contract_service_dates(){
        return $this->belongsTo(ContractServiceDate::class, 'contract_service_date_id', 'id');
    }

    
    public function contract_service_recurrence(){
        return $this->belongsTo(ContractServiceRecurrence::class,'contract_service_id','contract_service_id');
    }
}
