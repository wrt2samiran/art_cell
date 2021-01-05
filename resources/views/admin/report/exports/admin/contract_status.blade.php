<table>
    <thead>
    <tr>
        <th></th>
        <th>Contract ID</th>
        <th>Contract Start Date</th>
        <th>Contract End Date</th>
        <th>Completed WO</th>
        <th>Pending WO</th>
        <th>Property</th>
        <th>Property Owner</th>
        <th>Service Provider</th>
    </tr>
    </thead>
    <tbody>
    	@forelse($contracts as $contract)
    	<tr>
            <td></td>
    		<td>{{$contract->id}}</td>
    		<td>{{$contract->start_date}}</td>
    		<td>{{$contract->end_date}}</td>
            <td>{{$contract->completed_work_orders}}</td>
            <td>{{$contract->pending_work_orders}}</td>
            <td>{{$contract->property->property_name}}</td>
            <td>{{$contract->property->owner_details->name}}</td>
            <td>{{$contract->service_provider->name}}</td>
    	</tr>
    	@empty
        <tr>
            <td>No Contracts</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    	@endforelse
    </tbody>
</table>