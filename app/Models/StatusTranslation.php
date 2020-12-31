<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusTranslation extends Model
{
    protected $fillable = ['status_name'];
    public $timestamps = false;
}
