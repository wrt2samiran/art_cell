@extends('admin.dashboard.dashboard-layout')

@section('dashboard-content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>
                  {{$total_customers}}
                </h3>
                <p><strong>NUMBER OF CUSTOMERS</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="{{route('admin.users.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>
                  {{$total_service_providers}}
                </h3>

                <p><strong> NUMBER OF SERVICE PROVIDERS</strong> </p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="{{route('admin.users.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
              <a href="{{route('admin.contracts.list')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
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
                    <a href="{{route('admin.spare_part_orders.order_list')}}">View All</a>
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
                    <a href="{{route('admin.shared_service_orders.order_list')}}">View All</a>
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
                      <!-- <a href="#">View All</a> -->
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