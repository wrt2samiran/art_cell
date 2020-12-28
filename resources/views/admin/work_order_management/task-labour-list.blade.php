@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Task Details And Labour List</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item "><a href="{{route('admin.work-order-management.labourTaskList', $task_data->work_order_id)}}">Task List</a></li>
                  <li class="breadcrumb-item active">Task Details And Labour List</li>
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
                    <td >Service Type</td>
                    <td >{{$task_data->contract_services->service_type}}</td>
                  </tr>
                  <tr>
                    <td>Task Details</td>
                    <td >{!! $task_data->task_desc!!}</td>
                  </tr>
                  <tr>
                    <td>Created At</td>
                    <td >{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', @$task_data->created_at)->format('d/m/Y')}}</td>
                  </tr>
                  <tr>
                    <td>Completed (%)</td> 
                    <td ><div class="progress"><div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="{{$task_data->task_complete_percent}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$task_data->task_complete_percent}}%">{{$task_data->task_complete_percent}}% </div></div></td>
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
                  
                  

                  
            
            <table class="table table-bordered" id="task_labour_list_management_table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Labour Title</th>
                        <th>Task Finish Date And Time (Assigned)</th>
                        <th>Task Slot</th>
                        <th>Task Mode</th>
                        <th>Labour Feedback</th>
                        <th>Feedback Date And Time</th>
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
                        
                        <h4 class="modal-title">Add Reschedule</h4>
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
                                              <label for="task_date">Task Date <span class="error">*</span></label>
                                              <input type="text" class="form-control" value="{{old('task_date')?old('task_date'):''}}" readonly="readonly" name="task_date" id="task_date" autocomplete="off"  placeholder="Task Date" onchange="checkOnDemandFree()">
                                              @if($errors->has('task_date'))
                                              <span class="text-danger">{{$errors->first('task_date')}}</span>
                                              @endif
                                            </div>
                                            <div class="form-group required">
                                              <label for="finish_time">Task Finish Time<span class="error">*</span></label>
                                                  
                                                  <input type="text" class="form-control clockpicker" readonly="" value="" name="assigned_finish_time" id="assigned_finish_time" onchange="checkOnDemandFree()">
                                                </label>
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
                                     <button type="submit" class="btn btn-success" disabled="">Submit</button> 
                                     <div class="live_list" id="live_list" content="width=device-width, initial-scale=1"></div>

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
$( document ).ready(function() {
      $(".clockpicker").clockpicker({
           timeFormat: 'HH:mm',
           interval: 30,
           scrollbar: true,
       });
});

function checkOnDemandFree()
    {

      if (($('#task_date').val().length > 0) && ($('#assigned_finish_time').val().length  > 0))
      {
        $(".live_list").empty();
        $('.btn-success').prop('disabled', false);
        //var userDataString =  document.getElementById('user_id').innerHTML;
        //var userDataString = $('#user_id option:selected').val();
        var userDataString = $('#user_id').val();
        var dateString = $('#date_range').val();
        var work_order_id = $('#work_order_id');
        console.log(dateString);
        var userData = JSON.stringify(userDataString);
        var selectedDate = JSON.stringify(dateString);
        var work_order_id = JSON.stringify(work_order_id);
        $.ajax({
        
        url: "{{route('admin.work-order-management.checkAvailablity')}}",
        type:'post',
        dataType: "json",
        data: {data : userData, selectedDate : selectedDate, work_order_id : work_order_id, _token:"{{ csrf_token() }}"},
        cache: false,
        }).done(function(response) {
           
           console.log(response.status);
           if(response.status){
            //console.log(response.userLeaveList);
            // var stringifiedWeeklyLeave = JSON.stringify(response.weeklyLeave);
             var userLeaveList = JSON.stringify(response.userLeaveList);
             var userLeaveData = JSON.parse(userLeaveList);
             var all_leave_list = '';
             console.log(userLeaveList.length);
            if (userLeaveList.length>0) {
               // alert('Positive ::'+userLeaveList.length);
                  document.getElementById("live_list").setAttribute("style","overflow:auto;height:150px;width:350px;color: red; font-weight: bold;");
                  $.each(userLeaveData,function(index, leave_all_dates){
                    all_leave_list += index+' have leave on below days : '+'<br>';
                    $.each(leave_all_dates,function(indexval, leave_date){
                    console.log(index);
                    console.log(leave_date);
                    all_leave_list += leave_date+'<br>';
                    });
                  });  
                 // $("#live_list").text('dta set');
                  $("div.live_list").html(all_leave_list );
              }
            
            }

            
        });
      }
      else
      {
        $('.btn-success').prop('disabled', true);
      }
    }
</script>

<script type="text/javascript" src="{{asset('js/admin/work_order_management/task-labour-list.js')}}"></script>
@endpush


