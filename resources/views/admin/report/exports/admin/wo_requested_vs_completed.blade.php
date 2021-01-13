<table>

    <tbody>
        <tr>
        <td colspan="3" align="center" style="background-color: #97e6f7;font-size: 18px">
        <div>Work Order Requested vs Completed Report<div>
        </td>
       </tr>
       <tr>
        <td colspan="3" rowspan="2" align="center">
            <div>Report On : {{Carbon::now()->format('d/m/Y h:i A')}}</div><br>
            <div>From Date: {{request()->from_date}} - To Date: {{request()->to_date}}</div>
        </td>
       </tr>
       <tr>
           <td></td>
       </tr>
        <tr>
            <td>Date</td>
            <td>Requested Work Order</td>
            <td>Completed Work Order</td>
        </tr>
        @forelse($data as $record)
        <tr>
          
            <td>{{Carbon::parse($record['date'])->format('d/m/Y')}}</td>
            <td>{{$record['requested_work_orders']}}</td>
            <td>{{$record['completed_work_orders']}}</td>
        </tr>
        @empty

        @endforelse
    </tbody>
</table>