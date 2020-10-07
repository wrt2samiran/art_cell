<?php

/*****************************************************/
# RolePermission
# Page/Class name   : RolePermission
# Author            :
# Created Date      : 15-07-2020
# Functionality     : Table declaration
# Purpose           : Table declaration
/*****************************************************/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded=[];
    /*****************************************************/
    # RolePermission
    # Function name : module
    # Author        :
    # Created Date  : 15-07-2020
    # Purpose       : Relation between Module table
    # Params        : 
    /*****************************************************/
    public function module(){

        return $this->belongsTo('App\Models\Module');
    }

    /*****************************************************/
    # RolePermission
    # Function name : functionality
    # Author        :
    # Created Date  : 15-07-2020
    # Purpose       : Relation between ModuleFunctionality table
    # Params        : 
    /*****************************************************/

    public function functionality(){
        return $this->belongsTo('App\Models\ModuleFunctionality','module_functionality_id');
    }
}
