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

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h5>Delivery Address Details</h5>
                                        <hr>
                                        <form  method="post" id="shared_services_checkout_form" method="post" action="{{route('admin.shared_service_orders.submit_order')}}" >
                                      @csrf
                                       <div class="row">
                                         <div class="col-md-6">
                                            <div class="form-group">
                                             <label for="first_name">First Name <span class="error">*</span></label>
                                            <input type="text" name="first_name" placeholder="First Name" value="{{old('first_name')}}" class="form-control" id="first_name">
                                            @if($errors->has('first_name'))
                                            <div class="text-danger">{{$errors->first('first_name')}}</div>
                                            @endif
                                            </div>
                                         </div>
                                         <div class="col-md-6">
                                            <div class="form-group">
                                             <label for="last_name">Last Name <span class="error">*</span></label>
                                             <input type="text" name="last_name" value="{{old('last_name')}}"  placeholder="Last Name" class="form-control" id="last_name">
                                            @if($errors->has('last_name'))
                                            <div class="text-danger">{{$errors->first('last_name')}}</div>
                                            @endif
                                            </div>
                                         </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                            <label for="contact_number">Contact Number <span class="error">*</span></label>
                                             <input type="text" name="contact_number" value="{{old('contact_number')}}"  placeholder="Contact Number" class="form-control" id="contact_number">
                                            @if($errors->has('contact_number'))
                                            <div class="text-danger">{{$errors->first('contact_number')}}</div>
                                            @endif
                                            </div>
                                         </div>
                                         <div class="col-md-12">
                                            <div class="form-group">
                                            <label for="address_line_1">Address Line 1 <span class="error">*</span></label>
                                             <input type="text" name="address_line_1" value="{{old('address_line_1')}}"  placeholder="Address line 1" class="form-control" id="address_line_1">
                                            @if($errors->has('address_line_1'))
                                            <div class="text-danger">{{$errors->first('address_line_1')}}</div>
                                            @endif
                                            </div>
                                         </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                            <label for="address_line_2">Address Line 2</label>
                                             <input type="text" name="address_line_2" value="{{old('address_line_2')}}"  placeholder="Address line 2" class="form-control" id="address_line_2">
                                            @if($errors->has('address_line_2'))
                                            <div class="text-danger">{{$errors->first('address_line_2')}}</div>
                                            @endif
                                            </div>
                                         </div>
                           
                                        <div class="col-md-12">
                                            <div class="form-group">
                                            <label for="city_id">Select City <span class="error">*</span></label>
                                            <select class="form-control" name="city_id" id="city_id" style="width: 100%;">
                                               <option value="">Select city</option>
                                               @if(count($cities))
                                                  @foreach($cities as $city)
                                                    <option value="{{$city->id}}">{{$city->name}}</option>
                                                  @endforeach
                                               @else
                                                 <option value="">No city found</option> 
                                               @endif
                                            </select>
                                            @if($errors->has('city_id'))
                                            <div class="text-danger">{{$errors->first('city_id')}}</div>
                                            @endif
                                            </div>
                                         </div>
                                         <div class="col-md-12">
                                            <div class="form-group">
                                            <label for="pincode">Pincode <span class="error">*</span></label>
                                             <input type="text" name="pincode" value="{{old('pincode')}}"  placeholder="Pincode" class="form-control" id="pincode">
                                            @if($errors->has('pincode'))
                                            <div class="text-danger">{{$errors->first('pincode')}}</div>
                                            @endif
                                            </div>
                                         </div>
                                       </div>
                                        <div>
                                          <button type="submit" class="btn btn-success">Order Now</button>
                                        </div>
                                      </form>
                                    </div>
                                    <div class="col-md-6 table-responsive">
                                        <h5>Order Item Details</h5>
                                        <hr>
                                        <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40%">Service Name</th>
                                                    <th style="width: 20%">Quantity</th>
                                                    <th class="text-center">Price </th>
                                                    <th class="text-center">Total </th>
                                          
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($shared_service_carts))
                                                    @foreach($shared_service_carts as $cart)
                                                    <tr>
                                                        <td >
                                                        <div class="media">
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
                                                            <div >
                                                            {{$cart->quantity}}
                                                            </div>
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
                      
                                                    </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="4" align="center">No shared service in your cart</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                            @if(count($shared_service_carts))
                                            <tfoot>
                                                <tr>
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
                                                    <td>
                                                        <h5>Tax</h5></td>
                                                    <td class="text-right"><strong>({{$tax_percentage}}% ){{Helper::getSiteCurrency()}} {{number_format($tax_amount, 2, '.', '')}}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>   </td>
                                                    <td>   </td>
                                                    <td>
                                                        <h5>Total</h5>
                                                    </td>
                                                    <td class="text-right"><strong>{{Helper::getSiteCurrency()}} {{number_format($total, 2, '.', '')}}</strong>
                                                    </td>
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
                </div>
  
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/shared_service_orders/common.js')}}"></script>
@endpush


