<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
    protected $fillable = ['service_name', 'description'];
    public $timestamps = false;
}
