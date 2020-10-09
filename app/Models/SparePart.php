<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    
    public function unitmaster() {
		return $this->belongsTo('\App\Models\UnitMaster', 'unit_master_id', 'id');
	}
}
