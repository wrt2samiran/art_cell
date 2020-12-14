<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePartOrder extends Model
{
    protected $guarded=[];

    public function ordered_spare_parts(){
    	return $this->hasMany(OrderedSparePartDetail::class,'spare_part_order_id');
    }
    public function user(){
    	return $this->belongsTo(User::class);
    }
    public function status(){
        return $this->belongsTo(Status::class,'status_id');
    }
    public function delivery_address(){
    	return $this->hasOne(SparePartDeliveryAddress::class,'order_id');
    }
}
