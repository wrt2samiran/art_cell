<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class MobileBrand extends Model implements TranslatableContract
{
	use SoftDeletes;
    use Translatable;
	public $translatedAttributes = ['name'];
    protected $fillable = ['author'];
   
}
