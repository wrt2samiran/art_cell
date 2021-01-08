<table>
    <thead>
    <tr style="color: #0099cc">
        <th>Task Title</th>
        <th>Work Order Title</th>
        <th>Contract Code</th>
        <th>Contract Name</th>
        <th>Service Type</th>
        <th>Service Name</th>
        <th>Task Description</th>
        <th>Task Reschedule</th>
        <th>Assigned Finish Time</th>
        <th>Labour User</th>
        <th>Actual Task Finish Date</th>
        <th>Late Feedback</th>
        <th>Rating</th>
        <td>Task Complete Percent</td>
        <th>Property Name</th>
        <th>Property Address</th>
        <th>Property Owner Email</th>
        <th>Property Owner Name</th>
        <th>Current Status</th>
        <th>Created Date</th>
        
    </tr>
    </thead>
    <tbody>
    	@forelse($task_details_list as $task_details)
	    <tr>
            <td>{{@$task_details->task->task_title}}</td>
	        <td>{{@$task_details->task->work_order->task_title}}</td>
            <td>{{@$task_details->task->contract->code}}</td>
	        <td>{{@$task_details->task->contract->title}}</td>
	        <td>{{@$task_details->task->contract_services->service_type}}</td>
	        <td>{{@$task_details->task->service->service_name}}</td>

            <td>{{@$task_details->task_description}}</td>
            <td>@if(@$task_details->rescheduled=='N'){{'No'}}@else{{'Yes'}}@endif</td>
            <td>{{Carbon::parse(@$task_details->task_date)->format('d/m/Y H:i a')}}</td>
            <td>{{@$task_details->userDetails->name}}</td>
            <td>{{Carbon::parse(@$task_details->task_finish_date_time)->format('d/m/Y H:i a')}}</td>
            <td>@if(@$task_details->late_feedback=='N'){{'No'}}@else{{'Yes'}}@endif</td>

            <td>{{@$task_details->rating}}</td>
            <td>{{@$task_details->task_complete_percent}}%</td>
            <td>{{@$task_details->task->property->property_name}}</td>
	        <td>{{@$task_details->task->property->address}}</td>
	        <td>{{@$task_details->task->property->owner_details->email}}</td>
	        <td>{{@$task_details->task->property->owner_details->name}}</td>
	        
            <td>
                {{@$task_details->get_status_name()}}
            </td>
	        <td>{{Carbon::parse(@$task_details->created_at)->format('d/m/Y')}}</td>
	    </tr>
    	@empty
    	<tr>
    		<td>No Task Details Found!</td>
    	</tr>
    	@endforelse
    </tbody>
</table>
