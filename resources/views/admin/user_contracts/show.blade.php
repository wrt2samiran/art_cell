@extends('admin.layouts.after-login-layout')


@section('unique-content')
@php $current_user=auth()->guard('admin')->user(); @endphp
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Contract Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.contracts.list')}}">Contracts</a></li>
              <li class="breadcrumb-item active">Details</li>
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
                  Contract Details
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="contract-details-table">
                      <tbody>
                        <tr>
                          <td>Contract Code</td>
                          <td >{{$contract->code}}</td>
                        </tr>
                        <tr>
                          <td>Contract Title</td>
                          <td >{{$contract->title}}</td>
                        </tr>
                        <tr>
                          <td>Contract Description</td>
                          <td >{!!$contract->description!!}</td>
                        </tr>
                        <tr>
                          <td>Services Required</td>
                          <td>
                            @if(count($contract->services))
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>Service Name</th>
                                  <th>Service Type</th>
                                  <th>Recurrence Details</th>
                                  <th>Service Price</th>
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
                                      <span>From {{Carbon::parse($service->recurrence_details->start_date)->format('d/m/Y')}}</span> To 
                                      <span>
                                      @if($service->recurrence_details->end_by_or_after=='end_by')
                                        {{Carbon::parse($service->recurrence_details->end_date)->format('d/m/Y')}}
                                      @else
                                        after {{$service->recurrence_details->no_of_occurrences}} occurrences
                                      @endif
                                      </span>

                                    </div>
                                    <div>
                                      {{Carbon::parse($service->recurrence_details->start_time)->format('g:i A')}}-{{Carbon::parse($service->recurrence_details->end_time)->format('g:i A')}}
                                    </div>

                                    <div>
                                      
                                    @if($service->recurrence_details->interval_type=='yearly')
                                    <div>Recurre every {{$service->recurrence_details->reccure_every}} year(s)</div>
                                    <div>
                                      @if($service->recurrence_details->on_or_on_the=='on')
                                      On <span>{{$service->recurrence_details->day_number}} {{$service->recurrence_details->month_name}}</span>
                                      @else
                                      On the <span>{{$service->recurrence_details->ordinal}}, {{$service->recurrence_details->week_day_name}}, {{$service->recurrence_details->month_name}}</span>
                                      @endif
                                    </div>
                                    @elseif($service->recurrence_details->interval_type=='monthly')
                                    <div>Recurre every {{$service->recurrence_details->reccure_every}} month(s)</div>
                                    <div>
                                      @if($service->recurrence_details->on_or_on_the=='on')
                                      On <span>{{$service->recurrence_details->day_number}} day 
                                      @else
                                      On the <span>{{$service->recurrence_details->ordinal}}, {{$service->recurrence_details->week_day_name}}</span>
                                      @endif
                                      Of every {{$service->recurrence_details->reccure_every}} month(s)
                                    </div>

                                    @elseif($service->recurrence_details->interval_type=='weekly')
                                    <div>Recurre every {{$service->recurrence_details->reccure_every}} week(s)</div>
                                    <div>({{$service->recurrence_details->weekly_days}})</div>
                                    @else
                                    <div>Recurre every {{$service->recurrence_details->reccure_every}} day(s)</div>
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
                          <td>Start Date</td>
                          <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $contract->start_date)->format('d/m/Y')}}</td>
                        </tr>
                        <tr>
                          <td>End Date</td>
                          <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $contract->end_date)->format('d/m/Y')}}</td>
                        </tr>

    
                        <tr>
                          <td>Property</td>
                          <td >{{$contract->property->property_name}}</td>
                        </tr>
                        <tr>
                          <td>Property Type</td>
                          <td >{{$contract->property->property_type->type_name}}</td>
                        </tr>
                        <tr>
                          <td>Location/Landmark</td>
                          <td >{{$contract->property->location}}</td>
                        </tr>



                        @if(in_array($current_user->role->user_type->slug,['super-admin','property-owner']) && $contract->in_installment)

                          <tr>
                          <td>Contract Price</td>
                          <td >{{$contract->contract_price_currency}}{{number_format($contract->contract_price, 2, '.', '')}}</td>
                          </tr>

                          @if(count($contract->contract_installments))
                          <tr>
                            <td>Installments</td>
                            <td>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Payment Amount</th>
                                    <th>Due Date</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach($contract->contract_installments as $installment)
                                  <tr>
                                    <td>{{$installment->currency}} {{number_format($installment->price, 2, '.', '')}}</td>
                                    <td>
                                      {{Carbon::parse($installment->due_date)->format('d/m/Y')}}</td>
                                  </tr>
                                  @endforeach
                                </tbody>
                              </table>
                            </td>
                          </tr>
                          @endif
                        @endif

                        <tr>
                          <td>Status</td>
                          <td>
                            @if($status=$contract->contract_status)
                              <span style="color: {{$status->color_code}}">{{$status->status_name}}</span>
                            @else
                              <span>Status not found</span>
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td>Downloadable Files</td>
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
                              <div class="col-md-12">No files</div>
                              @endif
                            </div>
                          </td>
                        </tr>

                        <tr>
                          <td>Created At</td>
                          <td>{{$contract->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.user_contracts.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

