<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLists extends Model
{
    protected $guarded=[];
    
 	public function userDetails() {
        return $this->belongsTo('\App\Models\User',  'user_id','id');
    }
    public function country() {
        return $this->belongsTo('\App\Models\Country',  'country_id','id');
    }
    public function state() {
        return $this->belongsTo('\App\Models\State',  'state_id','id');
    }
    public function city() {
        return $this->belongsTo('\App\Models\City',  'city_id','id');
    }

    public function property() {
		return $this->belongsTo('\App\Models\Property', 'property_id', 'id');
	}
	public function service() {
		return $this->belongsTo('\App\Models\Service', 'service_id', 'id');
	}
}
