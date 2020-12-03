<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
 	public function country() {
		return $this->belongsTo('\App\Models\Country', 'country_id', 'id');
	}
	public function cities(){
		return $this->hasMany(City::class);
	}
	public function local() {
		return $this->hasMany('App\Models\StateTranslation', 'state_id');
	}
}
