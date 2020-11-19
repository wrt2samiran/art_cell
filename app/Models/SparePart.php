<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    protected $guarded=[];
    protected $appends = ['image_url'];
    public function unitmaster() {
		return $this->belongsTo('\App\Models\UnitMaster', 'unit_master_id', 'id');
	}

	public function getImageUrlAttribute()
    {
        if($this->image){
			return asset('/uploads/sparepart/'.$this->image);
        }else{
        	return asset('/uploads/sparepart/no_image.png');
        }
    }
}
