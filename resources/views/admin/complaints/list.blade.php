@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Complaint Management</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Complaints</li>
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
				                    <div><span>Complaints List</span></div>
					                <div>
                                        
                                        @if(auth()->guard('admin')->user()->hasAllPermission(['complaint-create']))
                                        <a class="btn btn-success" href="{{route('admin.complaints.create')}}">
                                         Create Complaint
                                        </a>
                                        @endif 
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
                                <div class="filter-area ">
                                    <div class="row">
                                        <div class="col-sm-4" >
                                            <select class="form-control contract_id"  name="contract_id" id="contract_id">
                                                <option value="">Filter By Contract</option>
                                                @forelse($contracts as $contract)
                                                   <option value="{{$contract->id}}">{{$contract->title}}({{$contract->code}})</option>
                                                @empty
                                                <option value="">No Contract Found</option>
                                                @endforelse
                                            </select>
                                            <div class="cursor-poiner" title="Click to clear filter" style="display: none;" id="contract-filter-clear"><span class="badge badge-danger">Clear Filter<i class="fas fa-times"></i></span></div>
                                        </div>
                                </div>
                                <hr class="mt-3 mb-3"/>
                                <table class="table table-bordered" id="complaints_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Contract Code</th>
                                            <th>Work Order</th>
                                            <th>Complaint</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- calling this url from datatable data which is inside public\js\admin\complaints\list.js-->
                                <input type="hidden" id="complaints_data_url" value="{{route('admin.complaints.list')}}">
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/complaints/list.js')}}"></script>
@endpush


