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
                                @include('admin.order_spare_parts.partials.navbar')
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
                                        <form  method="post" id="spare_part_checkout_form" method="post" action="{{route('admin.spare_parts_submit_order')}}" >
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
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50%">Spare Part</th>
                                                    <th style="width: 10%">Quantity</th>
                                                    <th class="text-center">Price </th>
                                                    <th class="text-center">Total </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(count($spare_part_carts))
                                                    @foreach($spare_part_carts as $cart)
                                                    <tr>
                                                        <td >
                                                            <div class="media">
                                        
                                                                <div class="media-body">
                                                                    <h4 class="media-heading">
                                                                        <a href="#">{{$cart->spare_part_details->name}}
                                                                        </a>
                                                                    </h4>
                                                                    <h5 class="media-heading"> BY- <a href="#">
                                                                        {{$cart->spare_part_details->manufacturer}}
                                                                    </a></h5>
                                                                    <span>Status: </span><span class="text-success"><strong>In Stock</strong></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td  style="text-align: center">
                                                            <b>{{$cart->quantity}}</b>
                                                        </td>
                                                        <td class=" text-center">
                                                            <strong>{{$cart->spare_part_details->currency}} {{number_format($cart->spare_part_details->price, 2, '.', '')}}</strong>

                                                        </td>
                                                        <td class="text-center">
                                                            <strong>{{$cart->spare_part_details->currency}}
                                                             {{number_format($cart->total, 2, '.', '')}}</strong>
                                                        </td>
               
                                                    </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="4" align="center">No spare parts in your cart</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                            @if(count($spare_part_carts))
                                            <tfoot>
                                                <tr>
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
                                                    
                                                    <td>
                                                        <h5>Tax</h5>
                                                    </td>
                                                    <td class="text-right"><strong>({{$tax_percentage}}% ){{Helper::getSiteCurrency()}} {{number_format($tax_amount, 2, '.', '')}}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>   </td>
                                                    <td>   </td>
                                                  
                                                    <td>
                                                        <h5>Total</h5></td>
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
  
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/order_spare_parts/spare_parts_for_order.js')}}"></script>
@endpush


