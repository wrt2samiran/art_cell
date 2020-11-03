<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    protected $guarded=[];
    public function unitmaster() {
		return $this->belongsTo('\App\Models\UnitMaster', 'unit_master_id', 'id');
	}
}
