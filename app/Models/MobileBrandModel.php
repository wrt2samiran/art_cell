<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class MobileBrandModel extends Model implements TranslatableContract
{
	//protected $table = 'state';

	use SoftDeletes;
    use Translatable;
    public $translatedAttributes = ['name'];
    protected $guarded=[];

 	public function brand() {
		return $this->belongsTo(MobileBrand::class, 'mobile_brand_id', 'id');
	}
	
}
