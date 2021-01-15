@extends('admin.layouts.after-login-layout')


@section('unique-content')
@php $current_user=auth()->guard('admin')->user(); @endphp
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
              <li class="breadcrumb-item"><a href="{{route('admin.user_contracts.list')}}">{{__('general_sentence.breadcrumbs.contracts')}}</a></li>
              <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.details')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card card-success">
                <div class="card-header">
                  {{__('contract_manage_module.contract_details')}}
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
                 <table class="table table-bordered table-hover record-details-table" id="contract-details-table">
                      <tbody>
                        <tr>
                          <td>{{__('contract_manage_module.labels.contract_code')}}</td>
                          <td >{{$contract->code}}</td>
                        </tr>
                        <tr>
                          <td>{{__('contract_manage_module.labels.contract_title')}}</td>
                          <td >{{$contract->title}}</td>
                        </tr>
                        <tr>
                          <td>{{__('contract_manage_module.labels.description')}}</td>
                          <td >{!!$contract->description!!}</td>
                        </tr>
                        <tr>
                          <td>{{__('contract_manage_module.services_required')}}</td>
                          <td>
                            @if(count($contract->services))
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>{{__('contract_manage_module.labels.service')}}</th>
                                  <th>{{__('contract_manage_module.labels.service_type')}}</th>
                                  <th>{{__('contract_manage_module.recurrence_details')}}</th>
                                  <th>{{__('contract_manage_module.labels.service_price')}}</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($contract->services as $service)
                                <tr>
                                  <td>{{$service->service->service_name}}</td>
                                  <td>
                                    {{$service->service_type}}

                                    @if($service->service_type=='On Demand')
                                    <br>
                                    {{($service->number_of_time_can_used)? $service->number_of_time_can_used.' times':'' }}
                                    @endif
                                  </td>
                                  <td>
                                    @if($service->service_type=='Maintenance')
                                    <div>
                                      <span>{{__('contract_manage_module.from')}} {{Carbon::parse($service->recurrence_details->start_date)->format('d/m/Y')}}</span> {{__('contract_manage_module.to')}} 
                                      <span>
                                      @if($service->recurrence_details->end_by_or_after=='end_by')
                                        {{Carbon::parse($service->recurrence_details->end_date)->format('d/m/Y')}}
                                      @else
                                        {{__('contract_manage_module.after')}} {{$service->recurrence_details->no_of_occurrences}} {{__('contract_manage_module.occurences')}}
                                      @endif
                                      </span>

                                    </div>
                                    <div>
                                      {{Carbon::parse($service->recurrence_details->start_time)->format('g:i A')}}-{{Carbon::parse($service->recurrence_details->end_time)->format('g:i A')}}
                                    </div>

                                    <div>
                                      
                                    @if($service->recurrence_details->interval_type=='yearly')
                                    <div>{{__('contract_manage_module.reccur_every')}} {{$service->recurrence_details->reccure_every}} {{__('general_sentence.years')}}</div>
                                    <div>
                                      @if($service->recurrence_details->on_or_on_the=='on')
                                      {{__('contract_manage_module.on')}} <span>{{$service->recurrence_details->day_number}} {{$service->recurrence_details->month_name}}</span>
                                      @else
                                      {{__('contract_manage_module.on_the')}} <span>{{$service->recurrence_details->ordinal}}, {{$service->recurrence_details->week_day_name}}, {{$service->recurrence_details->month_name}}</span>
                                      @endif
                                    </div>
                                    @elseif($service->recurrence_details->interval_type=='monthly')
                                    <div>{{__('contract_manage_module.reccur_every')}} {{$service->recurrence_details->reccure_every}} {{__('general_sentence.months')}}</div>
                                    <div>
                                      @if($service->recurrence_details->on_or_on_the=='on')
                                      {{__('contract_manage_module.on')}} <span>{{$service->recurrence_details->day_number}} {{__('general_sentence.day')}} 
                                      @else
                                      {{__('contract_manage_module.on_the')}} <span>{{$service->recurrence_details->ordinal}}, {{$service->recurrence_details->week_day_name}}</span>
                                      @endif
                                      {{__('contract_manage_module.of_every')}} {{$service->recurrence_details->reccure_every}} {{__('general_sentence.months')}}
                                    </div>

                                    @elseif($service->recurrence_details->interval_type=='weekly')
                                    <div>{{__('contract_manage_module.reccur_every')}} {{$service->recurrence_details->reccure_every}} {{__('general_sentence.weeks')}}</div>
                                    <div>({{$service->recurrence_details->weekly_days}})</div>
                                    @else
                                    <div>{{__('contract_manage_module.reccur_every')}} {{$service->recurrence_details->reccure_every}} {{__('general_sentence.days')}}</div>
                                    @endif

                                    </div>


                                    @else
                                    Not Available
                                    @endif

                                  </td>
                                  <td>{{$service->currency}} {{number_format($service->price, 2, '.', '')}}</td>
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                            @else
                            <span>No services</span>
                            @endif

                          </td>
                        </tr>

                        <tr>
                          <td>{{__('contract_manage_module.labels.start_date')}}</td>
                          <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $contract->start_date)->format('d/m/Y')}}</td>
                        </tr>
                        <tr>
                          <td>{{__('contract_manage_module.labels.end_date')}}</td>
                          <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $contract->end_date)->format('d/m/Y')}}</td>
                        </tr>

    
                        <tr>
                          <td>{{__('contract_manage_module.labels.property')}}</td>
                          <td >{{$contract->property->property_name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('contract_manage_module.labels.property_type')}}</td>
                          <td >{{$contract->property->property_type->type_name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('contract_manage_module.labels.location')}}</td>
                          <td >{{$contract->property->location}}</td>
                        </tr>
                        @if(in_array($current_user->role->user_type->slug,['super-admin','property-owner']))
                          <tr>
                          <td>{{__('contract_manage_module.labels.contract_price')}}</td>
                          <td >
                            {{$contract->contract_price_currency}}{{number_format($contract->contract_price, 2, '.', '')}}

                            @if(!$contract->in_installment)

                              @if($contract->is_paid)
                              <span class="text-success">{{__('contract_manage_module.paid_on')}} : {{Carbon::parse($contract->paid_on)->format('d/m/Y')}}</span>
                              @else
                                @if($current_user->role->user_type->slug=='property-owner')
                                  <a href="{{route('admin.pay_contract_amount',$contract->id)}}" class="btn btn-success">Pay</a>
                                @else
                                  ({{__('contract_manage_module.not_paid')}})
                                @endif
                              
                              @endif

                            @endif

                          </td>
                          </tr>
                        @endif


                        @if(in_array($current_user->role->user_type->slug,['super-admin','property-owner']) && $contract->in_installment)

                          <tr>
                            <td>Installments</td>
                            <td>
                              <table class="table table-bordered">
                                <thead>
                                  <th>{{__('contract_manage_module.labels.amount')}}</th>
                                  <th>{{__('contract_manage_module.labels.due_date')}}</th>
                                  <th>{{__('contract_manage_module.labels.payment')}}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($contract->contract_installments as $installment)
                                  <tr>
                                    <td>{{$installment->currency}} {{number_format($installment->price, 2, '.', '')}}</td>
                                    <td>
                                      {{Carbon::parse($installment->due_date)->format('d/m/Y')}}
                                    </td>
                                    <td>

                                      @if($installment->is_paid)
                                      <span class="text-success">{{__('contract_manage_module.paid_on')}} : {{Carbon::parse($installment->paid_on)->format('d/m/Y')}}</span>
                                      @else
                                        @if($current_user->role->user_type->slug=='property-owner')
                                          <a href="{{route('admin.pay_contract_installment',$installment->id)}}" class="btn btn-success">Pay</a>
                                        @else
                                          {{__('contract_manage_module.not_paid')}}
                                        @endif
                                      
                                      @endif

                                    </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        
                        @endif

                        <tr>
                          <td>{{__('contract_manage_module.labels.status')}}</td>
                          <td>
                            @if($status=$contract->contract_status)
                              <span style="color: {{$status->color_code}}">{{$status->status_name}}</span>
                            @else
                              <span>Status not found</span>
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td>{{__('contract_manage_module.downloadable_files')}}</td>
                          <td>
                            <div class="row">
                              @if(count($files=$contract->contract_attachments))
                                @foreach($files as $file)
                                  @php
                                    if($file->file_type=='pdf'){
                                      $font_icon='far fa-file-pdf';
                                      $color='red';
                                    }elseif($file->file_type=='doc'){
                                      $font_icon='far fa-file-word';
                                      $color='blue';
                                    }elseif($file->file_type=='text'){
                                      $font_icon='far fa-file-alt';
                                      $color='white';
                                    }elseif($file->file_type=='image'){
                                      $font_icon='far fa-file-image';
                                      $color='grey';
                                    }else{
                                       $font_icon='fas fa-file';
                                      $color='grey';
                                    }
                                  @endphp

                                  <div style="height: 55px" class="col-sm-1 col-xs-1">
                                    <a title="{{$file->title}}" href="{{route('admin.contracts.download_attachment',$file->id)}}"><i style="color: {{$color}};" class="fa-4x {{$font_icon}}"></i></a>
                                  </div>
                                @endforeach
                              @else
                              <div class="col-md-12">{{__('contract_manage_module.no_files')}}</div>
                              @endif
                            </div>
                          </td>
                        </tr>

                        <tr>
                          <td>{{__('contract_manage_module.labels.created_at')}}</td>
                          <td>{{$contract->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.user_contracts.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a></td>
                        </tr>
                      </tfoot>
                  </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
</div>
@endsection 

