<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractService extends Model
{
	protected $guarded=[];
	public function frequency_type(){
		return $this->belongsTo(FrequencyType::class);
	}
	public function service(){
		return $this->belongsTo(Service::class);
	}
	public function recurrence_details()
	{
		return $this->hasOne(ContractServiceRecurrence::class);
	}

	public function service_dates()
	{
		return $this->hasMany(ContractServiceDate::class);
	}

}
