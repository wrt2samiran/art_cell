<table>
    <thead>
    <tr>

        <th>Month</th>
        <th>Date-Range</th>
        <th>Total Completed Work Orders</th>
        <th>Work Order Id</th>
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
        <th>Work Date</th>
        <th>Service Provider Email</th>
        <th>Service Provider Name</th>
        <th>Current Status</th>
        <th>Completed Date</th>
        <th>Created Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $month_year=>$record)
        @if(count($record['work_orders']))
            @foreach($record['work_orders'] as $key=> $work_order)
            <tr>

                <td>
                @if($key=='0')
                {{$month_year}}
                @endif
                </td>
                <td>
                @if($key=='0')
                {{$record['effective_from']}}-{{$record['effective_to']}}
                @endif
                </td>
                <td>
                @if($key=='0')
                {{$record['total_wo']}}
                @endif
                </td>
                <td>{{$work_order->id}}</td>
                <td>{{$work_order->contract->code}}</td>
                <td>{{$work_order->contract->title}}</td>
                <td>{{$work_order->property->code}}</td>
                <td>{{$work_order->property->property_name}}</td>
                <td>{{$work_order->property->city->name}}</td>
                <td>{{$work_order->property->address}}</td>
                <td>{{$work_order->property->owner_details->email}}</td>
                <td>{{$work_order->property->owner_details->name}}</td>
                <td>{{$work_order->task_title}}</td>
                <td>{{$work_order->service->service_name}}</td>
                <td>{{Carbon::parse($work_order->task_date)->format('d/m/Y')}}</td>
                <td>{{$work_order->service_provider->email}}</td>
                <td>{{$work_order->service_provider->name}}</td>

                <td>
                    {{$work_order->get_status_name()}}
                </td>
                <td>
                    @if($work_order->work_order_complete_date)
                    {{Carbon::parse($work_order->work_order_complete_date)->format('d/m/Y')}}
                    @else
                    N/A
                    @endif
                </td>
                <td>
                    {{Carbon::parse($work_order->created_at)->format('d/m/Y')}}
                </td>
            </tr>  
            @endforeach
        @else
        <tr>

            <td>{{$month_year}}</td>
            <td>{{$record['effective_from']}}-{{$record['effective_to']}}</td>
            <td>{{$record['total_wo']}}</td>
            @for($a=1;$a<18;$a++)
            <td>N/A</td>
            @endfor
            
        </tr> 
        @endif


    @endforeach
    </tbody>
</table>