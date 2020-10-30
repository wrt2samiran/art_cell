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
}
