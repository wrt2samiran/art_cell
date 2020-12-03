@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Manage Spare Part Orders</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Spare Part Orders</li>
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
                                    <div><span>Order List</span></div>
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
                                <table class="table table-bordered" id="manage_spare_parts_ordered_table">
                                    <thead>
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Order By</th>
                                            <th>Total Spare Parts</th>
                                            <th>Total Price</th>
                                            <th>Current Status</th>
                                            <th style="width: 10%">Details</th>
                                        </tr>
                                    </thead>
                                </table>

                                <input type="hidden" id="manage_spare_parts_orders_data_url" value="{{route('admin.spare_part_orders.order_list')}}">
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

