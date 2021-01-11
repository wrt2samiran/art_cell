<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class UnitMaster extends Model implements TranslatableContract
{
    use SoftDeletes;
    use Translatable;
    protected $table = 'unit_masters';
    public $translatedAttributes = ['unit_name'];
    protected $guarded=[];
}
