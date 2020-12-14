   <table width="650" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto 20px; border-collapse:collapse; border: 1px solid #666;">
<tr>
  <td width="40%" align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#eaf4d8;">Shared Service</td>
  <td width="20%" align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#eaf4d8;">Quantity</td>
  <td width="20%" align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#eaf4d8;">Unit Price</td>
  <td width="20%" align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#eaf4d8;">Amount</td>
</tr>

@foreach($order->ordered_shared_services as $ordered_shared_service) 

<tr>
<td  align="left" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">
  <div> 
    <a href="#">{{$ordered_shared_service->shared_service->name}}
    </a>
    @if($ordered_shared_service->buy_or_rent=='rent')
    <br>
    <span>
    For - {{$ordered_shared_service->no_of_days}} {{($ordered_shared_service->no_of_extra_days>0)?'+ '.$ordered_shared_service->no_of_extra_days:''}} Days
    </span>
    @endif

  </div>
</td>
<td align="left" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">{{$ordered_shared_service->quantity}}</td>

<td  align="right" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">{{$order->order_currency}}{{number_format($ordered_shared_service->total_unit_price, 2, '.', '')}}
</td>
<td align="right" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">{{$order->order_currency}}{{number_format($ordered_shared_service->total_price, 2, '.', '')}}
</td>
</tr>

@endforeach


<tr>
  <td colspan="3" align="right" valign="top" style="padding:10px; font-size:14px; font-weight: bold; border: 1px solid #666;">Tax</td>
  <td align="right" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;"> 


      +{{$order->order_currency}}{{number_format($order->tax_amount, 2, '.', '')}}
  </td>
</tr>

 <tr>
  <td colspan="3" align="right" valign="top" style="padding:10px; font-size:14px; font-weight: bold; border: 1px solid #666;">Total Amount</td>
  <td align="right" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;"> 
    {{$order->order_currency}}{{number_format($order->total_amount , 2, '.', '')}}
  </td>
</tr>

</table>