@php
$current_route_name=request()->route()->getName();
@endphp
<ul class="nav nav-tabs mb-4">
  <li class="nav-item">
    <a class="nav-link {{($current_route_name=='admin.spare_parts_for_order')?'active':''}}" href="{{route('admin.spare_parts_for_order')}}">Create Order</a>
  </li>
  <li class="nav-item">
    <a class="nav-link
     {{($current_route_name=='admin.spare_parts_cart' || $current_route_name=='admin.spare_parts_checkout'  )?'active':''}}" href="{{route('admin.spare_parts_cart')}}">Cart</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{($current_route_name=='admin.spare_parts_ordered')?'active':''}}" href="{{route('admin.spare_parts_ordered')}}">Ordered Spare Parts</a>
  </li>
</ul>