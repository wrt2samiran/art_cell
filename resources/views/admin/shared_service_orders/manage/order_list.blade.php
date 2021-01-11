@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('manage_order_module.manage_shared_service_order_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.shared_service_orders')}}</li>
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
                                    <div><span>{{__('manage_order_module.order_list')}}</span></div>
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
                                <table class="table table-bordered" id="manage_shared_service_orders_table">
                                    <thead>
                                        <tr>
                                            <th>{{__('manage_order_module.labels.order_id')}}</th>
                                            <th>{{__('manage_order_module.labels.order_by')}}</th>
                                            <th>{{__('manage_order_module.labels.total_shared_service')}}</th>
                                            <th>{{__('manage_order_module.labels.total_price')}}</th>
                                            <th>{{__('manage_order_module.labels.current_status')}}</th>
                                            <th style="width: 10%">{{__('manage_order_module.labels.details')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <input type="hidden" id="manage_shared_service_orders_data_url" value="{{route('admin.shared_service_orders.order_list')}}">
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


