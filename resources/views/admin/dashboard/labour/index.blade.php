@extends('admin.dashboard.dashboard-layout')

@section('dashboard-content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>
                  {{$total_pending_tasks}}
                </h3>
                <p><strong>NUMBER OF PENDING TASKS</strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-circle"></i>
              </div>
              <a href="{{route('admin.work-order-management.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        
	        <div class="col-lg-4 col-6">
	            <!-- small box -->
	            <div class="small-box bg-secondary">
	              <div class="inner">
	                <h3>
	                  {{$total_overdue_tasks}}
	                </h3>

	                <p><strong> NUMBER OF OVER-DUE TASKS</strong> </p>
	              </div>
	              <div class="icon">
	                <i class="far fa-clock"></i>
	              </div>
	              <a href="{{route('admin.work-order-management.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
	            </div>
	        </div>
	        <div class="col-lg-4 col-6">
		        <div class="small-box bg-success">
		          <div class="inner">
		            <h3>
		              {{$total_completed_tasks}}
		            </h3>
		            <p><strong>NUMBER OF COMPLETED TASKS</strong></p>
		          </div>
		          <div class="icon">
		            <i class="fas fa-handshake"></i>
		          </div>
		          <a href="{{route('admin.work-order-management.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
		        </div>
		    </div>
		</div>
	    
	    <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header border-0">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h3 class="card-title">
                        
                       Upcoming Task Lists
                      </h3>
                    </div>
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-4">
                          <select id="task_title" style="width: 100%;">
                            <option value="">Filter By Task</option>
                            <option value="all" selected>All Tasks</option>
                            @forelse($tasks as $task_data)
                            <option value="{{$task_data->task_id}}">{{$task_data->task->task_title}}</option>
                            @empty

                            @endforelse
                          </select>
                        </div>

                        <div class="col-md-4">
                          <select id="task_property" style="width: 100%;">
                            <option value="">Filter By Property</option>
                            <option value="all" selected>All Properties</option>
                            @forelse($task_properties as $task_property)
                            <option value="{{$task_property->id}}">
                              {{$task_property->code}} ({{$task_property->property_name}})
                            </option>
                            @empty
                            @endforelse
                          </select>
                        </div>
                        <div class="col-md-4">
                          <select id="task_service" style="width: 100%;">
                            <option value="">Filter By Service</option>
                            <option value="all" selected>All Service</option>
                            @forelse($work_order_services as $service_data)
                            <option value="{{$service_data->id}}">
                             {{$service_data->service_name}}
                            </option>
                            @empty
                            @endforelse
                          </select>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0" id="task_data">
                  <div class="table-responsive">
                      <table class="table m-0">
                      <thead>
                          <tr>
                              <th>Task Title</th>
                              <th>Task Date And Time</th>
                              <th>Property</th>
                              <th>Service</th>
                              <th>Task Finish Date and Time (Scheduled)</th>
                              <th>Slot</th>
                              <th>Created At</th>
                          </tr>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($task_details_upcoming_list as $task_upcoming)
                          <tr>
                             <td>{{@$task_upcoming->task->task_title}}</td> 
                             <td>{{@$task_upcoming->task_date}}</td> 
                             <td>{{@$task_upcoming->task->property->property_name}}</td>
                             <td>{{@$task_upcoming->service->service_name}}</td>
                             <td>{{@$task_upcoming->created_at->format('d/m/Y')}}</td>
                             <td>{{@$task_upcoming->get_slot_name()}}</td>
							               <td>{{@$task_upcoming->created_at->format('d/m/Y')}}</td>                             
                          </tr>
                          @empty
                          <tr>
                             <td colspan="7">No upcomming tasks</td> 
                          </tr>
                          @endforelse
                      </tbody>
                  </table>
                  </div>

                </div>
                <!-- /.card-body -->
              </div>
            </div>
   
            <!-- /.col -->
        </div>
        <div class="row">
	        <div class="col-md-6">
		        <div class="card">
		          <div class="card-header border-0">
		            <div class="d-flex justify-content-between">
		              <div>
		                <h3 class="card-title">
		                  <i class="fas fa-book-open mr-1"></i>
		                  Last six month assigned tasks
		                </h3>
		              </div>
		              <div>
		                <a href="#">View All</a>
		              </div>
		            </div>
		          </div>
		          <div class="card-body">
		            <canvas id="tasksChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
		          </div>
		          <!-- /.card-body -->
		        </div>
	        </div>
	   
	        <div class="col-md-6">
              <div class="card">
                <div class="card-header border-0">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h3 class="card-title">
                        <i class="far fas fa-quote-right"></i>
                        Latest Reschedule Request
                      </h3>
                    </div>

                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-4">
                          <select id="task_title_reschedule" style="width: 100%;">
                            <option value="">Filter By Task</option>
                            <option value="all" selected>All Tasks</option>
                            @forelse($tasks_resschedule as $task_data)
                            <option value="{{$task_data->task_id}}">{{$task_data->task->task_title}}</option>
                            @empty

                            @endforelse
                          </select>
                        </div>

                        <div class="col-md-4">
                          <select id="task_property_reschedule" style="width: 100%;">
                            <option value="">Filter By Property</option>
                            <option value="all" selected>All Properties</option>
                            @forelse($task_properties as $task_property)
                            <option value="{{$task_property->id}}">
                              {{$task_property->code}} ({{$task_property->property_name}})
                            </option>
                            @empty
                            @endforelse
                          </select>
                        </div>
                        
                        
                      </div>
                    </div>

                    <div>
                      <a href="#">View All</a>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0" id="reschedule_task_data">
                  <div class="table-responsive">
                      <table class="table m-0">
                      <thead>
                          <tr>
                              <!-- <th>ID</th> -->
                              <th>Task Title</th>
                              <th>Task Date And Time</th>
                              <th>Property</th>
                              <th>Service</th>
                              <th>Created At</th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($task_details_reschedule_list as $reschedule_list)
                          <tr>
                             <td>{{@$reschedule_list->task->task_title}}</td> 
                             <td>{{@$reschedule_list->task_date}}</td>
                             <td>{{@$reschedule_list->task->property->property_name}}</td>
                             <td>{{@$reschedule_list->service->service_name}}</td>
                             <td>{{@$reschedule_list->created_at->format('d/m/Y')}}</td>
                          </tr>
                          @empty
                          <tr>
                             <td colspan="5">No Reschedule Request</td> 
                          </tr>
                          @endforelse
                      </tbody>
                  </table>
                  </div>

                </div>
                <!-- /.card-body -->
              </div>
            </div>
	    </div>
       
       
       
      </div><!-- /.container-fluid -->
    </section>


  <!-- /.content-wrapper -->

  <?php //dd($twelve_months_work_orders);?>

@endsection

@push('custom-scripts')

<script type="text/javascript">
  
    $(function () {

      var labels=<?php echo json_encode(@$last_six_month_task_assigned_array);?>;
      console.log(labels);
      /** spare part orders chart */
      var tasks_graph_data_array=<?php echo json_encode(@$last_six_month_tasks_assigned);?>;
      console.log(tasks_graph_data_array);
      var tasksChartData = {
          labels  : labels,
          datasets: [
            {
              label               : 'Orders',
              backgroundColor     : 'rgba(60,141,188,0.9)',
              borderColor         : 'rgba(60,141,188,0.8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)',
              data                : tasks_graph_data_array
            }

          ]
        }

        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        var tasksChartCanvas = $('#tasksChart').get(0).getContext('2d')
        var barTasksChartData = jQuery.extend(true, {}, tasksChartData)
        var temp0 = tasksChartData.datasets[0]

        barTasksChartData.datasets[0] = temp0

        var tasksChart = new Chart(tasksChartCanvas, {
          type: 'bar', 
          data: barTasksChartData,
          options: barChartOptions
        });

        /**************/

  });



   /*****************/

  /*Task filter */

  $('#task_title').on('change',function(){
   filter_task();
  });
 
  $('#task_service').on('change',function(){
   filter_task();
  });

  $('#task_property').on('change',function(){
   filter_task();
  });

  function filter_task(){

    $.LoadingOverlay("show");
    $.ajax({
      url: '{{route("admin.dashboard")}}',
      type: "POST",
      data:{
        task_filter: true,
        task_id: $('#task_title').val(),
        property_id: $('#task_property').val(),
        task_service_id: $('#task_service').val(),
        "_token": $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        
        $.LoadingOverlay("hide");

        $('#task_data').html(data.html);
        
      },
      error: function(jqXHR, textStatus, errorThrown) {
         $.LoadingOverlay("hide");
         var response=jqXHR.responseJSON;
         var status=jqXHR.status;
         if(status=='404'){
          toastr.error('Invalid URL', 'Error', {timeOut: 5000});
         }else{
           toastr.error('Internal server error.', 'Error', {timeOut: 5000});
         }
      }
    });


  }


$('#task_title').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Task Title',
    "language": {
       "noResults": function(){
           return "No Task";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


$('#task_service').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Service',
    "language": {
       "noResults": function(){
           return "No Service";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#task_property').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Property',
    "language": {
       "noResults": function(){
           return "No Property";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
  /*****************/



/*****************/

  /* Reschedule Task filter */

  $('#task_title_reschedule').on('change',function(){
   filter_task();
  });
 

  $('#task_property_reschedule').on('change',function(){
   filter_task();
  });

  function filter_task(){

    $.LoadingOverlay("show");
    $.ajax({
      url: '{{route("admin.dashboard")}}',
      type: "POST",
      data:{
        task_reschedule_filter: true,
        task_id: $('#task_title_reschedule').val(),
        property_id: $('#task_property_reschedule').val(),
        task_service_id: $('#task_service').val(),
        "_token": $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        
        $.LoadingOverlay("hide");

        $('#reschedule_task_data').html(data.html);
        
      },
      error: function(jqXHR, textStatus, errorThrown) {
         $.LoadingOverlay("hide");
         var response=jqXHR.responseJSON;
         var status=jqXHR.status;
         if(status=='404'){
          toastr.error('Invalid URL', 'Error', {timeOut: 5000});
         }else{
           toastr.error('Internal server error.', 'Error', {timeOut: 5000});
         }
      }
    });


  }


$('#task_title_reschedule').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Task Title',
    "language": {
       "noResults": function(){
           return "No Task";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


$('#task_property_reschedule').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Service',
    "language": {
       "noResults": function(){
           return "No Service";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#task_property').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Property',
    "language": {
       "noResults": function(){
           return "No Property";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
  /*****************/

</script>

@endpush