<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Country extends Model implements TranslatableContract
{
	use SoftDeletes;
    use Translatable;
	public $translatedAttributes = ['name','country_code','dial_code'];
    protected $fillable = ['author'];
   
}
