@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Work Order Management</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Work Order</li>
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
                                    <div><span>Work Order List</span></div>
                                    @if(\Auth::guard('admin')->user()->role_id==3 || \Auth::guard('admin')->user()->role_id == 2)
                                      <div>
                                        <a class="btn btn-success" href="{{route('admin.work-order-management.workOrderCreate')}}">
                                         Work Order List
                                        </a>
                                      </div>
                                    @endif
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
                                
                                <table class="table table-bordered" id="work_order_management_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Contract Id</th>
                                            <th>Title</th>
                                            <th>Property Name</th>
                                            <th>Service</th>
                                            <th>Country</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Service Start Date</th>
                                            
                                            <th>Status</th>
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <input type="hidden" id="work_order_data_url" value="{{route('admin.work-order-management.list')}}">
                            </div>
                        </div>
                    </div>
                </div>
  
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/work_order_management/list.js')}}"></script>
@endpush


