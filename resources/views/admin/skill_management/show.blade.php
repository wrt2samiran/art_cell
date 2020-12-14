@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Service Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.service_management.list')}}">Service</a></li>
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
                  Service Deatils
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover" id="country-details-table">
                      <tbody>
                        <?php //dd($service_allocation_data);?>
                        <tr>
                          <td >Contract Code</td>
                          <td >{{$service_allocation_data->contract->code}}</td>
                        </tr>
                        <tr>
                          <td >Contract Info</td>
                          <td >{{$service_allocation_data->contract->description}}</td>
                        </tr>
                        <tr>
                          <td >Property Name</td>
                          <td >{{$service_allocation_data->property->property_name}}</td>
                        </tr>
                        <tr>
                          <td>Service Required</td>
                          <td >{{$service_allocation_data->service->service_name}}</td>
                        </tr>
                        <tr>
                          <td>Service Details</td>
                          <td >{!! $service_allocation_data->service_details!!}</td>
                        </tr>
                        <tr>
                          <td >Service Provider</td>
                          <td >{{$service_allocation_data->service_provider->name}}</td>
                        </tr>
                        <tr>
                          <td>Start Date</td> 
                          <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $service_allocation_data->service_start_date)->format('d/m/Y')}}</td>
                        </tr>

                        <tr>
                          <td>End Date</td>
                          <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $service_allocation_data->service_end_date)->format('d/m/Y')}}</td>
                        </tr>
                        
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($service_allocation_data->status=='A')?'success':'danger'}}">{{($service_allocation_data->status=='A')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>
                       
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.service_management.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

