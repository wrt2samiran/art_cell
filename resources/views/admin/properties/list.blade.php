@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('property_manage_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.properties')}}</li>
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
				                    <div><span>{{__('property_manage_module.property_list')}}</span></div>
					                <div>
                                        @if(auth()->guard('admin')->user()->hasAllPermission(['property-create']))
						                <a class="btn btn-success" href="{{route('admin.properties.create')}}">
						                 {{__('general_sentence.button_and_links.create_property')}}
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
                                        <div class="col-sm-4" id="city-name">
                                            <select class="form-control city-name"  name="city_id" id="city_id">
                                                <option value="">{{__('property_manage_module.placeholders.filter_by_city')}}</option>
                                                @forelse($propertyCity as $status)
                                                   <option value="{{$status->id}}">{{$status->name}}</option>
                                                @empty
                                                <option value="">No City Found</option>
                                                @endforelse
                                           </select>
                                           <div class="cursor-poiner"  style="display: none;" id="city-name-clear"><span class="badge badge-danger">{{__('property_manage_module.clear_filter')}}<i class="fas fa-times"></i></span></div>
                                        </div>


                                </div>
                                <hr class="mt-3 mb-3"/>
                                <table class="table table-bordered" id="property_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>{{__('property_manage_module.labels.property_code')}}</th>
                                            <th>{{__('property_manage_module.labels.property_name')}}</th>
                                            <th>{{__('property_manage_module.labels.city')}}</th>
                                            <th>{{__('property_manage_module.labels.status')}}</th>
                                            <th>{{__('property_manage_module.labels.created_at')}}</th>
                                            <th>{{__('property_manage_module.labels.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- calling this url from datatable data which is inside public\js\admin\properties\list.js-->
                                <input type="hidden" id="properties_data_url" value="{{route('admin.properties.list')}}">
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/properties/list.js')}}"></script>
@endpush


