<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
    protected $fillable = ['service_name', 'description'];
    public $timestamps = false;
    
    public function service(){
        return $this->belongsTo('\App\Models\Service','service_id');
    }
}
