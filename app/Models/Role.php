<?php
/*****************************************************/
# Role
# Page/Class name   : Role
# Author            :
# Created Date      : 15-07-2020
# Functionality     : Table declaration
# Purpose           : Table declaration
/*****************************************************/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    protected $guarded=[];
    
    /*****************************************************/
    # Role
    # Function name : creator
    # Author        :
    # Created Date  : 15-07-2020
    # Purpose       : Relation between User table
    # Params        : 
    /*****************************************************/
    public function creator(){

        return $this->belongsTo('App\Models\User','created_by');
    }

    public function parent()
    {
        return $this->belongsTo(Role::class, 'parrent_id');
    }
    public function childrens()
    {
        return $this->hasMany(Role::class,'parrent_id','id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions(){
        return $this->hasMany(RolePermission::class);
    }

    public function user_type(){
       return $this->belongsTo(UserType::class); 
    }

}
