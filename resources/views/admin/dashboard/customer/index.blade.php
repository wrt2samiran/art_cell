@extends('admin.dashboard.dashboard-layout')

@section('dashboard-content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">

          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>
                  {{$total_properties}}
                </h3>

                <p><strong> NUMBER OF PROPERTIES</strong> </p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>

              @if($current_user->hasAllPermission(['property-list']))
              <a href="{{route('admin.properties.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              @elseif($current_user->hasAllPermission(['users-property-list']))
			  <a href="{{route('admin.user_properties.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              @endif
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>
                  {{$total_contracts}}
                </h3>
                <p><strong>NUMBER OF CONTRACTS </strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-handshake"></i>
              </div>
              @if($current_user->hasAllPermission(['users-contract-list']))
              <a href="{{route('admin.user_contracts.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              @endif
            </div>
          </div>
          @if($current_user->created_by_admin)
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>
                  {{$total_users}}
                </h3>
                <p><strong>NUMBER OF USERS</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="{{route('admin.users.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          @endif
        </div>
        <div class="row">
            <div class="col-md-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <div>
                    <h3 class="card-title">
                      <i class="fas fa-book-open mr-1"></i>
                      Last six month spare part orders
                    </h3>
                  </div>
                  <div>
                    <a href="{{route('admin.spare_part_orders.my_orders')}}">View All</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <canvas id="orderSparePartChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                      <i class="fas fa-book-open mr-1"></i>
                      Last six month shared service orders
                    </h3>
                  </div>
                  <div>
                    <a href="{{route('admin.shared_service_orders.my_orders')}}">View All</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <canvas id="orderSharedServiceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header border-0">
                  <div class="d-flex justify-content-between">
                    <div>
                      <h3 class="card-title">
                        
                        Upcomming Task Lists
                      </h3>
                    </div>
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-4">
                          <select id="task_contract" style="width: 100%;">
                            <option value="">Filter By Contract</option>
                            <option value="all" selected>All Contract</option>
                            @forelse($work_order_contracts as $work_order_contract)
                            <option value="{{$work_order_contract->id}}"> {{$work_order_contract->code}} ({{$work_order_contract->title}})</option>
                            @empty

                            @endforelse
                          </select>
                        </div>
                        <div class="col-md-4">
                          <select id="task_work_order" style="width: 100%;">
                            <option value="">Filter By Work Order</option>
                            <option value="all" selected>All Work Order</option>
                            @forelse($task_work_orders as $task_work_order)
                            <option value="{{$task_work_order->id}}">
                              Id : {{$task_work_order->id}} ({{$task_work_order->task_title}})
                            </option>
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
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                      <table class="table m-0">
                      <thead>
                          <tr>
                              <th>Contract Code</th>
                              <th>Work Order ID</th>
                              <th>Service Provider</th>
                              <th>Property</th>
                              <th>Task Title</th>
                              <th>Task Date</th>
                              <th>Status</th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($tasks as $task)
                          <tr>
                             <td>{{$task->contract->code}}</td> 
                             <td>{{$task->work_order_id}}</td> 
                             <td>{{$task->contract->service_provider->name}}</td>
                             <td>{{$task->property->property_name}}</td>
                             <td>{{$task->task_title}}</td>
                             <td>{{Carbon::parse($task->start_date)->format('d/m/Y')}}</td>
                             <td>
                             	{{$task->get_status_name()}}
                             </td>
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
                        <a href="{{route('admin.complaints.list')}}"><i class="far fa-question-circle"></i>
                        Complaints</a>
                      </h3>
                    </div>
                    <div>
                      <div class="row">

                        <div class="col">
                          <select id="complaint_contract">
                            <option value="">Filter By Contract</option>
                            <option value="all" selected>All Contract</option>
                            @forelse($complaint_contracts as $complaint_contract)
                            <option value="{{$complaint_contract->id}}"> {{$complaint_contract->code}}</option>
                            @empty

                            @endforelse
                          </select>
                        </div>
                        <div class="col">
                          <select id="complaint_status">
                            <option value="">Filter By Status</option>
                            <option value="all" selected>All Status</option>
                            @forelse($complaint_statuses as $complaint_status)
                            <option value="{{$complaint_status->id}}">{{$complaint_status->status_name}}</option>
                            @empty

                            @endforelse
                          </select>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                      <table class="table m-0">
                      <thead>
                          <tr>
                              <th>Contract Code</th>
                              <th>Work Order ID</th>
                              <th>Complaint</th>
                              <th>Status</th>
                              <th>Created At</th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($complaints as $complaint)
                          <tr>
                             <td>{{$complaint->contract->code}}</td> 
                             <td>{{$complaint->work_order_id?$complaint->work_order_id:'N/A'}}</td> 
                             <td>{{$complaint->details}}</td>
                             <td>{{$complaint->complaint_status->status_name}}</td>
                             <td>{{$complaint->created_at->format('d/m/Y')}}</td>
                          </tr>
                          @empty
                          <tr>
                             <td colspan="5">No complaints</td> 
                          </tr>
                          @endforelse
                      </tbody>
                  </table>
                  </div>

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
                        Work Orders
                      </h3>
                    </div>
                    <div>
                      <div class="row">

                        <div class="col">
                          <select id="work_order_contract">
                            <option value="">Filter By Contract</option>
                            <option value="all" selected>All Contract</option>
                            @forelse($work_order_contracts as $work_order_contract)
                            <option value="{{$work_order_contract->id}}"> {{$work_order_contract->code}}</option>
                            @empty

                            @endforelse
                          </select>
                        </div>
                        <div class="col">
                          <select id="work_order_service">
                            <option value="">Filter By Service</option>
                            <option value="all" selected>All Service</option>
                            @forelse($work_order_services as $service)
                            <option value="{{$service->id}}"> {{$service->service_name}}</option>
                            @empty

                            @endforelse
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                      <table class="table m-0">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Contract Code</th>
                              <th>Task Titile</th>
                              <th>Service</th>
                              <th>Created At</th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($work_orders as $work_order)
                          <tr>
                             <td>{{$work_order->id}}</td> 
                             <td>{{$work_order->contract->code}}</td> 
                             <td>{{$work_order->task_title}}</td>
                             <td>{{$work_order->service->service_name}}</td>
                             <td>{{$work_order->created_at->format('d/m/Y')}}</td>
                          </tr>
                          @empty
                          <tr>
                             <td colspan="5">No complaints</td> 
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
      </div><!-- /.container-fluid -->
    </section>


  <!-- /.content-wrapper -->

@endsection

@push('custom-scripts')
<script type="text/javascript">
  /*complaint filter */

  $('#complaint_status').on('change',function(){
   filter_complaint();
  });
  $('#complaint_contract').on('change',function(){
   filter_complaint();
  });
  
  function filter_complaint(){

    $.LoadingOverlay("show");
    $.ajax({
      url: '{{route("admin.dashboard")}}',
      type: "POST",
      data:{
        complaint_filter: true,
        complaint_status_id: $('#complaint_status').val(),
        complaint_contract_id: $('#complaint_contract').val(),
        "_token": $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        
        $.LoadingOverlay("hide");

        $('#complaints_data').html(data.html);
        
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


 $('#complaint_contract').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Contract',
    "language": {
       "noResults": function(){
           return "No Contract";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
$('#complaint_status').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Status',
    "language": {
       "noResults": function(){
           return "No Status";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

  /*****************/

  /*Work Order filter */

  $('#work_order_contract').on('change',function(){
   filter_work_order();
  });
  $('#work_order_service').on('change',function(){
   filter_work_order();
  });
  function filter_work_order(){

    $.LoadingOverlay("show");
    $.ajax({
      url: '{{route("admin.dashboard")}}',
      type: "POST",
      data:{
        work_order_filter: true,
        work_order_contract_id: $('#work_order_contract').val(),
        work_order_service_id: $('#work_order_service').val(),
        "_token": $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        
        $.LoadingOverlay("hide");

        $('#work_order_data').html(data.html);
        
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


 $('#work_order_contract').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Contract',
    "language": {
       "noResults": function(){
           return "No Contract";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
$('#work_order_service').select2({
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
  /*****************/


  /*Task filter */

  $('#task_contract').on('change',function(){
   filter_task();
  });
  $('#task_work_order').on('change',function(){
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
        contract_id: $('#task_contract').val(),
        work_order_id: $('#task_work_order').val(),
        property_id: $('#task_property').val(),
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


 $('#task_contract').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Contract',
    "language": {
       "noResults": function(){
           return "No Contract";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
$('#task_work_order').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Work Order',
    "language": {
       "noResults": function(){
           return "No Work Order";
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
/**************/


    $(function () {

      var labels=<?php echo json_encode($last_six_month_array);?>;

      /** spare part orders chart */
      var spare_part_orders_graph_data_array=<?php echo json_encode($six_months_spare_part_orders);?>;

      var orderSparePartChartData = {
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
              data                : spare_part_orders_graph_data_array
            }

          ]
        }

        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        var orderSparePartChartCanvas = $('#orderSparePartChart').get(0).getContext('2d')
        var barSparePartChartData = jQuery.extend(true, {}, orderSparePartChartData)
        var temp0 = orderSparePartChartData.datasets[0]

        barSparePartChartData.datasets[0] = temp0

        var orderSparePartChart = new Chart(orderSparePartChartCanvas, {
          type: 'bar', 
          data: barSparePartChartData,
          options: barChartOptions
        });

        /**************/

      /** Shared service orders chart */
      var six_months_shared_service_orders=<?php echo json_encode($six_months_shared_service_orders);?>;


      var orderSharedServiceChartData = {
          labels  : labels,
          datasets: [
            {
              label               : 'Orders',
              backgroundColor     : 'rgba(132, 245, 66,0.9)',
              borderColor         : 'rgba(132, 245, 66,0.8)',
              pointRadius          : false,
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,100,200,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,100,200,1)',
              data                : six_months_shared_service_orders
            }

          ]
        }

        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        var orderSharedServiceChartCanvas = $('#orderSharedServiceChart').get(0).getContext('2d')
        var barSparePartChartData = jQuery.extend(true, {}, orderSharedServiceChartData)
        var temp0 = orderSharedServiceChartData.datasets[0]

        barSparePartChartData.datasets[0] = temp0

        var orderSharedServiceChart = new Chart(orderSharedServiceChartCanvas, {
          type: 'bar', 
          data: barSparePartChartData,
          options: barChartOptions
        });

        /**************/


  });
</script>
@endpush