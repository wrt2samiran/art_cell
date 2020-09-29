<?php
/*****************************************************/
# ModuleFunctionality
# Page/Class name   : ModuleFunctionality
# Author            :
# Created Date      : 15-07-2020
# Functionality     : Table declaration
# Purpose           : Table declaration
/*****************************************************/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleFunctionality extends Model
{
    /*****************************************************/
    # ModuleFunctionality
    # Function name : moduleData
    # Author        :
    # Created Date  : 15-07-2020
    # Purpose       : Relation between Module table
    # Params        : 
    /*****************************************************/
    public function moduleData()
    {
        return $this->belongsTo('App\Models\Module', 'module_id');
    }
}