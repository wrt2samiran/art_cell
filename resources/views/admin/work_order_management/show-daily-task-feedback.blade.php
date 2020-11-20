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
              <li class="breadcrumb-item"><a href="{{route('admin.task_management.list')}}">Task Management</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.task_management.labourTaskList', $task_data->task_id)}}">Labour Task List</a></li>
              <li class="breadcrumb-item active">Daily Task Details</li>
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
                  Daily Task Deatils
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover" id="country-details-table">
                      <tbody>
                        <tr>
                          <td >Task Title</td>
                          <td >{{$task_data->task->task_title}}</td>
                        </tr>
                        @if( \Auth::guard('admin')->user()->id!=$task_data->user_id)
                        <tr>
                          <td>Labour Name</td>
                          <td >{{$task_data->userDetails->name}}</td>
                        </tr>
                        @endif
                        <tr>
                          <td>Daily Task Details</td>
                          <td >{!!$task_data->task_description!!}</td>
                        </tr>
                        <tr>
                          <td>Labour Feedback</td>
                          <td >{{$task_data->user_feedback}}</td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            @php
                              if($task_data->status=='0'){
                                  $button = 'warning';
                                  $status = 'Pending';
                                }
                                
                              else if($task_data->status=='1'){
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
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.task_management.labourTaskList', $task_data->task_id)}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

