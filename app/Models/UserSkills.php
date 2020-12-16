<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkills extends Model
{
    protected $guarded=[];

    public function skill(){
        return $this->belongsTo(Skills::class, 'skill_id', 'id');
    }
}
