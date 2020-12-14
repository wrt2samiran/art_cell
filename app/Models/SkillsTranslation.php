<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillsTranslation extends Model
{
    protected $fillable = ['skill_title'];
    public $timestamps = false;
    
    public function skills(){
        return $this->belongsTo('\App\Models\Skills','skill_id');
    }
}
