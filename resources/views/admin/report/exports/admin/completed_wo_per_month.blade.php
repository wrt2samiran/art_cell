<table>
    <tbody>
   <tr>
    <td colspan="20" align="center" style="background-color: #97e6f7;font-size: 18px">
    <div>Completed Work Orders/Month Report<div>
    </td>
   </tr>
   <tr>
    <td colspan="20" rowspan="2" align="center">
        <div>Report On : {{Carbon::now()->format('d/m/Y h:i A')}}</div><br>
        <div>From Date: {{request()->from_date}} - To Date: {{request()->to_date}}</div>
    </td>
   </tr>
   <tr>
       <td></td>
   </tr>

    <tr>
        <td>Month</td>
        <td>Date-Range</td>
        <td>Total Completed Work Orders</td>
        <td>Work Order Id</td>
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
        <td>Work Date</td>
        <td>Service Provider Email</td>
        <td>Service Provider Name</td>
        <td>Current Status</td>
        <td>Completed Date</td>
        <td>Created Date</td>
    </tr>
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
                {{Carbon::parse($record['effective_from'])->format('d/m/Y')}}-{{Carbon::parse($record['effective_to'])->format('d/m/Y')}}
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
            <td>{{Carbon::parse($record['effective_from'])->format('d/m/Y')}}-{{Carbon::parse($record['effective_to'])->format('d/m/Y')}}</td>
            <td>{{$record['total_wo']}}</td>
            @for($a=1;$a<18;$a++)
            <td>N/A</td>
            @endfor
            
        </tr> 
        @endif


    @endforeach
    </tbody>
</table>