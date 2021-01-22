<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderStatusTranslation extends Model
{
    protected $fillable = ['status_name'];
    public $timestamps = false;
}
