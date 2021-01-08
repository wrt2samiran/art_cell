@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('manage_order_module.manage_spare_part_order_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item" ><a href="{{route('admin.spare_part_orders.order_list')}}">{{__('general_sentence.breadcrumbs.spare_part_orders')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.details')}}</li>
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
                                    <div><span>{{__('manage_order_module.order_details')}}</span></div>
                                    <div>
                                        <a class="btn btn-primary" href="{{route('admin.spare_part_orders.download_invoice',$order->id)}}">{{__('general_sentence.button_and_links.download_invoice')}}</a>
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
                                        <h5>{{__('manage_order_module.user_details')}}</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <td>{{__('manage_order_module.labels.name')}}</td>
                                                    <td>{{$order->user->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('manage_order_module.labels.email')}}</td>
                                                    <td>{{$order->user->email}}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <h5>{{__('manage_order_module.order_item_details')}}</h5>
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50%">{{__('manage_order_module.labels.spare_part')}}</th>
                                                    <th style="width: 10%">{{__('manage_order_module.labels.quantity')}}</th>
                                                    <th class="text-center">{{__('manage_order_module.labels.price')}}</th>
                                                    <th class="text-center">{{__('manage_order_module.labels.total')}} </th>
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
                                                        <h5>{{__('manage_order_module.labels.sub_total')}}</h5>
                                                    </td>
                                                    <td class="text-right"><strong>{{$order->order_currency}} {{number_format(($order->total_amount - $order->tax_amount - $order->delivery_charge), 2, '.', '')}}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> </td>
                                                    <td> </td>
                                                 
                                                    <td>
                                                        <h5>{{__('manage_order_module.labels.vat')}}</h5>
                                                    </td>
                                                    <td class="text-right"><strong>{{$order->order_currency}} {{number_format($order->tax_amount, 2, '.', '')}}</strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td> </td>
                                                    <td> </td>
                                                 
                                                    <td>
                                                        <h5>{{__('manage_order_module.labels.total')}}</h5>
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
                                        <h5>{{__('manage_order_module.delivery_details')}}</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <td>{{__('manage_order_module.labels.name')}}</td>
                                                    <td>{{$order->delivery_address->first_name}} {{$order->delivery_address->last_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('manage_order_module.labels.contact_number')}}</td>
                                                    <td>{{$order->delivery_address->contact_number}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('manage_order_module.labels.address')}}</td>
                                                    <td>{{$order->delivery_address->address_line_1}} <br>{{$order->delivery_address->address_line_2}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('manage_order_module.labels.city')}}</td>
                                                    <td>{{$order->delivery_address->city->name}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{__('manage_order_module.labels.pincode')}}</td>
                                                    <td>{{$order->delivery_address->pincode}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>{{__('manage_order_module.update_order_status')}}</h5>
                                        <form class="form-inline" action="{{route('admin.spare_part_orders.update_order_status',$order->id)}}" method="post">
                                            @csrf
                                            @method("PUT")
                                          <label for="email" class="mr-sm-2">{{__('manage_order_module.labels.status')}}:</label>
                                      
                                            <select class="form-control mb-2 mr-sm-2" name="status" id="status">
                                              @forelse($statuses as $status)
                                              <option {{($status->id==$order->status_id)?'selected':''}} value="{{$status->id}}">{{$status->status_name}}</option>
                                              @empty
                                              <option value="">No status found</option>
                                              @endforelse
                                            </select>
                                         
                        
                                          <button type="submit" class="btn btn-success mb-2">{{__('general_sentence.button_and_links.update')}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-primary" href="{{route('admin.spare_part_orders.order_list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a>
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
<script type="text/javascript" src="{{asset('js/admin/spare_part_orders/manage_order.js')}}"></script>
@endpush


