@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Manage Shared Service Orders</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item" ><a href="{{route('admin.shared_service_orders.order_list')}}">Shared Service Orders</a></li>
                  <li class="breadcrumb-item active">Details</li>
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
                            <div class="card-header">
                                <div class="d-flex justify-content-between" >
                                    <div><span>Order Details</span></div>
                                    <div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                               
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
                                    <div class="col-md-12">
                                        <h5>User Details</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <td>Name</td>
                                                    <td>{{$order->user->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td>{{$order->user->email}}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <h5>Order Item Details</h5>
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
                                                @if(count($order->ordered_shared_services))
                                                    @foreach($order->ordered_shared_services as $ordered_shared_service)
                                                    <tr>
                                                        <td >
                                                            <div class="media">
                                                                <div class="media-body">

                                                                    <h5 class="media-heading">
                                                                        <span>
                                                                        {{$ordered_shared_service->shared_service->name}}
                                                                        </span>
                                                                    </h5>
                                                                    @if($ordered_shared_service->buy_or_rent=='rent')
                                                                    <h6 class="media-heading"> 
                                                                    <span>
                                                                    For - {{$ordered_shared_service->no_of_days}} {{($ordered_shared_service->no_of_extra_days>0)?'+ '.$ordered_shared_service->no_of_extra_days:''}} Days
                                                                    </span>
                                                                    </h6>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td  style="text-align: center">
                                                            <strong>{{$ordered_shared_service->quantity}}</strong>
                                                        </td>
                                                        <td class=" text-right">
                                                            <strong>
                                                            {{$order->order_currency}}
                                                            @if($ordered_shared_service->buy_or_rent=='rent')
                                                            {{number_format($ordered_shared_service->price, 2, '.', '')}}
                                                            @else
                                                            {{number_format($ordered_shared_service->buy_price, 2, '.', '')}}
                                                            @endif
                                                            </strong>

                                                            @if($ordered_shared_service->no_of_extra_days>0 && $ordered_shared_service->buy_or_rent=='rent')
                                                            <br>
                                                            <strong>+</strong><br>
                                                             <strong>{{$order->order_currency}}{{number_format($ordered_shared_service->extra_days_price, 2, '.', '')}}/day
                                                             </strong>
                                                            @endif

                                                        </td>
                                                        <td class="text-right">
                                                            <strong>{{$order->order_currency}}
                                                             {{number_format($ordered_shared_service->total_price, 2, '.', '')}}</strong>
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

                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Update Order Status</h5>
                                        <form class="form-inline" action="{{route('admin.shared_service_orders.update_order_status',$order->id)}}" method="post">
                                            @csrf
                                            @method("PUT")
                                          <label for="email" class="mr-sm-2">Status:</label>
                                      
                                            <select class="form-control mb-2 mr-sm-2" name="status" id="status">
                                                <option {{($order->curent_status=='Placed')?'selected':''}} value="Placed">Placed</option>
                                                <option {{($order->curent_status=='Delivered')?'selected':''}} value="Delivered">Delivered</option> 
                                            </select>
                                         
                        
                                          <button type="submit" class="btn btn-primary mb-2">Update</button>
                                        </form>
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
<script type="text/javascript" src="{{asset('js/admin/shared_service_orders/manage_order.js')}}"></script>
@endpush


