<table>
    <thead>
    <tr>
        <td colspan="17" align="center" style="background-color: #97e6f7;font-size: 18px">
        <div>Work Order Report<div>
        </td>
       </tr>
    <tr>
        <td colspan="17" rowspan="2" align="center">
            <div>Report On : {{Carbon::now()->format('d/m/Y h:i A')}}</div><br>
            <div>From Date: {{request()->from_date}} - To Date: {{request()->to_date}}</div>
        </td>
       </tr>
       <tr>
           <td></td>
       </tr>
    <tr>
        <th>Task Id</th>
        <th>Task Title</th>
        <th>Work Order Title</th>
        <th>Contract Code</th>
        <th>Contract Name</th>
        <th>Service Type</th>
        <th>Service Name</th>
        <th>Property Name</th>
        <th>Property Address</th>
        <th>Property Owner Email</th>
        <th>Property Owner Name</th>
        <th>Task Completed Percent</th>
        <th>Current Status</th>
        <th>Created Date</th>
        
    </tr>
    </thead>
    <tbody>
    	@forelse($task_lists as $task_list)
	    <tr>
	        <td>{{@$task_list->id}}</td>
            <td>{{@$task_list->task_title}}</td>
	        <td>{{@$task_list->work_order->task_title}}</td>
            <td>{{@$task_list->contract->code}}</td>
	        <td>{{@$task_list->contract->title}}</td>
	        <td>{{@$task_list->contract_services->service_type}}</td>
	        <td>{{@$task_list->service->service_name}}</td>
            <td>{{@$task_list->property->property_name}}</td>
	        <td>{{@$task_list->property->address}}</td>
	        <td>{{@$task_list->property->owner_details->email}}</td>
	        <td>{{@$task_list->property->owner_details->name}}</td>
	        <td>{{@$task_list->task_complete_percent}}%</td>
            <td>
                {{@$task_list->get_status_name()}}
            </td>
	        <td>{{Carbon::parse(@$task_list->created_at)->format('d/m/Y')}}</td>
	    </tr>
    	@empty
    	<tr>
    		<td>No Task Found!</td>
    	</tr>
    	@endforelse
    </tbody>
</table>
