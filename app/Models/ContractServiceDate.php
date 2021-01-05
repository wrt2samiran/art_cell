<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractServiceDate extends Model
{
    protected $guarded=[];
    public function contract_service()
    {
		return $this->belongsTo(ContractService::class);
    }
    public function service()
    {
		return $this->belongsTo(Service::class);
    }
    public function contract()
    {
		return $this->belongsTo(Contract::class);
    }
    public function task_details_list(){
        return $this->hasMany(TaskDetails::class,'task_date','date')->whereHas('task.contract_services',function($q){
            $q->where('service_type','Maintenance');
        });
    }
    public function recurrence()
    {
		return $this->belongsTo(ContractServiceRecurrence::class,'recurrence_id','id');
    }


}
