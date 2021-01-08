@extends('admin.dashboard.dashboard-layout')

@section('dashboard-content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>
                  {{$total_labours}}
                </h3>
                <p><strong>NUMBER OF LABOURS</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="{{route('admin.labour.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        
	        <div class="col-lg-4 col-6">
	            <!-- small box -->
	            <div class="small-box bg-success">
	              <div class="inner">
	                <h3>
	                  {{$total_contracts}}
	                </h3>

	                <p><strong> NUMBER OF CONTRACTS</strong> </p>
	              </div>
	              <div class="icon">
	                <i class="fa fa-user"></i>
	              </div>
	              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
	            </div>
	        </div>
	        <div class="col-lg-4 col-6">
		        <div class="small-box bg-warning">
		          <div class="inner">
		            <h3>
		              {{$total_work_orders}}
		            </h3>
		            <p><strong>NUMBER OF WORK ORDER</strong></p>
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
                      <i class="fas fa-book-open mr-1"></i>
                      Last twelve month work orders
                    </h3>
                  </div>
                  <div>
                    <a href="{{route('admin.work-order-management.list')}}">View All</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <canvas id="workOrderChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                        
                        Task Lists
                      </h3>
                    </div>
                    <div>
                     <!--  <a href="{{route('admin.complaints.list')}}">View All</a> -->
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                      <table class="table m-0">
                      <thead>
                          <tr>
                              <th>Contract Code</th>
                              <th>Work Order Title</th>
                              <th>Service Provider</th>
                              <th>Property</th>
                              <th>Task Title</th>
                              <th>Task Complete(%)</th>
                              <!-- <th>Status</th> -->
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($tasks as $task)
                          <tr>
                             <td>{{@$task->contract->code}}</td> 
                             <td>{{@$task->work_order->task_title}}</td> 
                             <td>{{@$task->contract->service_provider->name}}</td>
                             <td>{{@$task->property->property_name}}</td>
                             <td>{{@$task->task_title}}</td>
                             <td>@if(@$task->task_complete_percent>0){{@$task->task_complete_percent}}@else{{'Not Started Yet'}}@endif</td>
                             <!-- <td>
                              {{$task->get_status_name()}}
                             </td> -->
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
                        <i class="far fa-question-circle"></i>
                        Complaints
                      </h3>
                    </div>
                    <div>
                      <a href="{{route('admin.complaints.list')}}">View All</a>
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
                             <td>{{@$complaint->contract->code}}</td> 
                             <td>{{@$complaint->work_order_id?$complaint->work_order_id:'N/A'}}</td> 
                             <td>{{@$complaint->details}}</td>
                             <td>{{@$complaint->complaint_status->status_name}}</td>
                             <td>{{@$complaint->created_at->format('d/m/Y')}}</td>
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
                        Latest Work Orders
                      </h3>
                    </div>
                    <div>
                      <a href="{{route('admin.work-order-management.list')}}">View All</a>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                      <table class="table m-0">
                      <thead>
                          <tr>
                              <!-- <th>ID</th> -->
                              <th>Contract Code</th>
                              <th>Work Order Titile</th>
                              <th>Type</th>
                              <th>Service</th>
                              <th>Created At</th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($work_orders as $work_order)
                          <tr>
                             <!-- <td>{{$work_order->id}}</td> --> 
                             <td>{{@$work_order->contract->code}}</td> 
                             <td>{{@$work_order->task_title}}</td>
                             <td>{{@$work_order->contract_services->service_type}}</td>
                             <td>{{@$work_order->service->service_name}}</td>
                             <td>{{@$work_order->created_at->format('d/m/Y')}}</td>
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

  <?php //dd($twelve_months_work_orders);?>

@endsection

@push('custom-scripts')
<script type="text/javascript">
  
    $(function () {

      var labels=<?php echo json_encode(@$last_twelve_month_work_order_array);?>;
      console.log(labels);
      /** spare part orders chart */
      var workorders_graph_data_array=<?php echo json_encode(@$twelve_months_work_orders);?>;
      console.log(workorders_graph_data_array);
      var workOrderChartData = {
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
              data                : workorders_graph_data_array
            }

          ]
        }

        var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        var workOrderChartCanvas = $('#workOrderChart').get(0).getContext('2d')
        var barWorkOrderChartData = jQuery.extend(true, {}, workOrderChartData)
        var temp0 = workOrderChartData.datasets[0]

        barWorkOrderChartData.datasets[0] = temp0

        var workOrderChart = new Chart(workOrderChartCanvas, {
          type: 'bar', 
          data: barWorkOrderChartData,
          options: barChartOptions
        });

        /**************/

  });
</script>
@endpush