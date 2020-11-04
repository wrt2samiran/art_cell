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
                          <td >{{$task_list->task_title}}</td>
                        </tr>
                        <tr>
                          <td >Task Details</td>
                          <td >{!!$task_list->task_desc!!}</td>
                        </tr>
                        <tr>
                          <td >Property Name</td>
                          <td >{{$task_list->property->property_name}}</td>
                        </tr>
                        <tr>
                          <td >Service</td>
                          <td >{{$task_list->service->service_name}}</td>
                        </tr>
                        <tr>
                          <td >Service Type</td>
                          <td >{{$task_list->contract_services->service_type}}</td>
                        </tr>
                        <tr>
                          <td >Country</td>
                          <td >{{$task_list->country->name}}</td>
                        </tr>
                        <tr>
                          <td >State</td>
                          <td >{{$task_list->state->name}}</td>
                        </tr>
                        <tr>
                          <td >City</td>
                          <td >{{$task_list->city->name}}</td>
                        </tr>
                        <tr>
                          <td >Task Start Date</td>
                          <td >{{$task_list->start_date}}</td>
                        </tr>
                        <tr>
                          <td >Task End Date</td>
                          <td >{{$task_list->end_date}}</td>
                        </tr>
                       
                        <tr>
                          <td>Status</td>
                          <td>
                            @php
                              if($task_list->status=='0'){
                                  $button = 'warning';
                                  $status = 'Pending';
                                }
                                
                              else if($task_list->status=='1'){
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
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.task_management.labourTaskList', $task_list->id)}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

