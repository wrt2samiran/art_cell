<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLists extends Model
{
 	public function userDetails() {
        return $this->belongsTo('\App\Models\User',  'user_id','id');
    }
}
