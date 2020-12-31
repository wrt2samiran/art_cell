<table>
    <thead>
    <tr>
        <th></th>
        <th>Date</th>
        <th>Requested Work Order</th>
        <th>Completed Work Order</th>
    </tr>
    </thead>
    <tbody>
        @forelse($data as $record)
        <tr>
            <td></td>
            <td>{{$record['date']}}</td>
            <td>{{$record['requested_work_orders']}}</td>
            <td>{{$record['completed_work_orders']}}</td>
        </tr>
        @empty

        @endforelse
    </tbody>
</table>