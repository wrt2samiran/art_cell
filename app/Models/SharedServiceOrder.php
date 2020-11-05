<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedServiceOrder extends Model
{
    protected $guarded=[];

    public function ordered_shared_services(){
    	return $this->hasMany(OrderedSharedServiceDetail::class,'order_id');
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }
    public function delivery_address(){
    	return $this->hasOne(SharedServiceDeliveryAddress::class,'order_id');
    }
}
