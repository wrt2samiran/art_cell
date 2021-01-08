@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('spare_part_manage_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.spare_parts')}}</li>
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
				                    <div><span>{{__('spare_part_manage_module.spare_part_list')}}</span></div>
					                <div>
						                <a class="btn btn-success" href="{{route('admin.spare_parts.create')}}">
						                 {{__('general_sentence.button_and_links.create_spare_part')}}
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
                                <table class="table table-bordered" id="spare_parts_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>{{__('spare_part_manage_module.labels.spare_part_name')}}</th>
                                            <th>{{__('spare_part_manage_module.labels.manufacturer')}}</th>
                                            <th>{{__('spare_part_manage_module.labels.unit')}}</th>
                                           
                                            <th>{{__('spare_part_manage_module.labels.price')}}</th>
                                            
                                            <th>{{__('spare_part_manage_module.labels.status')}}</th>
                                            <th>{{__('spare_part_manage_module.labels.created_at')}}</th>
                                            <th>{{__('spare_part_manage_module.labels.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <input type="hidden" id="spare_parts_data_url" value="{{route('admin.spare_parts.list')}}">
                            </div>
                        </div>
                    </div>
                </div>
  
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/spare_parts/list.js')}}"></script>
@endpush


