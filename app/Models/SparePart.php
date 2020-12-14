<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SparePart extends Model
{
    use SoftDeletes;
    protected $guarded=[];
    protected $appends = ['image_thumb_url'];
    public function unitmaster() {
		return $this->belongsTo('\App\Models\UnitMaster', 'unit_master_id', 'id');
	}

	public function getImageThumbUrlAttribute()
    {
        if(count($this->images)){
            return asset('/uploads/spare_part_images/thumb/'.$this->images[0]->image_name);
        }else{
            return asset('/uploads/spare_part_images/no_image.png');
        }
    }
    public function images(){
        return $this->hasMany(SparePartImage::class);
    }


}
