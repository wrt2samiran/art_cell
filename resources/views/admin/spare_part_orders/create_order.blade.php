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
                                <table class="table table-bordered" id="spare_parts_for_order_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Spare Part Name</th>
                                            <th>Manufacturer</th>
                                            <th>Quantity Available</th>
                                            <th>Price</th>
                                            <th style="width: 30%">Add To Cart</th>
                                        </tr>
                                    </thead>
                                </table>

                                <input type="hidden" id="spare_parts_for_order_data_url" value="{{route('admin.spare_part_orders.create_order')}}">
                            </div>
                        </div>
                    </div>
                </div>
  
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/spare_part_orders/common.js')}}"></script>
@endpush


