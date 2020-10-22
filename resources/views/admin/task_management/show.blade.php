@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Task Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.task-management.list')}}">Task</a></li>
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
                  Task Deatils
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover" id="country-details-table">
                      <tbody>
                        
                        <tr>
                          <td >Task Title</td>
                          <td >{{$city->country->name}}</td>
                        </tr>
                        <tr>
                          <td >Property Name</td>
                          <td >{{$city->country->name}}</td>
                        </tr>
                        <tr>
                          <td >Country</td>
                          <td >{{$city->country->name}}</td>
                        </tr>
                        <tr>
                          <td >State Name</td>
                          <td >{{$city->state->name}}</td>
                        </tr>
                        <tr>
                          <td>City Name</td>
                          <td >{{$city->name}}</td>
                        </tr>
                        <tr>
                          <td>Servise Tenure</td>
                          <td >{{$city->name}}</td>
                        </tr>
                        <tr>
                          <td>Assigned User</td>
                          <td >{{$city->name}}</td>
                        </tr>
                        <tr>
                          <td>User Reply</td>
                          <td >{{$city->name}}</td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($city->is_active=='1')?'success':'danger'}}">{{($city->is_active=='1')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>
                       
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.task-management.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

