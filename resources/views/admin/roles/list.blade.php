@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('group_manage_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.user_groups')}}</li>
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
				                    <div><span>{{__('group_manage_module.group_list')}}</span></div>
					                <div>
                                        @if(auth()->guard('admin')->user()->hasAllPermission(['group-create']))
						                <a class="btn btn-success" href="{{route('admin.roles.create')}}">
						                 {{__('general_sentence.button_and_links.create_group')}}
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
                                        <div class="col-sm-4" id="user_type-filter-container">
                                            <select class="form-control user_type-filter"  name="user_type_id" id="user_type_id">
                                                <option value="">{{__('group_manage_module.placeholders.filter_by_user_type')}}</option>
                                                @forelse($user_types as $user_type)
                                                   <option value="{{$user_type->id}}">{{$user_type->name}}</option>
                                                @empty
                                                <option value="">No User Type Found</option>
                                                @endforelse
                                           </select>
                                           <a href="javascript::void(0)" title="Click to clear filter" style="display: none;" id="user_type-filter-clear"><span class="badge badge-danger">{{__('group_manage_module.clear_filter')}} <i class="fas fa-times"></i></span></a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-3 mb-3"/>
                                <table class="table table-bordered" id="roles_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>{{__('group_manage_module.labels.group_name')}}</th>
                                            <th>{{__('group_manage_module.labels.user_type')}}</th>
                                            <th>{{__('group_manage_module.labels.created_by')}}</th>
                                            <th>{{__('group_manage_module.labels.status')}}</th>
                                            <th>{{__('group_manage_module.labels.created_at')}}</th>
                                            <th>{{__('group_manage_module.labels.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- calling this url from datatable data which is inside public\js\admin\roles\list.js-->
                                <input type="hidden" id="roles_data_url" value="{{route('admin.roles.list')}}">
                            </div>
                        </div>
                    </div>
                </div>
  
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')

<script type="text/javascript" src="{{asset('js/admin/roles/list.js')}}"></script>
@endpush


