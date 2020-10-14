@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Property Owner Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.property_owners.list')}}">Property Owners</a></li>
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
                  Property Owner Details
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="service-provider-details-table">
                      <tbody>
                        <tr>
                          <td>First Name</td>
                          <td >{{$property_owner->first_name}}</td>
                        </tr>
                        <tr>
                          <td >Last Name</td>
                          <td >{{$property_owner->last_name}}</td>
                        </tr>
                        <tr>
                          <td >Email</td>
                          <td >{{$property_owner->email}}</td>
                        </tr>
                        <tr>
                          <td >Phone/Contact Number</td>
                          <td >{{$property_owner->phone}}</td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($property_owner->status=='A')?'success':'danger'}}">{{($property_owner->status=='A')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>

                        <tr>
                          <td>Created At</td>
                          <td>{{$property_owner->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.property_owners.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

