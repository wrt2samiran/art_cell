<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	use SoftDeletes;
    public function country() {
		return $this->belongsTo('\App\Models\Country', 'country_id', 'id');
	}

	public function state() {
		return $this->belongsTo('\App\Models\State', 'state_id', 'id');
	}

	public function local() {
		return $this->hasMany('App\Models\CityTranslation', 'city_id');
	}
}
