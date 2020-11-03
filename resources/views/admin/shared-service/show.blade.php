@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Shared Service Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.shared-service.list')}}">Shared Service</a></li>
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
                  Shared Service Deatils
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover" id="country-details-table">
                      <tbody>
                        <tr>
                          <td>Shared Service Name</td>
                          <td>{{$sharedServices->name}}</td>
                        </tr>
                        <tr>
                          <td>Description</td>
                          <td>{!! $sharedServices->description !!}</td>
                        </tr>
                        <tr>
                          <td>Number of Days</td>
                          <td >{{$sharedServices->number_of_days}}</td>
                        </tr>
                        <tr>
                          <td>Price</td>
                          <td >{{$sharedServices->currency}} {{$sharedServices->price}}</td>
                        </tr>
                        <tr>
                          <td>Extra Price/Day</td>
                          <td >{{$sharedServices->currency}} {{$sharedServices->extra_price_per_day}}</td>
                        </tr>
                        <tr>
                          <td>Quantity Available</td>
                          <td >{{$sharedServices->quantity_available}}</td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($sharedServices->is_active=='1')?'success':'danger'}}">{{($sharedServices->is_active=='1')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>
                       
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.shared-service.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

