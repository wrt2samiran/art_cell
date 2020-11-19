@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
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
                  <li class="breadcrumb-item active">Task</li>
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
                                    <div><span>Task List</span></div>
                                  <div>
                                    <a class="btn btn-success" href="{{route('admin.work-order-management.taskCreate')}}">
                                     Add Task
                                    </a>
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
                                <table class="table table-bordered" id="labour_task_management_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <!-- <th>Property Name</th> -->
                                            <th>Service</th>
                                            <!-- <th>Country</th>
                                            <th>State</th>
                                            <th>City</th> -->
                                            <th>Task Date</th>
                                            <th>Status</th>
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

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
                                              
                                              <form  method="post" id="admin_labour_task_feedback_form" action="{{route('admin.work-order-management.taskFeedback')}}" method="post" enctype="multipart/form-data">
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

<script type="text/javascript" src="{{asset('js/admin/work_order_management/daily-task-list.js')}}"></script>
@endpush


