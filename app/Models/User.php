<?php
/*****************************************************/
# User
# Page/Class name   : User
# Author            :
# Created Date      : 15-07-2020
# Functionality     : Table declaration
# Purpose           : Table declaration
/*****************************************************/

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use File;
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $guarded=[];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }

    public function getNameAttribute($value){
        return ucfirst($value);
    }
    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
    protected $appends = ['image_url'];

    public function getImageUrlAttribute($value){
        return asset('assets/images/'.$this->profile_pic);
    }

    public function module_access_slug_array(){
        $modules_slug_array=[];
        if($this->role){
            if(count($this->role->permissions)){
                foreach ($this->role->permissions as $permission) {
                    if($permission->module){
                        array_push($modules_slug_array, $permission->module->slug);
                    }
                }
            }
        }
        return array_unique($modules_slug_array);
    }
    public function permisions_slug_array(){
        $permissions_slug_array=[];
        if($this->role){
            if(count($this->role->permissions)){
                foreach ($this->role->permissions as $permission) {
                    if($permission->functionality){
                        array_push($permissions_slug_array, $permission->functionality->slug);
                    }
                }
            }
        }
        return array_unique($permissions_slug_array);
    }


    public function hasAnyPermission(array $permissions){
        foreach ($permissions as $permission) {
            if(in_array($permission, $this->permisions_slug_array())){
                return true;
            }
        }
        return false;

    }
    public function hasAllPermission(array $permissions){
        foreach ($permissions as $permission) {
            if(!in_array($permission, $this->permisions_slug_array())){
                return false;
            }
        }
        return true;
    }

    public function hasModulePermission($module_name){
        return in_array($module_name, $this->module_access_slug_array());
    }

    public function profile_image_url(){

        if($this->profile_pic){
            return asset('/uploads/profile_images/'.$this->profile_pic);
        }else{
             return asset('/uploads/profile_images/dummy_profile_image.png');
        }
    }

    //function to return true/false according to user has permission to select user type during group creation
    public function can_select_user_type_during_group_creation(){
        if($this->role->user_type && $this->role->user_type->slug=='super-admin'){
            return true;
        }
        return false;
    }

    
    public function user_skills(){
        return $this->hasMany(UserSkills::class, 'user_id', 'id');
    }

    public function country(){
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state(){
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

}
