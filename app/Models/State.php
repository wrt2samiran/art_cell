<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class State extends Model implements TranslatableContract
{
	//protected $table = 'state';

	use SoftDeletes;
    use Translatable;
    public $translatedAttributes = ['name'];
    protected $guarded=[];

 	public function country() {
		return $this->belongsTo('\App\Models\Country', 'country_id', 'id');
	}
	public function cities(){
		return $this->hasMany(City::class);
	}
	public function local() {
		return $this->hasMany('App\Models\StateTranslation', 'state_id');
	}
}
