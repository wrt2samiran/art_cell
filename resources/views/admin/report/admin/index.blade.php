@extends('admin.layouts.after-login-layout')
@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Report Management</h1>
          </div>
          <div class="col-sm-6">

          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <!-- SELECT2 EXAMPLE -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Generate Report</h3>
              </div>
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
                  <div class="row justify-content-center">
                    <div class="col-md-3">

                      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-schedule-compliance-tab" data-toggle="pill" href="#schedule-compliance" role="tab" aria-controls="v-pills-schedule-compliance" aria-selected="true">Schedule Compliance</a>

                        <a class="nav-link" id="v-pills-planned-maintenance-tab" data-toggle="pill" href="#planned-maintenance" role="tab" aria-controls="v-pills-planned-maintenance" aria-selected="false">Planned Maintenance</a>

                        <a class="nav-link" id="v-pills-maintenance-backlog-tab" data-toggle="pill" href="#maintenance-backlog" role="tab" aria-controls="v-pills-maintenance-backlog" aria-selected="false">
                        Maintenance Backlog
                        </a>
                        <a class="nav-link" id="v-pills-open-preventive-maintenance-tab" data-toggle="pill" href="#open-preventive-maintenance" role="tab" aria-controls="v-pills-open-preventive-maintenance" aria-selected="false">Open Preventive Maintenance
                        </a>

                        <a class="nav-link" id="v-pills-upcoming-sch-per-week-user-tab" data-toggle="pill" href="#upcoming-sch-per-week-user" role="tab" aria-controls="v-pills-upcoming-sch-per-week-user" aria-selected="false">
                        Upcoming Scheduled/Week<br>
                        (By User / Technician) 
                        </a>
                        <a class="nav-link" id="v-pills-upcoming-sch-per-week-category-tab" data-toggle="pill" href="#upcoming-sch-per-week-category" role="tab" aria-controls="v-pills-upcoming-sch-per-week-category" aria-selected="false">
                        Upcoming Scheduled/Week<br>
                        (By Service Category) 
                        </a>
                        <a class="nav-link" id="v-pills-upcoming-sch-per-week-tab" data-toggle="pill" href="#upcoming-sch-per-week" role="tab" aria-controls="v-pills-upcoming-sch-per-week" aria-selected="false">
                        Upcoming Scheduled/Week
                        </a>
                        <a class="nav-link" id="v-pills-planned-maintenance-per-two-week-tab" data-toggle="pill" href="#planned-maintenance-per-two-week" role="tab" aria-controls="v-pills-planned-maintenance-per-two-week" aria-selected="false">
                        Planned Maintenance/Two Week
                        </a>
                        <a class="nav-link" id="v-pills-upcomming-sch-maintenance-tab" data-toggle="pill" href="#upcomming-sch-maintenance" role="tab" aria-controls="v-pills-upcomming-sch-maintenance" aria-selected="false">
                        Upcoming Schedule Maintenance
                        </a>
                        <a class="nav-link" id="v-pills-closed-wo-tab" data-toggle="pill" href="#closed-wo" role="tab" aria-controls="v-pills-closed-wo" aria-selected="false">
                        Closed WO 
                        </a>
                        <a class="nav-link" id="v-pills-completed-wo-per-month-tab" data-toggle="pill" href="#completed-wo-per-month" role="tab" aria-controls="v-pills-completed-wo-per-month" aria-selected="false">
                        Completed WO/Month
                        </a>

                        <a class="nav-link" id="v-pills-wo-req-vs-completed-tab" data-toggle="pill" href="#wo-req-vs-completed" role="tab" aria-controls="v-pills-wo-req-vs-completed" aria-selected="false">
                        WO Rquested Vs Completed
                        </a>

                        <a class="nav-link" id="v-pills-contract-status-tab" data-toggle="pill" href="#contract-status" role="tab" aria-controls="v-pills-contract-status" aria-selected="false">
                        Contract/Project Status
                        </a>

                        <a class="nav-link" id="v-pills-overdue-wo-tab" data-toggle="pill" href="#overdue-wo" role="tab" aria-controls="v-pills-overdue-wo" aria-selected="false">
                        Overdue Work Orders
                        </a>
                        <a class="nav-link" id="v-pills-all-wo-tab" data-toggle="pill" href="#all-wo" role="tab" aria-controls="v-pills-all-wo" aria-selected="false">
                        All Work Orders
                        </a>
                        <a class="nav-link" id="v-pills-requested-wo-tab" data-toggle="pill" href="#requested-wo" role="tab" aria-controls="v-pills-requested-wo" aria-selected="false">
                        Requested Work Orders
                        </a>
                        <a class="nav-link" id="v-pills-financial-tab" data-toggle="pill" href="#financial" role="tab" aria-controls="v-pills-financial" aria-selected="false">
                        Financial Report
                        </a>
                        
                      </div>

                    </div>
                    <div class="col-md-9">
                      <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="schedule-compliance" role="tabpanel" aria-labelledby="v-pills-schedule-compliance-tab">
                          Schedule Compliance
                        </div>
                        <div class="tab-pane fade" id="planned-maintenance" role="tabpanel" aria-labelledby="v-pills-planned-maintenance-tab">
                          planned-maintenance
                        </div>
                        <div class="tab-pane fade" id="maintenance-backlog" role="tabpanel" aria-labelledby="v-pills-maintenance-backlog-tab">
                          maintenance-backlog
                        </div>
                        <div class="tab-pane fade" id="upcoming-sch-per-week-user" role="tabpanel" aria-labelledby="v-pills-upcoming-sch-per-week-user-tab">upcoming-sch-per-week-user
                        </div>
                        <div class="tab-pane fade" id="upcoming-sch-per-week-category" role="tabpanel" aria-labelledby="v-pills-upcoming-sch-per-week-category-tab">upcoming-sch-per-week-category
                        </div>
                        <div class="tab-pane fade" id="upcoming-sch-per-week" role="tabpanel" aria-labelledby="v-pills-upcoming-sch-per-week-tab">upcoming-sch-per-week
                        </div>

                        <div class="tab-pane fade" id="planned-maintenance-per-two-week" role="tabpanel" aria-labelledby="v-pills-planned-maintenance-per-two-week-tab">planned-maintenance-per-two-week
                        </div>
                        <div class="tab-pane fade" id="upcomming-sch-maintenance" role="tabpanel" aria-labelledby="v-pills-upcomming-sch-maintenance-tab">upcomming-sch-maintenance
                        </div>
                        <div class="tab-pane fade" id="closed-wo" role="tabpanel" aria-labelledby="v-pills-closed-wo-tab">
                        Closed Work Orders
                        </div>
                        <div class="tab-pane fade" id="completed-wo-per-month" role="tabpanel" aria-labelledby="v-pills-completed-wo-per-month-tab">
                        Completed Work Orders per month
                        </div>

                        <div class="tab-pane fade" id="wo-req-vs-completed" role="tabpanel" aria-labelledby="v-pills-wo-req-vs-completed-tab">
                        wo-req-vs-completed
                        </div>
                        <div class="tab-pane fade" id="contract-status" role="tabpanel" aria-labelledby="v-pills-contract-status-tab">
                        Contract/ Project Status
                        </div>
                        <div class="tab-pane fade" id="overdue-wo" role="tabpanel" aria-labelledby="v-pills-overdue-wo-tab">
                        Contract/ Project Status
                        </div>
                        <div class="tab-pane fade" id="all-wo" role="tabpanel" aria-labelledby="v-pills-all-wo-tab">
                        All Work Orders
                        </div>
                        <div class="tab-pane fade" id="requested-wo" role="tabpanel" aria-labelledby="v-pills-requested-wo-tab">
                        Requested Work Orders
                        </div>
                        <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="v-pills-financial-tab">
                        Financial Report
                        </div>
                      </div>
                    </div>


                  </div>
              </div>
            </div>
          </div>
      </div>
    </section>
    
</div>
@endsection 
@push('custom-scripts')

@endpush
