@extends('admin.layouts.after-login-layout')
@section('unique-content')

<div class="content-wrapper">
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
              <li class="breadcrumb-item"><a href="{{route('admin.contracts.list')}}">{{__('general_sentence.breadcrumbs.contracts')}}</a></li>
              <li class="breadcrumb-item active">
                {{($contract->creation_complete)?__('general_sentence.breadcrumbs.edit'):__('general_sentence.breadcrumbs.create')}}
              </li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <!-- SELECT2 EXAMPLE -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  @if($contract->creation_complete)
                  {{__('contract_manage_module.edit_contract')}}
                  @else
                  {{__('contract_manage_module.create_contract')}}
                  @endif
                </h3>
              </div>
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
                  <input type="hidden" name="contract_start_date" id="contract_start_date" value="{{$contract->start_date}}">
                  <input type="hidden" name="contract_end_date" id="contract_end_date" value="{{$contract->end_date}}">

                  <div class="row justify-content-center">
                    @include('admin.contracts.partials.multi_step_links')
                    <div class="col-md-11 col-sm-12">
                      <div id="accordion">
                        <div class="card">
                          <div  id="headingOne">
                              <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                {{__('contract_manage_module.view_added_services')}}
                              </button>
                          </div>

                          <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body" style="padding: 10px 3px;">
                                <table class="table table-bordered" id="contract_services_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>{{__('contract_manage_module.labels.service')}}</th>
                                            <th>{{__('contract_manage_module.labels.service_type')}}</th>
                                            <th>{{__('contract_manage_module.labels.service_price')}} ({{Helper::getSiteCurrency()}})</th>
                                            <th>{{__('contract_manage_module.labels.status')}}</th>
                                            <th>{{__('contract_manage_module.labels.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <input type="hidden" value="{{route('admin.contracts.services',$contract->id)}}" id="contract_services_data_url">
                            </div>
                          </div>
                        </div>
                      </div>
                      @if($contract_service)
                        @include('admin.contracts.partials.service_edit_form')
                      @else
                        @include('admin.contracts.partials.service_add_form')
                      @endif
                      
                    </div>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </section>
</div>
@include('admin.contracts.modals.contract_service_details_modal')
@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/contracts/services.js')}}"></script>

@endpush
