@extends('admin.layouts.after-login-layout')

@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Contracts</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Contracts</li>
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
				                    <div><span>Contract List</span></div>
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
                                        <div class="col-sm-4" id="status-filter-container">
                                            <select class="form-control status-filter"  name="contract_status_id" id="contract_status_id">
                                                <option value="">Filter by Status</option>
                                                @forelse($ContractStatus as $status)
                                                   <option value="{{$status->id}}">{{$status->status_name}}</option>
                                                @empty
                                                <option value="">No Service Found</option>
                                                @endforelse
                                           </select>
                                           <div class="cursor-poiner" title="Click to clear filter" style="display: none;" id="status-filter-clear"><span class="badge badge-danger">Clear Filter<i class="fas fa-times"></i></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input class="form-control" type="text" name="contract_duration" id="contract_duration" placeholder="Search By Date">
                                                

                                                    <input type="hidden" name="daterange" id="daterange" placeholder="Search By Date">

                                                    
                                                </div>
                                                <div class="cursor-poiner" title="Click to clear filter" style="display: none;" id="contract_duration-filter-clear"><span class="badge badge-danger">Clear Filter<i class="fas fa-times"></i></span></div>
                                                
                                                                        
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <hr class="mt-3 mb-3"/>
                                <table class="table table-bordered" id="user_contracts_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Contract Code</th>
                                            <th>Info</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- calling this url from datatable data which is inside public\js\admin\user_contracts\list.js-->
                                <input type="hidden" id="user_contracts_data_url" value="{{route('admin.user_contracts.list')}}">
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/user_contracts/list.js')}}"></script>
@endpush


