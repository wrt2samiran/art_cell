@extends('admin.layouts.after-login-layout')


@section('unique-content')
    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Order Shared Services</h1>
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
                                @include('admin.shared_service_orders.partials.order_details_modal')
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
                                <table class="table table-bordered" id="my_shared_service_orders_table">
                                    <thead>
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Total Shared Service</th>
                                            <th>Total Price</th>
                                            <th>Current Status</th>
                                            <th style="width: 10%">Details</th>
                                        </tr>
                                    </thead>
                                </table>

                                <input type="hidden" id="my_shared_service_orders_data_url" value="{{route('admin.shared_service_orders.my_orders')}}">
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


