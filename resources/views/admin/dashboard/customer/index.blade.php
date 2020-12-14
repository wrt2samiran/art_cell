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

</script>
@endpush