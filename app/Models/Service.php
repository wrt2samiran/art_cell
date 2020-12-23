<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
class Service extends Model implements TranslatableContract
{
    use SoftDeletes;
    use Translatable;
	public $translatedAttributes = ['service_name', 'description'];
    protected $guarded=[];

    public function work_orders(){
    	return $this->hasMany(WorkOrderLists::class,'service_id','id');
    }
}
