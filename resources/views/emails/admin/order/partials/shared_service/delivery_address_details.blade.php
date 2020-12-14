  <table width="650" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto 20px; border-collapse:collapse; border: 1px solid #666;">
     <tr>

        <td  align="left" valign="top" style="padding:10px; font-size:14px; font-weight:bold; border: 1px solid #666; background:#ddd;">Delivery Address</td>
     </tr>
     <tr>

        <td align="left" valign="top" style="padding:10px; font-size:14px; border: 1px solid #666;">
	            <span>Name : {{$order->delivery_address->first_name}} {{$order->delivery_address->last_name}} </span><br>
            
                <span>Mobile Number : {{$order->delivery_address->contact_number}}</span>  <br>
                <span>Address : {{$order->delivery_address->address_line_1}}
                  
                @if($order->delivery_address->address_line_2)
                <br>{{$order->delivery_address->address_line_2}}
                @endif
             </span>
             <br>
                @if($order->delivery_address->city)
                <span>City : {{$order->delivery_address->city->name}}</span> <br>
                @endif
                <span>Pincode : {{$order->delivery_address->pincode}}</span>
        </td>
     </tr>
  </table>