<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendars extends Model
{
 	use SoftDeletes;

 	public function userDetails() {
        return $this->belongsTo('\App\Models\User',  'user_id','id');
    }
}
