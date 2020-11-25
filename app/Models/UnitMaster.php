<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitMaster extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $table = 'unit_masters';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','created_by','updated_by','status','deleted_by'
    ];
}
