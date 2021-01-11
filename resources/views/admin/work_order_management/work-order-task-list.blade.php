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
                  <li class="breadcrumb-item active">Task Management</li>
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

                              

                                <div class="filter-area ">
                                    <div class="row">
                                        <div class="col-md-4" id="status-filter-container1">
                                            <select id='status' name="status" class="form-control status-filter" style="width: 200px">
                                                  <option value="">--Filter By Status--</option>
                                                  <option value="0">Pending</option>
                                                  <option value="1">Overdue</option>
                                                  <option value="2">Completed</option>
                                                  <option value="3">Rescheduled</option>
                                              </select>

                                           
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input class="form-control" type="text" name="contract_duration" id="contract_duration" placeholder="Search By Date">

                                                    <input type="hidden" name="daterange" id="daterange" placeholder="Search By Date">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <table class="table table-bordered" id="labour_task_management_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Property</th>
                                            <th width="15%">Address</th>
                                            <th>Service</th>
                                            <th>Task Title</th>
                                            <th>Task Finish Date And Time (Scheduled)</th>
                                            <th>Slot</th>
                                            <!-- <th width="25%">Task Details</th> -->
                                            <th>Feedback Date And Time</th>
                                            <th>Feedback on Time</th>
                                            <th width="10%">Status</th>
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
                    <div class="modal-content" style=" width: 750px; margin: auto;">
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
                                            <label for="service_id">Feedback <span class="error">*</span></label>
                                            <textarea class="form-control float-right" name="user_feedback" id="user_feedback">{{old('user_feedback')}}</textarea>
                                             @if($errors->has('user_feedback'))
                                              <span class="text-danger">{{$errors->first('user_feedback')}}</span>
                                             @endif  
                                          </div>

                                          <div class="form-group required">
                                            <label for="property_id">Feedback Status <span class="error">*</span></label>
                                              <select class="form-control parent_role_select2" style="width: 100%;" name="status" id="status" aria-invalid="false">
                                                <option value="">Select Feedback Status</option>
                                                <option value="2">Completed</option>
                                                <option value="4">Completed with Warning</option>
                                                <option value="3">Request for Reschedule</option>

                                              </select>
                                          </div>

                                          <div class="form-group required">
                                              <label>Attach Files</label>
                                              <div>
                                                <button type="button" id="add_new_file" class="btn btn-outline-success"><i class="fa fa-plus"></i>&nbsp;Add File</button>
                                              </div>
                                          </div>
                                          <div id="files_container">

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


