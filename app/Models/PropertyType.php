<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class PropertyType extends Model implements TranslatableContract
{
    use SoftDeletes;
    use Translatable;
	public $translatedAttributes = ['type_name', 'description'];
    protected $guarded=[];
}
