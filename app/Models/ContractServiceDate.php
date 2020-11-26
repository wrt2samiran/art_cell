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
    public function recurrence()
    {
		return $this->belongsTo(ContractServiceRecurrence::class,'recurrence_id','id');
    }


}
