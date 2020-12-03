<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Property extends Model
{
	use SoftDeletes;
    protected $guarded=[];

    public function owner_details(){
    	return $this->belongsTo(User::class,'property_owner','id');
    }

    public function manager_details(){
        return $this->belongsTo(User::class,'property_manager','id');
    }

    public function city(){
    	return $this->belongsTo(City::class);
    }
    public function property_type(){
        return $this->belongsTo(PropertyType::class);
    }
    public function property_attachments(){
        return $this->hasMany(PropertyAttachment::class);
    }
    public function contracts(){
        return $this->hasMany(Contract::class);
    }

    public function parent_user()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function country(){
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state(){
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

   
}
