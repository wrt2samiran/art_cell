<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function country() {
		return $this->belongsTo('\App\Models\Country', 'country_id', 'id');
	}

	public function state() {
		return $this->belongsTo('\App\Models\State', 'state_id', 'id');
	}
}
