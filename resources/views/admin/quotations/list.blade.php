@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('quotation_manage_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.quotations')}}</li>
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
				                    <div><span>{{__('quotation_manage_module.quotation_list')}}</span></div>
			
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
                                        <div class="col-sm-4" id="service-filter-container">
                                            <select class="form-control service-filter"  name="service" id="service">
                                                <option value="">{{__('quotation_manage_module.placeholders.filter_by_service')}}</option>
                                                @forelse($services as $service)
                                                   <option value="{{$service->id}}">{{$service->service_name}}</option>
                                                @empty
                                                <option value="">No Service Found</option>
                                                @endforelse
                                           </select>
                                           <div class="cursor-poiner" title="Click to clear filter" style="display: none;" id="service-filter-clear"><span class="badge badge-danger">{{__('quotation_manage_module.clear_filter')}} <i class="fas fa-times"></i></span></div>
                                        </div>
                                        <div class="col-sm-4" id="property-type-filter-container">
                                            <select class="form-control property-type-filter"  name="property_type" id="property_type">
                                                <option value="">{{__('quotation_manage_module.placeholders.filter_by_property_type')}}</option>
                                                @forelse($property_types as $property_type)
                                                   <option value="{{$property_type->id}}">{{$property_type->type_name}}</option>
                                                @empty
                                                <option value="">No Property Type Found</option>
                                                @endforelse
                                            </select>
                                            <div class="cursor-poiner" title="Click to clear filter" style="display: none;" id="property-type-filter-clear"><span class="badge badge-danger">{{__('quotation_manage_module.clear_filter')}} <i class="fas fa-times"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-3 mb-3"/>
                                <table class="table table-bordered" id="quotations_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>{{__('quotation_manage_module.labels.name')}}</th>
                                            <th>{{__('quotation_manage_module.labels.email')}}</th>
                                            <th>{{__('quotation_manage_module.labels.service_required')}}</th>
                                            <th>{{__('quotation_manage_module.labels.property_type')}}</th>
                                            <th>{{__('quotation_manage_module.labels.duration')}}</th>
                                            <th>{{__('quotation_manage_module.labels.status')}}</th>
                                            <th>{{__('quotation_manage_module.labels.created_at')}}</th>
                                            <th>{{__('quotation_manage_module.labels.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- calling this url from datatable data which is inside public\js\admin\quotations\list.js-->
                                <input type="hidden" id="quotations_data_url" value="{{route('admin.quotations.list')}}">
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>
@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/quotations/list.js')}}"></script>
@endpush


