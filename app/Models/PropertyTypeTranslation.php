<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyTypeTranslation extends Model
{
     protected $fillable = ['type_name', 'description'];
     public $timestamps = false;
}
