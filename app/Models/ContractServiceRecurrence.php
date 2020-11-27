<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractServiceRecurrence extends Model
{
  protected $guarded=[];
  public function contract_service()
  {
  	 return $this->belongsTo(ContractService::class);
  }
}
