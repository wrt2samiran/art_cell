<table>

    <tbody>

       <tr>
        <td colspan="17" align="center" style="background-color: #97e6f7;font-size: 18px">
        <div>Upcoming Weekly Schedule Maintenance Report<div>
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
            <td>Week Number</td>
            <td>Year</td>
            <td>Date Range</td>
            <td>Service Date</td>
            <td>Contract Code</td>
            <td>Contract Name</td>
            <td>Property Code</td>
            <td>Property Name</td>
            <td>City</td>
            <td>Location</td>
            <td>Property Owner Email</td>
            <td>Property Owner Name</td>
            <td>Task Title</td>
            <td>Service Name</td>
            <td>Service Provider Email</td>
            <td>Service Provider Name</td>
            <td>Labour Assigned</td>
        </tr>

    	@forelse($upcoming_weekly_services as $upcoming_weekly_service)

        @if(count($upcoming_weekly_service['upcoming_service_dates']))
            @php
            $index=0;
            @endphp
            @foreach($upcoming_weekly_service['upcoming_service_dates'] as $upcoming_service_date)
            <tr>

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
                {{Carbon::parse($upcoming_weekly_service['effective_from'])->format('d/m/Y')}}- {{Carbon::parse($upcoming_weekly_service['effective_to'])->format('d/m/Y')}}
                @endif 
                </td>

                <td>{{Carbon::parse($upcoming_service_date->date)->format('d/m/Y')}}</td>
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
                <td>
                    @if(count($upcoming_service_date->task_details_list))
                        @foreach($upcoming_service_date->task_details_list as $key=>$labour_task)
                            {{($key!='0')?',':''}} {{$labour_task->userDetails->name}}
                        @endforeach
                    @else
                    No Labour Assigned
                    @endif
                </td>
            </tr>
            @php
            $index++;
            @endphp
            @endforeach
        @else
        <tr>

            <td>{{$upcoming_weekly_service['week_number']}}</td>
            <td>{{$upcoming_weekly_service['year']}}</td>
            <td>{{Carbon::parse($upcoming_weekly_service['effective_from'])->format('d/m/Y')}}- {{Carbon::parse($upcoming_weekly_service['effective_to'])->format('d/m/Y')}}</td>

            @for($a=1;$a<15;$a++)
            <td>N/A</td>
            @endfor
        </tr>
        @endif



    	@empty
    	<tr>
    		<td colspan="17" align="center">No Upcoming Schedule</td>
    	</tr>
    	@endforelse
    </tbody>
</table>