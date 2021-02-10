<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileBrandTranslation extends Model
{
	protected $table = 'mobile_brand_translation';
    public $timestamps = false;
	protected $fillable = ['name'];
   
}
