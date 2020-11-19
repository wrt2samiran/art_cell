<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
	protected $guarded=[];
    public function user_full_name(){
    	return $this->first_name.' '.$this->last_name;
    }

    public function city(){
    	return $this->belongsTo(City::class);
    }
    public function country(){
    	return $this->belongsTo(Country::class);
    }
    public function state(){
    	return $this->belongsTo(State::class);
    }

    public function services(){
    	return $this->belongsToMany(Service::class,'service_quotation', 'quotation_id', 'service_id');
    }
    public function property_types(){
    	return $this->belongsToMany(PropertyType::class);
    }

    public function serviceRelatedQuotetion(){
    	return $this->hasMany('App\Models\QuotationService','quotation_id');
    }
}
