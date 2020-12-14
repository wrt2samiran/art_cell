<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Quotation extends Model
{
    use SoftDeletes;
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
    public function status(){
        return $this->belongsTo(Status::class,'status_id');
    }
    public function services(){
        return $this->hasMany(QuotationService::class,'quotation_id');
    }
    public function property_types(){
        return $this->belongsToMany(PropertyType::class);
    }

}
