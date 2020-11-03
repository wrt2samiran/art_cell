@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Labour Task Management</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.task_management.list')}}">Task Management</a></li>
                  <li class="breadcrumb-item active">Labour Task List</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between" >
                            <div><span>Task Details</span></div>
                          <div>
                            <!-- <a class="btn btn-success" href="{{route('admin.service_management.addService')}}">
                             Add Service
                            </a> -->
                          </div>
                        </div>
                    </div>


                            <!-- /.card-header -->
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
                                @if(\Auth::guard('admin')->user()->role_id !=5)
                                <table class="table table-bordered table-hover" id="country-details-table">
                                      <tbody>
                                        <?php //dd($service_allocation_data);?>
                                        <tr>
                                          <td >Task Title</td>
                                          <td >{{$task_list_data->task_title}}</td>
                                        </tr>
                                        <tr>
                                          <td >Property Name</td>
                                          <td >{{$task_list_data->property->property_name}}</td>
                                        </tr>
                                        <tr>
                                          <td >Service</td>
                                          <td >{{$task_list_data->service->service_name}}</td>
                                        </tr>
                                        <tr>
                                          <td>Country</td>
                                          <td >{{$task_list_data->country->name}}</td>
                                        </tr>
                                        <tr>
                                          <td>State</td>
                                          <td >{{$task_list_data->state->name}}</td>
                                        </tr>
                                        <tr>
                                          <td>City</td>
                                          <td >{{$task_list_data->city->name}}</td>
                                        </tr>
                                        <tr>
                                          <td>Task Details</td>
                                          <td >{!! $task_list_data->task_desc!!}</td>
                                        </tr>
                                        <tr>
                                          <td >Service Provider</td>
                                          <td >{{$task_list_data->userDetails->name}}</td>
                                        </tr>
                                        <tr>
                                          <td>Start Date</td> 
                                          <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $task_list_data->start_date)->format('d-m-Y')}}</td>
                                        </tr>

                                        <tr>
                                          <td>End Date</td>
                                          <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $task_list_data->end_date)->format('d-m-Y')}}</td>
                                        </tr>
                                        
                                        <!-- <tr>
                                          <td>Status</td>
                                          <td>
                                            <button role="button" class="btn btn-{{($task_list_data->status=='A')?'success':'danger'}}">{{($task_list_data->status=='A')?'Active':'Inactive'}}</button>
                                          </td>
                                        </tr> -->
                                       
                                      </tbody>
                                      
                                  </table>
                              </br>
                                <div class="card-header">
                                    <div class="d-flex justify-content-between" >
                                      <div>
                                        <a class="btn btn-success" style="text-align: right;" href="{{route('admin.task_management.labourTaskCreate', $task_list_data->id)}}">
                                         Assign Labour Task
                                        </a>
                                      </div>
                                    </div>
                                </div>
                                @endif
                                <table class="table table-bordered" id="daily_task_management_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Task Date</th>
                                            <th>Labour Name</th>
                                            <th>User Feedback</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>

                                <!-- Modal -->
                                

                                <div class="modal fade" id="addTaskModal" role="dialog">
                                <div class="modal-dialog">
                                
                                  <!-- Modal content-->
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      
                                      <h4 class="modal-title">Add Feedback</h4>
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="card-body">
                                          <div class="row justify-content-center">
                                            <div class="col-md-10 col-sm-12">
                                              
                                              <form  method="post" id="admin_labour_task_feedback_form" action="{{route('admin.task_management.taskFeedback')}}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                      <div>  
                                                        <input type="hidden" name="task_details_id" id="task_details_id" />
                                                 
                                                        <div class="form-group">
                                                          <label for="service_id">Task Feedback </label>
                                                          <textarea class="form-control float-right" name="user_feedback" id="user_feedback">{{old('user_feedback')}}</textarea>
                                                           @if($errors->has('user_feedback'))
                                                            <span class="text-danger">{{$errors->first('user_feedback')}}</span>
                                                           @endif  
                                                        </div>
                                                        
                                                    <div>
                                                   <button type="submit" class="btn btn-success">Submit</button> 

                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                  </div>
                                  
                                </div>
                                </div>

                        </div>
                    </div>
                </div>
  
        </section>
        <!-- /.content -->
    </div>

@endsection



@push('custom-scripts')

<script type="text/javascript">
    function addFeedback(id)
    {
        $('#task_details_id').val(id);
        $('#addTaskModal').modal('show');
    }

    setTimeout(function() {
        $('.alert-dismissable').fadeOut('fast');
    }, 5000); 
</script>
<script type="text/javascript" src="{{asset('js/admin/task_management/daily-task-list.js')}}"></script>
@endpush


