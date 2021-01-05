@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('shared_service_manage_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.shared_services')}}</li>
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
				                    <div><span>{{__('shared_service_manage_module.shared_service_list')}}</span></div>
					                <div>
						                <a class="btn btn-success" href="{{route('admin.shared_services.create')}}">
						                 {{__('general_sentence.button_and_links.create_shared_service')}}
						                </a>
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
                                <table class="table table-bordered" id="shared_service_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>{{__('shared_service_manage_module.labels.shared_service_name')}}</th>
                                            <th>{{__('shared_service_manage_module.labels.selling_price')}}</th>
                                            <th>{{__('shared_service_manage_module.labels.sharing_price')}}</th>
                                            <th>{{__('shared_service_manage_module.labels.status')}}</th>
                                            <th>{{__('shared_service_manage_module.labels.created_at')}}</th>
                                            <th>{{__('shared_service_manage_module.labels.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <input type="hidden" id="shared_services_data_url" value="{{route('admin.shared_services.list')}}">
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


