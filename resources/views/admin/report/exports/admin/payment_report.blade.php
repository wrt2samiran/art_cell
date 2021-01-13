<table>

    <tbody>
        <tr>
        <td colspan="6" align="center" style="background-color: #97e6f7;font-size: 18px">
        <div>Financial Report<div>
        </td>
       </tr>
       <tr>
        <td colspan="6" rowspan="2" align="center">
            <div>Report On : {{Carbon::now()->format('d/m/Y h:i A')}}</div><br>
            <div>From Date: {{request()->from_date}} - To Date: {{request()->to_date}}</div>
        </td>
       </tr>
       <tr>
           <td></td>
       </tr>
        <tr>
            <td>Payment Date</td>
            <td>Amount</td>
            <td>Payment By</td>
            <td>Payment For</td>
            <td>Contract Code</td>
            <td>Contract Name</td>
        </tr>
        @forelse($payments as $payment)
        <tr>
            <td>{{Carbon::parse($payment->payment_on)->format('d/m/Y')}}</td>
            <td>{{$payment->currency}} {{number_format($payment->amount, 2, '.', '')}}</td>
            <td>{{$payment->user->name}}</td>
            <td>{{$payment->payment_for}}</td>
            <td>{{$payment->contract->code}}</td>
            <td>{{$payment->contract->title}}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No payment found</td>
        </tr>
        @endforelse
    </tbody>
</table>