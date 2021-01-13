@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('emergency_service_manage_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  
                  <li class="breadcrumb-item active">{{__('emergency_service_manage_module.details')}}</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
              @if(Session::has('success-message'))
                  <div class="alert alert-success alert-dismissable">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('success-message') }}
                  </div>
              @endif
              @if(Session::has('error'))
                  <div class="alert alert-danger alert-dismissable">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('error') }}
                  </div>
              @endif

              <table class="table table-bordered table-hover" id="country-details-table">
                <tbody>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.contract_id')}}</td>
                          <td >{{$work_order_list->contract->code}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.title')}}</td>
                          <td >{{$work_order_list->task_title}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.details')}}</td>
                          <td >{!!$work_order_list->task_desc!!}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.property_name')}}</td>
                          <td >{{$work_order_list->property->property_name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.service')}}</td>
                          <td >{{$work_order_list->service->service_name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.service_type')}}</td>
                          <td >{{$work_order_list->contract_services->service_type}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.country')}}</td>
                          <td >{{$work_order_list->property->country->name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.state')}}</td>
                          <td >{{$work_order_list->property->state->name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.city_name')}}</td>
                          <td >{{$work_order_list->property->city->name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.date')}} </td>
                          <td >{{$work_order_list->start_date}}</td>
                        </tr>
                        <tr>
                          <td >{{__('emergency_service_manage_module.labels.service_provide')}}</td>
                          <td >{{$work_order_list->userDetails->name}}</td>
                        </tr>
                      
                        <tr>
                          <td>{{__('emergency_service_manage_module.labels.completed')}}</td> 
                          <td ><div class="progress"><div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="{{$work_order_list->work_order_complete_percent}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$work_order_list->work_order_complete_percent}}%">{{$work_order_list->work_order_complete_percent}}% </div></div></td>
                        </tr>

                        
                       
                        <tr>
                          <td>{{__('emergency_service_manage_module.labels.status')}}</td>
                          <td>
                            @php
                              if($work_order_list->status=='0'){
                                  $button = 'warning';
                                  $status = 'Pending';
                                }
                                
                              else if($work_order_list->status=='1'){
                                  $button = 'danger';
                                  $status = 'Over Due';

                                }
                                
                              else{

                                  $button = 'success';
                                  $status = 'Completed';
                                }
                                
                              @endphp  

                            <button role="button" class="btn btn-{{$button}}">{{$status}}</button>
                            
                          </td>
                        </tr>
                  
                  
                </tbody>
              </table>    
             

              <div>
                 <a href="{{route('admin.emergency-service-management.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a>
              </div>
            
            </div>
        </section>  
    </div>


              

              <!-- Labour Feedback END-->  

@endsection

@push('custom-scripts')


<script type="text/javascript" src="{{asset('js/admin/emergency_service_management/show.js')}}"></script>
@endpush


