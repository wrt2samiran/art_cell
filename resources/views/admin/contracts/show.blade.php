@extends('admin.layouts.after-login-layout')


@section('unique-content')

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
                                  <th>How Frequently</th>
                                  <th>Service Price</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($contract->services as $service)
                                <tr>
                                  <td>{{$service->service->service_name}}</td>
                                  <td>{{$service->service_type}}</td>
                                  <td>
                                    @if($service->frequency_type)
                                    {{$service->frequency_type->type}}
                                    @endif
                                    @if($service->number_of_time_can_used)
                                    <span>Can use {{$service->number_of_time_can_used}} times</span>
                                    @endif
                                  </td>
                                  <td>{{$service->currency}} {{$service->price}}</td>
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
                          <td>Customer</td>
                          <td>
                            @if($customer=$contract->customer()->withTrashed()->first())
                              @if($customer->deleted_at)
                                <span class="text-danger"><del>{{$customer->name}} </del>(user deleted)</span>
                              @else
                                <a target="_blank" href="{{route('admin.property_owners.show',$customer->id)}}">{{$customer->name}}</a>
                              @endif
                            @else
                            N/A
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td >Service Provider</td>
                          <td>
                            @if($service_provider=$contract->service_provider()->withTrashed()->first())
                              @if($service_provider->deleted_at)
                                <span class="text-danger"><del>{{$service_provider->name}} </del>(user deleted)</span>
                              @else
                                <a target="_blank" href="{{route('admin.users.show',$service_provider->id)}}">{{$service_provider->name}}</a>
                              @endif
                            @else
                            N/A
                            @endif
                          </td>
                        </tr>
                        <tr>
                          <td >Property Manager</td>
                          <td>
                            @if($property_manager=$contract->property_manager()->withTrashed()->first())
                              @if($property_manager->deleted_at)
                                <span class="text-danger"><del>{{$property_manager->name}} </del>(user deleted)</span>
                              @else
                                <a target="_blank" href="{{route('admin.users.show',$property_manager->id)}}">{{$property_manager->name}}</a>
                              @endif
                            @else
                            N/A
                            @endif
                          </td>
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
                        <tr>
                          <td>No. of Units</td>
                          <td >{{$contract->property->no_of_units}}</td>
                        </tr>

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
                                    <a title="Click to download" href="{{route('admin.contracts.download_attachment',$file->id)}}"><i style="color: {{$color}};" class="fa-4x {{$font_icon}}"></i></a>
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
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.contracts.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

