<table>

    <tbody>
       <tr>
        <td colspan="8" align="center" style="background-color: #97e6f7;font-size: 18px">
        <div>Contract Status Report<div>
        </td>
       </tr>
       <tr>
        <td colspan="8" rowspan="2" align="center">
            <div>Report On : {{Carbon::now()->format('d/m/Y h:i A')}}</div><br>
            <div>From Date: {{request()->from_date}} - To Date: {{request()->to_date}}</div>
        </td>
       </tr>
       <tr>
           <td></td>
       </tr>

        <tr>
            <td>Contract ID</td>
            <td>Contract Start Date</td>
            <td>Contract End Date</td>
            <td>Completed WO</td>
            <td>Pending WO</td>
            <td>Property</td>
            <td>Property Owner</td>
            <td>Service Provider</td>
        </tr>

    	@forelse($contracts as $contract)
    	<tr>

    		<td>{{$contract->id}}</td>
    		<td>{{Carbon::parse($contract->start_date)->format('d/m/Y')}}</td>
    		<td>{{Carbon::parse($contract->end_date)->format('d/m/Y')}}</td>
            <td>{{$contract->completed_work_orders}}</td>
            <td>{{$contract->pending_work_orders}}</td>
            <td>{{$contract->property->property_name}}</td>
            <td>{{$contract->property->owner_details->name}}</td>
            <td>{{$contract->service_provider->name}}</td>
    	</tr>
    	@empty
        <tr>
            <td colspan="8">No Contracts</td>
        </tr>
    	@endforelse
    </tbody>
</table>