@extends('admin.layouts.after-login-layout')


@section('unique-content')
<div class="content-wrapper" style="min-height: 1200.88px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Order Spare Parts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item active">Spare Parts</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            @include('admin.spare_part_orders.partials.navbar')
                            @if(Session::has('success'))
                                <div class="alert alert-success alert-dismissable __web-inspector-hide-shortcut__">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    {{ Session::get('success') }}
                                </div>
                            @endif
                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissable">
                                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%">Spare Part</th>
                                                <th style="width: 10%">Quantity</th>
                                                <th class="text-center">Price </th>
                                                <th class="text-center">Total </th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($spare_part_carts))
                                                @foreach($spare_part_carts as $cart)
                                                <tr>
                                                    <td >
                                                        <div class="media">
                                                            <img style="height: 60px;width: 80px" class="mr-3" src="{{$cart->spare_part_details->image_url}}" alt="{{$cart->spare_part_details->name}}">
                                                            <div class="media-body">
                                                                <h4 class="media-heading">
                                                                    <span>{{$cart->spare_part_details->name}}
                                                                    </span>
                                                                </h4>
                                                                <h5 class="media-heading"> By- <span>
                                                                    {{$cart->spare_part_details->manufacturer}}
                                                                </span></h5>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td  style="text-align: center">
                                                        <form method="post" action="{{route('admin.spare_part_orders.update_cart',$cart->id)}}">
                                                            @csrf
                                                            <input type="number" min="1" class="form-control update_quantity_field" name="quantity" data-cart_id="{{$cart->id}}" value="{{$cart->quantity}}">
                                                            <button id="cart_update_{{$cart->id}}" style="width: 100%;display: none" class="btn btn-primary mt-1">Update</button>
                                                        </form>
                                                    </td>
                                                    <td class=" text-center">
                                                        <strong>{{$cart->spare_part_details->currency}} {{number_format($cart->spare_part_details->price, 2, '.', '')}}</strong>

                                                    </td>
                                                    <td class="text-center">
                                                        <strong>{{$cart->spare_part_details->currency}}
                                                         {{number_format($cart->total, 2, '.', '')}}</strong>
                                                    </td>
                                                    <td class="">
                                                       <form method="post" action="{{route('admin.spare_part_orders.delete_cart',$cart->id)}}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button  class="btn btn-danger">
                                                                <span class="glyphicon glyphicon-remove"></span> Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                            <tr>
                                                <td colspan="5" align="center">No spare parts in your cart</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                        @if(count($spare_part_carts))
                                        <tfoot>
                                            <tr>
                                                <td>   </td>
                                                <td>   </td>
                                                <td>   </td>
                                                <td>
                                                    <h5>Subtotal</h5></td>
                                                <td class="text-right"><strong>{{Helper::getSiteCurrency()}} {{number_format($sub_total, 2, '.', '')}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>   </td>
                                                <td>   </td>
                                                <td>   </td>
                                                <td>
                                                    <h5>Tax</h5></td>
                                                <td class="text-right"><strong>({{$tax_percentage}}% ){{Helper::getSiteCurrency()}} {{number_format($tax_amount, 2, '.', '')}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>   </td>
                                                <td>   </td>
                                                <td>   </td>
                                                <td>
                                                    <h5>Total</h5></td>
                                                <td class="text-right"><strong>{{Helper::getSiteCurrency()}} {{number_format($total, 2, '.', '')}}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>   </td>
                                                <td>   </td>
                                                <td>   </td>
                                                <td>
                                                </td>
                                                <td>
                                                <a href="{{route('admin.spare_part_orders.checkout')}}" class="btn btn-success">
                                                    Checkout <span class="glyphicon glyphicon-play"></span>
                                                </a></td>
                                            </tr>
                                        </tfoot>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>

    </section>
</div>
@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/spare_part_orders/common.js')}}"></script>
@endpush

