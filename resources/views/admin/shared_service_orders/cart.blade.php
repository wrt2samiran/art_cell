@extends('admin.layouts.after-login-layout')


@section('unique-content')
    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Order Shared Servies</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Shared Services</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
<!-- 				            <div class="card-header">

				            </div> -->

                            <!-- /.card-header -->
                            <div class="card-body">
                                @include('admin.shared_service_orders.partials.navbar')
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
                                @if(Session::has('checkout_error'))
                                    <div class="alert alert-danger alert-dismissable">
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                        {{ Session::get('checkout_error') }}
                                    </div>
                                @endif
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40%">Service Name</th>
                                                    <th style="width: 20%">Quantity</th>
                                                    <th class="text-center">Price </th>
                                                    <th class="text-center">Total </th>
                                                    <th> </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($shared_service_carts))
                                                    @foreach($shared_service_carts as $cart)
                                                    <tr>
                                                        <td>
                              
                                                        <div class="media">
                                                            @if(count($cart->shared_service_details->images))
                                                                @foreach ($cart->shared_service_details->images as $key => $image)
                                                                <a style="display:{{($key=='0')?'block':'none'}}" href="{{asset('/uploads/shared_service_images/'.$image->image_name)}}" 
                                                                 data-fancybox="images-preview-{{$cart->shared_service_details->id}}" 
                                                                 data-width="1000" data-height="700"
                                                                 >
                                                                <img class="mr-3" style="height:60px;width:80px" src="{{asset('/uploads/shared_service_images/thumb/'.$image->image_name)}}" />
                                                                </a>

                                                                @endforeach
                                                            @else

                                                            <img class="mr-3" alt="{{$cart->shared_service_details->name}}" style="height:600px;width:80px" src="{{asset('/uploads/shared_service_images/no_image.png')}}"/>
                                                            @endif

                                                            <div class="media-body">
                                                                @if($cart->buy_or_rent=='buy')
                                                                <div>Buying</div>
                                                                @endif
                                                                <h5 class="media-heading">
                                                                    <span>
                                                                    {{$cart->shared_service_details->name}}
                                                                    </span>
                                                                </h5>
                                                                @if($cart->buy_or_rent=='rent')
                                                                <h6 class="media-heading"> 
                                                                <span>
                                                                For - {{$cart->shared_service_details->number_of_days}} {{($cart->no_of_extra_days>0)?'+ '.$cart->no_of_extra_days:''}} Days
                                                                </span>
                                                                </h6>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        </td>
                                                        <td  style="text-align: center">
                                                            <form method="post" action="{{route('admin.shared_service_orders.update_cart',$cart->id)}}">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                Quantity
                                                                <input type="number"  step="1" value="{{$cart->quantity}}" min="1" class="form-control update_quantity" data-cart_id="{{$cart->id}}" name="quantity" placeholder="Enter Quantity">
                                                                </div>
                                                                @if($cart->buy_or_rent=='rent')
                                                                <div class="col-md-6">
                                                                Extra Day <input type="number" data-cart_id="{{$cart->id}}" step="1" value="{{$cart->no_of_extra_days}}"  min="0" class="form-control update_no_of_extra_days" name="no_of_extra_days" placeholder="Extra Days">
                                                                </div>
                                                                @endif
                                                                <div class="col-md-12">
                                                                   <button id="cart_update_{{$cart->id}}" style="width: 100%;display: none" class="btn btn-primary mt-1">Update</button>
                                                                </div>
                                                            </div>
                                                            </form>
                                                        </td>
                                                        <td class=" text-center">
                                                            <strong>
                                                            {{$cart->shared_service_details->currency}}
                                                            @if($cart->buy_or_rent=='rent')
                                                            {{number_format($cart->shared_service_details->price, 2, '.', '')}}
                                                            @else
                                                            {{number_format($cart->shared_service_details->selling_price, 2, '.', '')}}
                                                            @endif
                                                            </strong>

                                                            @if($cart->no_of_extra_days>0 && $cart->buy_or_rent=='rent')
                                                            <br>
                                                            <strong>+</strong><br>
                                                             <strong>{{$cart->shared_service_details->currency}} {{number_format($cart->shared_service_details->extra_price_per_day, 2, '.', '')}}/day
                                                             </strong>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <strong>{{$cart->shared_service_details->currency}}
                                                             {{number_format($cart->total, 2, '.', '')}}</strong>
                                                        </td>
                                                        <td class="">
                                                           <form method="post" action="{{route('admin.shared_service_orders.delete_cart',$cart->id)}}">
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
                                                    <td colspan="5" align="center">No shared service in your cart</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                            @if(count($shared_service_carts))
                                            <tfoot>
                                                <tr>
                                                    <td>   </td>
                                                    <td>   </td>
                                                    <td>   </td>
                                                    <td>
                                                        <h5>Subtotal</h5>
                                                    </td>
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
                                                        <h5>Total</h5>
                                                    </td>
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
                                                    <a href="{{route('admin.shared_service_orders.checkout')}}" class="btn btn-success">
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
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/shared_service_orders/common.js')}}"></script>
@endpush


