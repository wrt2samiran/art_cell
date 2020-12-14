   <table width="650" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto 20px; border-collapse:collapse; border: 1px solid #666;">
<tr>
  <td width="40%" align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#eaf4d8;">Spare Part</td>
  <td width="20%" align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#eaf4d8;">Quantity</td>
  <td width="20%" align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#eaf4d8;">Unit Price</td>
  <td width="20%" align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#eaf4d8;">Amount</td>
</tr>

@foreach($order->ordered_spare_parts as $order_spare_part) 

<tr>
<td  align="left" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">
  <div> 
      
      <a href="#">{{$order_spare_part->spare_part->name}}</a><br>
      BY-<a href="#">{{$order_spare_part->spare_part->manufacturer}}</a>
  </div>
</td>
<td align="left" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">{{$order_spare_part->quantity}}</td>

<td  align="right" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">{{$order->order_currency}} {{number_format($order_spare_part->price, 2, '.', '')}}
</td>
<td align="right" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">{{$order->order_currency}}{{number_format($order_spare_part->total_price, 2, '.', '')}}
</td>
</tr>

@endforeach

<tr>
  <td colspan="3" align="right" valign="top" style="padding:10px; font-size:14px; font-weight: bold; border: 1px solid #666;">Sub Total</td>
  <td align="right" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;"> {{$order->order_currency}}{{number_format(($order->total_amount - $order->tax_amount - $order->delivery_charge), 2, '.', '')}}</td>
</tr>
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