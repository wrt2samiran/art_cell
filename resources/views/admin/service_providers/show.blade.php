@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Service Provider Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.service_providers.list')}}">Service Providers</a></li>
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
                  Service Provider Details
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="service-provider-details-table">
                      <tbody>
                        <tr>
                          <td>First Name</td>
                          <td >{{$service_provider->first_name}}</td>
                        </tr>
                        <tr>
                          <td >Last Name</td>
                          <td >{{$service_provider->last_name}}</td>
                        </tr>
                        <tr>
                          <td >Email</td>
                          <td >{{$service_provider->email}}</td>
                        </tr>
                        <tr>
                          <td >Phone/Contact Number</td>
                          <td >{{$service_provider->phone}}</td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($service_provider->status=='A')?'success':'danger'}}">{{($service_provider->status=='A')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>

                        <tr>
                          <td>Created At</td>
                          <td>{{$service_provider->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.service_providers.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

