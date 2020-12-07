<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SharedService extends Model
{
	use SoftDeletes;
    protected $guarded=[];
    protected $appends = ['image_thumb_url'];
	public function getImageThumbUrlAttribute()
    {
        if(count($this->images)){
            return asset('/uploads/shared_service_images/thumb/'.$this->images[0]->image_name);
        }else{
            return asset('/uploads/shared_service_images/no_image.png');
        }
    }
    public function images(){
    	return $this->hasMany(SharedServiceImage::class);
    }
}
