@php
$current_route_name=request()->route()->getName();
@endphp
<ul class="nav nav-tabs mb-4">
  <li class="nav-item">
    <a class="nav-link {{($current_route_name=='admin.shared_service_orders.create_order')?'active':''}}" href="{{route('admin.shared_service_orders.create_order')}}">Create Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link
     {{($current_route_name=='admin.shared_service_orders.cart' || $current_route_name=='admin.shared_service_orders.checkout'  )?'active':''}}" href="{{route('admin.shared_service_orders.cart')}}">Cart</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{($current_route_name=='admin.shared_service_orders.my_orders')?'active':''}}" href="{{route('admin.shared_service_orders.my_orders')}}">My Orders</a>
  </li>
</ul>