@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('contract_manage_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.contracts')}}</li>
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
				                    <div><span>{{__('contract_manage_module.contract_list')}}</span></div>

					                <div>
                                        @if(auth()->guard('admin')->user()->hasAllPermission(['contract-create']))
						                <a class="btn btn-success" href="{{route('admin.contracts.create')}}">
						                 {{__('general_sentence.button_and_links.create_contract')}}
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
                                        <div class="col-md-4" id="status-filter-container1">
                                            <select class="form-control status-filter"  name="contract_status_id" id="contract_status_id">
                                                <option value="">{{__('contract_manage_module.placeholders.filter_by_status')}}</option>
                                                @forelse($ContractStatus as $status)
                                                   <option value="{{$status->id}}">{{$status->status_name}}</option>
                                                @empty
                                                <option value="">No Service Found</option>
                                                @endforelse
                                           </select>
                                           <div class="cursor-poiner" title="Click to clear filter" style="display: none;" id="status-filter-clear"><span class="badge badge-danger">{{__('contract_manage_module.clear_filter')}}<i class="fas fa-times"></i></span></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input class="form-control" type="text" name="contract_duration" id="contract_duration" placeholder="Search By Date">
                                                

                                                    <input type="hidden" name="daterange" id="daterange" placeholder="{{__('contract_manage_module.placeholders.search_by_date')}}">

                                                </div>
                                                <div class="cursor-poiner"  style="display: none;" id="contract_duration-filter-clear"><span class="badge badge-danger">{{__('contract_manage_module.clear_filter')}}<i class="fas fa-times"></i></span></div>
                                                                        
                                            </div>
                                        </div>
                                    </div>

                                </div>















                                <hr class="mt-3 mb-3"/>
                                <table class="table table-bordered" id="contract_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>{{__('contract_manage_module.labels.contract_code')}}</th>
                                            <th>{{__('contract_manage_module.labels.contract_title')}}</th>
                                            <th>{{__('contract_manage_module.labels.start_date')}}</th>
                                            <th>{{__('contract_manage_module.labels.end_date')}}</th>
                                            <th>{{__('contract_manage_module.labels.service_provider')}}</th>
                                            <th>{{__('contract_manage_module.labels.creation_complete')}}</th>
                                            <th>{{__('contract_manage_module.labels.status')}}</th>
                                            <th>{{__('contract_manage_module.labels.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- calling this url from datatable data which is inside public\js\admin\contracts\list.js-->
                                <input type="hidden" id="contracts_data_url" value="{{route('admin.contracts.list')}}">
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/contracts/list.js')}}"></script>
@endpush


