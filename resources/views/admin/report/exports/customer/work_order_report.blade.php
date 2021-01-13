<table>
    <tbody>
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
    	@forelse($work_orders as $work_order)
	    <tr>

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
    	@empty
        <tr>
            <td colspan="17" align="center">No Work Orders</td>
        </tr>
    	@endforelse
    </tbody>
</table>
