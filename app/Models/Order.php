<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	use SoftDeletes;
    protected $guarded=[];
    
    public function country() {
		return $this->belongsTo('\App\Models\Country', 'country_id', 'id');
	}

	public function state() {
		return $this->belongsTo('\App\Models\State', 'state_id', 'id');
	}

	public function city() {
		return $this->belongsTo('App\Models\City', 'city_id', 'id');
	}

	public function mobile_brand() {
		return $this->belongsTo('App\Models\MobileBrand', 'mobile_brand_id', 'id');
	}

	public function mobile_brand_model() {
		return $this->belongsTo('App\Models\MobileBrandModel', 'mobile_brand_model_id', 'id');
	}

	public function createdby() {
		return $this->belongsTo('App\Models\User', 'created_by', 'id');
	}
	
}
