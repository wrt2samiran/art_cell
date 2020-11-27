<html>
<head>
	<title>Shared Service - Invoice</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<meta http-equiv="content-type" content="text-html; charset=utf-8">
	<style type="text/css">
body {
  font-family: 'Source Sans Pro', sans-serif;
  font-weight: 300;
  font-size: 12px;
  margin: 0;
  padding: 0;
  color: #555555;
}
body a {
  text-decoration: none;
  color: inherit;
}
body a:hover {
  color: inherit;
  opacity: 0.7;
}
body .clearfix:after {
  content: "";
  display: table;
  clear: both;
}
body .left {
  float: left;
}
body .right {
  float: right;
}
body .helper {
  display: inline-block;
  height: 100%;
  vertical-align: middle;
}
body .no-break {
  page-break-inside: avoid;
}
header {
}
header figure {
  float: left;
  margin-right: 10px;
  width: 65px;
  height: 70px;
  text-align: center;
}
header .company-info {
  float: right;
  color: #66BDA9;
  line-height: 14px;
}
section .details {
  min-width: 440px;
  margin-bottom: 40px;
  padding: 2px 10px;
  line-height: 20px;
}
section .details .client {
  width: 50%;
}
section .details .client .name {
  font-size: 1.16666666666667em;
  font-weight: 600;
}
section .details .data {
  width: 50%;
  font-weight: 600;
  text-align: right;
}
section .details .title {
  margin-bottom: 5px;
  font-size: 1.33333333333333em;
  text-transform: uppercase;
}
section table {
  width: 100%;
  margin-bottom: 20px;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
}
section table .qty, section table .unit, section table .total {
  width: 15%;
}
section table .desc {
  width: 55%;
}
section table thead {
  display: table-header-group;
  vertical-align: middle;
  border-color: inherit;
}
section table thead th {
  padding: 7px 10px;
  background: #66BDA9;
  border-right: 1px solid #FFFFFF;
  color: white;
  text-align: center;
  font-weight: 400;
  text-transform: uppercase;
}
section table thead th:last-child {
  border-right: none;
}
section table tbody tr:first-child td {
  border-top: 5px solid #ffffff;
}
section table tbody td {
  padding: 0px 10px;
  text-align: center;
  border-right: 1px solid #66BDA9;
}
section table tbody td:last-child {
  border-right: none;
}
section table tbody td.desc {
  text-align: left;
}
section table tbody td.total {
  color: #66BDA9;
  font-weight: 600;
  text-align: right;
}
section table tbody td.desc  h5.item_title {
  color: #66BDA9;
  font-weight: 600;
  font-size: 15px;
  padding: 0px;
  margin: 0px;
}

section table tbody td.desc .item_extra_days {
  font-weight: 500;
  font-size: 8px;
}


section table.grand-total {
  margin-bottom: 50px;
}
section table.grand-total tbody tr td {
  padding: 0px 10px 12px;
  border: none;
  font-weight: 300;
  text-align: right;
}
section table.grand-total tbody tr:first-child td {
  padding-top: 12px;
}
section table.grand-total tbody tr:last-child td {
  background-color: transparent;
}
section table.grand-total tbody .grand-total {
  padding: 0;
}
section table.grand-total tbody .grand-total div {
  float: right;
  padding: 11px 10px;
  font-weight: 600;
}
section table.grand-total tbody .grand-total div span {
  display: inline-block;
  margin-right: 20px;
  width: 80px;
}
footer {
  margin-bottom: 15px;
  padding: 0 5px;
}
footer .thanks {
  margin-bottom: 40px;
  color: #66BDA9;
  font-size: 1.16666666666667em;
  font-weight: 600;
}
footer .notice {
  margin-bottom: 15px;
}
footer .end {
  padding-top: 5px;
  border-top: 2px solid #66BDA9;
  text-align: center;
}
</style>
</head>
<body>
	<header class="clearfix">
		<div class="container">
			<figure>
				<img class="logo" src="https://www.imtech.co.uk/wp-content/uploads/2019/12/dummy-logo.jpg" alt="">
			</figure>
			<div class="company-info">
				<div>
					<p>SMMS</p>
					<p>Saudi Arab</p>
					<p><a href="tel:602-519-0450">(602) 519-0450</a></p>
					<p><a href="mailto:company@example.com">company@example.com</a></p>
				</div>
			</div>
		</div>
	</header>
	<hr>
	<section>
		<div class="container">
			<div class="details clearfix">
				<div class="client left">
					<div class="name">{{$order->delivery_address->first_name}} {{$order->delivery_address->last_name}}</div>
					<div>{{$order->delivery_address->address_line_1}},</div>
					<div>{{$order->delivery_address->city->name}}</div>
					<div>{{$order->delivery_address->pincode}}</div>
				</div>
				<div class="data right">
					<div class="title">Order #{{$order->id}}</div>
					<div class="date">
						Date of Order: {{$order->created_at->format('d/m/Y')}}
					</div>
				</div>
			</div>
			<table class="item_details" border="0" cellspacing="0" cellpadding="0" >
				<thead>
					<tr>
						<th class="qty">Quantity</th>
						<th class="desc">Description</th>
						<th class="unit">Unit price</th>
						<th class="total">Total</th>
					</tr>

				</thead>
				<tbody>
          @if(count($order->ordered_spare_parts))
          @foreach($order->ordered_spare_parts as $order_spare_part)
					<tr>
						<td class="qty">{{$order_spare_part->quantity}}</td>
						<td class="desc">
							<h5 class="item_title">{{$order_spare_part->spare_part->name}}</h5>
              <div class="item_extra_days">
              By - {{$order_spare_part->spare_part->manufacturer}}
              </div>
						</td>
						<td class="unit">
              {{$order->order_currency}} {{number_format($order_spare_part->price, 2, '.', '')}}
						</td>
						<td class="total">
              {{$order->order_currency}}{{number_format($order_spare_part->total_price, 2, '.', '')}}
            </td>
					</tr>
          @endforeach
          @else

          @endif
				</tbody>
			</table>
			<div class="no-break">
				<table class="grand-total">
					<tbody>
						<tr class="total">
							<td class="qty"></td>
							<td class="desc"></td>
							<td class="unit">SUBTOTAL:</td>
							<td class="total">{{$order->order_currency}} {{number_format(($order->total_amount - $order->tax_amount - $order->delivery_charge), 2, '.', '')}}</td>
						</tr>

						<tr class="total">
							<td class="qty"></td>
							<td class="desc"></td>
							<td class="unit">TAX:</td>
							<td class="total">{{$order->order_currency}} {{number_format($order->tax_amount, 2, '.', '')}}</td>
						</tr>
						<tr class="total">
							<td class="qty"></td>
							<td class="desc"></td>
							<td class="unit">GRAND TOTAL:</td>
							<td class="total">{{$order->order_currency}} {{number_format($order->total_amount, 2, '.', '')}}</td>
						</tr>
				
					</tbody>
				</table>
			</div>
		</div>
	</section>
	<footer>
		<div class="container">
			<div class="thanks">Thank you!</div>
		</div>
	</footer>
</body>
</html>
