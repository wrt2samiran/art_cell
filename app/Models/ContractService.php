<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractService extends Model
{
	protected $table = 'contract_service';
	
	public function service() {
		return $this->belongsTo('\App\Models\Service', 'service_id', 'id');
	}
 	
}
