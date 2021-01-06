<table>
    <thead>
    <tr>

        <th>Service Date</th>
        <th>Contract Code</th>
        <th>Contract Name</th>
        <th>Property Code</th>
        <th>Property Name</th>
        <th>City</th>
        <th>Location</th>
        <th>Property Owner Email</th>
        <th>Property Owner Name</th>
        <th>Task Title</th>
        <th>Service Name</th>
        <th>Service Provider Email</th>
        <th>Service Provider Name</th>
        <th>Labour Assigned</th>
    </tr>
    </thead>
    <tbody>
    	@forelse($service_dates as $service_date)
	    <tr>
            <td></td>
	        <td>{{$service_date->date}}</td>
	        <td>{{$service_date->contract->code}}</td>
	        <td>{{$service_date->contract->title}}</td>

	        <td>{{$service_date->contract->property->code}}</td>
	        <td>{{$service_date->contract->property->property_name}}</td>
	        <td>{{$service_date->contract->property->city->name}}</td>
	        <td>{{$service_date->contract->property->address}}</td>
	        <td>{{$service_date->contract->property->owner_details->email}}</td>
	        <td>{{$service_date->contract->property->owner_details->name}}</td>
	        <td>{{$service_date->task_title}}</td>
	        <td>{{$service_date->service->service_name}}</td>
	        <td>{{$service_date->contract->service_provider->email}}</td>
	        <td>{{$service_date->contract->service_provider->name}}</td>
            <th>
                @if(count($service_date->task_details_list))
                    @foreach($service_date->task_details_list as $key=>$labour_task)
                        {{($key!='0')?',':''}} {{$labour_task->userDetails->name}}
                    @endforeach
                @else
                No Labour Assigned
                @endif
            </th>
	    </tr>
    	@empty
    	<tr>
    		<td>No Completed Schedule</td>
    	</tr>
    	@endforelse
    </tbody>
</table>