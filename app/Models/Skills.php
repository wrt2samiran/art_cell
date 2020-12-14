<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Skills extends Model implements TranslatableContract
{
    use SoftDeletes;
    use Translatable;
	public $translatedAttributes = ['skill_title'];
    protected $guarded=[];

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
}
