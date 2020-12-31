<table>
    <thead>
    <tr>
        <th></th>
        <th>Week Number</th>
        <th>Year</th>
        <th>Date Range</th>
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
    	@forelse($upcoming_weekly_services as $upcoming_weekly_service)

        @if(count($upcoming_weekly_service['upcoming_service_dates']))
            @php
            $index=0;
            @endphp
            @foreach($upcoming_weekly_service['upcoming_service_dates'] as $upcoming_service_date)
            <tr>
                <td></td>
                <td>
                @if($index=='0')
                {{$upcoming_weekly_service['week_number']}}
                @endif  
                
                </td>
                <td>
                @if($index=='0')
                {{$upcoming_weekly_service['year']}}
                @endif 
                </td>
                <td>
                @if($index=='0')
                {{$upcoming_weekly_service['effective_from']}}- {{$upcoming_weekly_service['effective_to']}}
                @endif 
                </td>

                <td>{{$upcoming_service_date->date}}</td>
                <td>{{$upcoming_service_date->contract->code}}</td>
                <td>{{$upcoming_service_date->contract->title}}</td>

                <td>{{$upcoming_service_date->contract->property->code}}</td>
                <td>{{$upcoming_service_date->contract->property->property_name}}</td>
                <td>{{$upcoming_service_date->contract->property->city->name}}</td>
                <td>{{$upcoming_service_date->contract->property->address}}</td>
                <td>{{$upcoming_service_date->contract->property->owner_details->email}}</td>
                <td>{{$upcoming_service_date->contract->property->owner_details->name}}</td>
                <td>{{$upcoming_service_date->task_title}}</td>
                <td>{{$upcoming_service_date->service->service_name}}</td>
                <td>{{$upcoming_service_date->contract->service_provider->email}}</td>
                <td>{{$upcoming_service_date->contract->service_provider->name}}</td>
                <th>
                    @if(count($upcoming_service_date->task_details_list))
                        @foreach($upcoming_service_date->task_details_list as $key=>$labour_task)
                            {{($key!='0')?',':''}} {{$labour_task->userDetails->name}}
                        @endforeach
                    @else
                    No Labour Assigned
                    @endif
                </th>
            </tr>
            @php
            $index++;
            @endphp
            @endforeach
        @else
        <tr>
            <td></td>
            <td>{{$upcoming_weekly_service['week_number']}}</td>
            <td>{{$upcoming_weekly_service['year']}}</td>
            <td>{{$upcoming_weekly_service['effective_from']}}- {{$upcoming_weekly_service['effective_to']}}</td>

            @for($a=1;$a<16;$a++)
            <td>N/A</td>
            @endfor
        </tr>
        @endif



    	@empty
    	<tr>
    		<td>No Upcoming Schedule</td>
    	</tr>
    	@endforelse
    </tbody>
</table>