@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Shared Service Management</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Shared Service</li>
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
				                    <div><span>Shared Service List</span></div>
					                <div>
						                <a class="btn btn-success" href="{{route('admin.shared-service.add')}}">
						                 Create Shared Service
						                </a>
					                </div>
				                </div>
				            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered" id="shared_service_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Shared Service Name</th>
                                            <th>Number of Days</th>
                                            <th>Price</th>
                                            <th>Extra Price/Day</th>
                                            
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
  
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/shared_service/list.js')}}"></script>
@endpush


