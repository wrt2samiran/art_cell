@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Task Labour List</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item "><a href="{{route('admin.work-order-management.labourTaskList', $task_data->work_order_id)}}">Task List</a></li>
                  <li class="breadcrumb-item active">Task Labour List</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
              @if(Session::has('success-message'))
                  <div class="alert alert-success alert-dismissable">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('success-message') }}
                  </div>
              @endif
              @if(Session::has('error'))
                  <div class="alert alert-danger alert-dismissable">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('error') }}
                  </div>
              @endif

              <table class="table table-bordered table-hover" id="country-details-table">
                <tbody>
                  <?php //dd($service_allocation_data);?>
                  <tr>
                    <td >Task Title</td>
                    <td >{{$task_data->task_title}}</td>
                  </tr>
                  <tr>
                    <td >Contract Code</td>
                    <td >{{$task_data->contract->code}}</td>
                  </tr>
                  <tr>
                    <td >Work Order Title</td>
                    <td >{{$task_data->work_order->task_title}}</td>
                  </tr>
                  <tr>
                    <td >Property Name</td>
                    <td >{{$task_data->property->property_name}}</td>
                  </tr>
                  <tr>
                    <td >Service</td>
                    <td >{{$task_data->service->service_name}}</td>
                  </tr>
                  
                  <tr>
                    <td>Task Details</td>
                    <td >{!! $task_data->task_desc!!}</td>
                  </tr>
                  
                  

                  
            
            <table class="table table-bordered" id="task_labour_list_management_table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Labour Title</th>
                        <th>Task Date</th>
                        <th>Task Slot</th>
                        <th>Task Mode</th>
                        <th>Labour Feedback</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            
                <!-- <div class="modal fade" id="addFeedbackModal" role="dialog">
                  <div class="modal-dialog">
                  
                    <div class="modal-content">
                      <div class="modal-header">
                        
                        <h4 class="modal-title">Add Feedback</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="card-body">
                            <div class="row justify-content-center">
                              <div class="col-md-10 col-sm-12">
                                
                                <form  id="admin_labour_task_feedback_form" action="{{route('admin.work-order-management.taskFeedback')}}" method="post" enctype="multipart/form-data">
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
                </div> -->
                <div>
                  <a href="{{route('admin.work-order-management.labourTaskList', $task_data->work_order_id)}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
                </div>
                <div class="modal fade" id="taskRescheduleModal" role="dialog">
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
                                
                                <form  id="service_provider_reschedule" action="{{route('admin.work-order-management.labourTaskReschedule')}}" method="post" enctype="multipart/form-data">
                                  @csrf
                                        <div>  
                                          <input type="hidden" name="task_details_id" id="task_details_id" />
                                   
                                          <div class="form-group">

                                            <div class="form-group required">
                                              <label for="task_date">Start Date <span class="error">*</span></label>
                                              <input type="text" class="form-control" value="{{old('task_date')?old('task_date'):''}}" readonly="readonly" name="task_date" id="task_date" autocomplete="off"  placeholder="Task Date">
                                              @if($errors->has('task_date'))
                                              <span class="text-danger">{{$errors->first('task_date')}}</span>
                                              @endif
                                            </div>
                                            <div class="form-group required">
                                              <label for="task_description">Task Details</label>
                                              <textarea class="form-control" name="task_description" id="task_description">{{old('task_description')}}</textarea>
                                               @if($errors->has('task_description'))
                                                <span class="text-danger">{{$errors->first('task_description')}}</span>
                                               @endif
                                            </div>
                          
                                            <!-- /.input group -->
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

              <!-- Labour Feedback END-->  

@endsection

@push('custom-scripts')
<script type="text/javascript">

</script>

<script type="text/javascript" src="{{asset('js/admin/work_order_management/task-labour-list.js')}}"></script>
@endpush


