<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Message extends Model
{
	use SoftDeletes;
    protected $guarded=[];

    public function from_user(){
    	return $this->belongsTo(User::class,'message_from');
    }
    public function to_user(){
    	return $this->belongsTo(User::class,'message_to');
    }

    public function message_attachments(){
        return $this->hasMany(MessageAttachment::class);
    }
}
