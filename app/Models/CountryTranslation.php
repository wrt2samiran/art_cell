<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryTranslation extends Model
{
	protected $table = 'countries_translation';
    public $timestamps = false;
	protected $fillable = ['name','country_code','dial_code'];
   
}
