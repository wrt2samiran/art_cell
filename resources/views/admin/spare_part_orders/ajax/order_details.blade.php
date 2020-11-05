<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50%">Spare Part</th>
                        <th style="width: 10%">Quantity</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Total </th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($order->ordered_spare_parts))
                        @foreach($order->ordered_spare_parts as $order_spare_part)
                        <tr>
                            <td >
                                <div class="media">
            
                                    <div class="media-body">
                                        <h4 class="media-heading">
                                            <a href="#">{{$order_spare_part->spare_part->name}}
                                            </a>
                                        </h4>
                                        <h5 class="media-heading"> BY- <a href="#">
                                            {{$order_spare_part->spare_part->manufacturer}}
                                        </a></h5>
                                
                                    </div>
                                </div>
                            </td>
                            <td  style="text-align: center">
                            	<strong>{{$order_spare_part->quantity}}</strong>
                            </td>
                            <td class=" text-right">
                                <strong>{{$order->order_currency}} {{number_format($order_spare_part->price, 2, '.', '')}}</strong>

                            </td>
                            <td class="text-right">
                                <strong>{{$order->order_currency}}
                                 {{number_format($order_spare_part->total_price, 2, '.', '')}}</strong>
                            </td>
                        </tr>
                        @endforeach
                    @else
         
                    @endif
                </tbody>
              
                <tfoot>
                    <tr>
                        <td> </td>
                        <td> </td>
                     
                        <td>
                            <h5>Subtotal</h5>
                        </td>
                        <td class="text-right"><strong>{{$order->order_currency}} {{number_format(($order->total_amount - $order->tax_amount - $order->delivery_charge), 2, '.', '')}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td> </td>
                     
                        <td>
                            <h5>Tax</h5>
                        </td>
                        <td class="text-right"><strong>{{$order->order_currency}} {{number_format($order->tax_amount, 2, '.', '')}}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td> </td>
                     
                        <td>
                            <h5>Total</h5>
                        </td>
                        <td class="text-right"><strong>{{$order->order_currency}} {{number_format($order->total_amount, 2, '.', '')}}</strong>
                        </td>
                    </tr>
                </tfoot>
 
            </table>
        </div>
    </div>

    <div class="row">
    	<div class="col-md-12">
    		<h5>Delivery Details</h5>
    		<div class="table-responsive">
    			<table class="table table-bordered table-hover">
    				<tr>
    					<td>Name</td>
    					<td>{{$order->delivery_address->first_name}} {{$order->delivery_address->last_name}}</td>
    				</tr>
    				<tr>
    					<td>Contact Number</td>
    					<td>{{$order->delivery_address->contact_number}}</td>
    				</tr>
    				<tr>
    					<td>Address</td>
    					<td>{{$order->delivery_address->address_line_1}} <br>{{$order->delivery_address->address_line_2}}</td>
    				</tr>
	  				<tr>
    					<td>City</td>
    					<td>{{$order->delivery_address->city->name}}</td>
    				</tr>
	  				<tr>
    					<td>Pincode</td>
    					<td>{{$order->delivery_address->pincode}}</td>
    				</tr>
    			</table>
    		</div>
    	</div>
    </div>
</div>