<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskDetails extends Model
{
 	use SoftDeletes;
 	protected $guarded=[];
    public function get_status_name(){
        if($this->status=='0'){
            return 'Pending';
        }elseif($this->status=='1'){
            return 'Overdue';
        }elseif ($this->status=='2') {
            return 'Completed';
        }elseif ($this->status=='3') {
            return 'Reschedule';
        }elseif ($this->status=='5') {
            return 'Completed with Warning';
        }else{
            return '';
        }
    }
 	public function userDetails() {
        return $this->belongsTo('\App\Models\User',  'user_id','id');
    }
    public function task() {
        return $this->belongsTo('\App\Models\TaskLists',  'task_id','id');
    }
    public function service() {
		return $this->belongsTo('\App\Models\Service', 'service_id', 'id');
	}

	public function work_order_slot() {
		return $this->belongsTo('App\Models\WorkOrderSlot', 'work_order_slot_id', 'id');
	}
    public function contract_service()
    {
        return $this->belongsTo(ContractService::class);
    }

    public function task_details_feedback_files()
    {
        return $this->hasMany(TaskDetailsFeedbackFiles::class);
    }

    public function get_slot_name(){
        if($this->work_order_slot->daily_slot=='1'){
            return 'First Slot';
        }elseif($this->work_order_slot->daily_slot=='2'){
            return 'Second Slot';
        }elseif ($this->work_order_slot->daily_slot=='3') {
            return 'Third Slot';
        }elseif ($this->work_order_slot->daily_slot=='4') {
            return 'Fourth Slot';
        }elseif ($this->work_order_slot->daily_slot=='5') {
            return 'Fifth Slot';
        }elseif($this->work_order_slot->daily_slot=='6'){
            return 'Sixth Slot';
        }elseif ($this->work_order_slot->daily_slot=='7') {
            return 'Seventh Slot';
        }elseif ($this->work_order_slot->daily_slot=='8') {
            return 'Eighth Slot';
        }elseif ($this->work_order_slot->daily_slot=='9') {
            return 'Nineth Slot';
        }elseif ($this->work_order_slot->daily_slot=='10') {
            return 'Tenth Slot';
        }else{
            return 'No Slot';
        }

        
    }
    
}
