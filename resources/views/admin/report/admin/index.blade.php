@extends('admin.layouts.after-login-layout')
@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('report_module.module_title')}}</h1>
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
                <h3 class="card-title">{{__('report_module.page_header')}}</h3>
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
                        <a class="nav-link active" id="v-pills-schedule-compliance-tab" data-toggle="pill" href="#schedule-compliance" role="tab" aria-controls="v-pills-schedule-compliance" aria-selected="true">
                          {{__('report_module.maintenance_schedule_completed_tab')}}
                        </a>


                        <a class="nav-link" id="v-pills-maintenance-backlog-tab" data-toggle="pill" href="#maintenance-backlog" role="tab" aria-controls="v-pills-maintenance-backlog" aria-selected="false">
                        {{__('report_module.maintenance_backlog_tab')}}
                        </a>
               

                        <a class="nav-link" id="v-pills-upcoming-sch-per-week-tab" data-toggle="pill" href="#upcoming-sch-per-week" role="tab" aria-controls="v-pills-upcoming-sch-per-week" aria-selected="false">
                        {{__('report_module.upcoming_schedule_per_week_tab')}}
                        </a>
 
                        <a class="nav-link" id="v-pills-upcomming-sch-maintenance-tab" data-toggle="pill" href="#upcomming-sch-maintenance" role="tab" aria-controls="v-pills-upcomming-sch-maintenance" aria-selected="false">
                        {{__('report_module.upcoming_schedule_maintenance_tab')}}
                        </a>
                        <a class="nav-link" id="v-pills-work-order-tab" data-toggle="pill" href="#work-order" role="tab" aria-controls="v-pills-work-order" aria-selected="false">
                        {{__('report_module.work_order_report_tab')}}
                        </a>
                        <a class="nav-link" id="v-pills-completed-wo-per-month-tab" data-toggle="pill" href="#completed-wo-per-month" role="tab" aria-controls="v-pills-completed-wo-per-month" aria-selected="false">
                        {{__('report_module.completed_wo_per_month_tab')}}
                        </a>
                        <a class="nav-link" id="v-pills-wo-req-vs-completed-tab" data-toggle="pill" href="#wo-req-vs-completed" role="tab" aria-controls="v-pills-wo-req-vs-completed" aria-selected="false">
                          {{__('report_module.wo_requested_vs_completed_tab')}}
                        </a>

                        <a class="nav-link" id="v-pills-contract-status-tab" data-toggle="pill" href="#contract-status" role="tab" aria-controls="v-pills-contract-status" aria-selected="false">
                        {{__('report_module.contract_status_tab')}}
                        </a>


                        <a class="nav-link" id="v-pills-financial-tab" data-toggle="pill" href="#financial" role="tab" aria-controls="v-pills-financial" aria-selected="false">
                         {{__('report_module.financial_report_tab')}}
                        </a>
                      </div>
                    </div>
                    <div class="col-md-9">
                      <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="schedule-compliance" role="tabpanel" aria-labelledby="v-pills-schedule-compliance-tab">
                          <form method="post" action="{{route('admin.reports.schedule_compliance_report')}}" id="schedule_compliance_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.maintenance_schedule_completed_title')}}</u></h4>
                            <div class="form-group required">
                               <label for="service_status">{{__('report_module.labels.contract')}} </label>
                                <select class="form-control contract" id="schedule_compliance_contract_id" name="contract_id" style="width: 100%;">
                                  <option value="">{{__('report_module.placeholders.contract')}}</option>
                                  <option value="all" selected>All Contract</option>
                                  @forelse($contracts as $contract)
                                  <option value="{{$contract->id}}">{{$contract->code}}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                            <div style="margin-top: -1rem;">{{__('report_module.or')}}</div>
                            <div class="form-group required">
                               <label for="service_status">{{__('report_module.labels.property')}} </label>
                                <select class="form-control property" id="schedule_compliance_property_id" name="property_id" style="width: 100%;">
                                  <option value="">{{__('report_module.placeholders.property')}}</option>
                                  <option value="all" selected>All Property</option>
                                  @forelse($properties as $property)
                                  <option value="{{$property->id}}">{{$property->code}}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}} <span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="schedule_compliance_from_date" class="form-control" name="from_date">
                            </div>
                
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control" id="schedule_compliance_to_date" name="to_date">
                            </div>

                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>
                          </div>
                          <div>
                            <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form> 
                        </div>
    
                        <div class="tab-pane fade" id="maintenance-backlog" role="tabpanel" aria-labelledby="v-pills-maintenance-backlog-tab">

                          <form method="post" action="{{route('admin.reports.maintenance_backlog_report')}}" id="maintenance_backlog_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.maintenance_backlog_title')}}</u></h4>
                            <div class="form-group required">
                               <label for="service_status">{{__('report_module.labels.sp_or_labour')}} <span class="error">*</span></label>
                                <select class="form-control" id="maintenance_backlog_sp_or_labour_id" name="sp_or_labour_id" style="width: 100%;">
                                  <option value="">{{__('report_module.placeholders.sp_or_labour')}}</option>
                                  <option value="0" selected>All Service Provider & Labours</option>
                                  <optgroup label="Service Providers">
                                  @forelse($service_providers as $service_provider)
                                  <option value="{{$service_provider->id}}">{{$service_provider->name}}</option>
                                  @empty
                                  @endforelse
                                  </optgroup>
                                  <optgroup label="Labours">
                                  @forelse($labours as $labour)
                                  <option value="{{$labour->id}}">{{$labour->name}}</option>
                                  @empty
                                  @endforelse
                                  </optgroup>
                                </select>
                            </div>
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}} <span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="maintenance_backlog_from_date" class="form-control" name="from_date">
                            </div>
                
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control" id="maintenance_backlog_to_date" name="to_date">
                            </div>

                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>

                          </div>
                          <div>
                          <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form>
                        </div>
             
                        <div class="tab-pane fade" id="upcoming-sch-per-week" role="tabpanel" aria-labelledby="v-pills-upcoming-sch-per-week-tab">

                          <form method="post" action="{{route('admin.reports.upcoming_weekly_maintenance_report')}}" id="upcoming_weekly_maintenance_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.upcoming_schedule_per_week_title')}}</u></h4>
                            <div class="form-group required">
                               <label for="service_status">{{__('report_module.labels.sp_or_labour')}} <span class="error">*</span></label>
                                <select class="form-control" id="upcoming_weekly_maintenance_sp_or_labour_id" name="sp_or_labour_id" style="width: 100%;">
                                  <option value="">{{__('report_module.placeholders.sp_or_labour')}}</option>
                                  <option value="0" selected>All Service Provider & Labours</option>
                                  <optgroup label="Service Providers">
                                  @forelse($service_providers as $service_provider)
                                  <option value="{{$service_provider->id}}">{{$service_provider->name}}</option>
                                  @empty
                                  @endforelse
                                  </optgroup>
                                  <optgroup label="Labours">
                                  @forelse($labours as $labour)
                                  <option value="{{$labour->id}}">{{$labour->name}}</option>
                                  @empty
                                  @endforelse
                                  </optgroup>
                                </select>
                            </div>
                            <div class="form-group required">
                               <label for="service_status">{{__('report_module.labels.service')}} <span class="error">*</span></label>
                                <select class="form-control" id="upcoming_weekly_maintenance_service_id" name="service_id" style="width: 100%;">
                                  <option value="">{{__('report_module.placeholders.service')}} </option>
                                  <option value="0" selected>All Service </option>
                                 
                                  @forelse($services as $service)
                                  <option value="{{$service->id}}">{{$service->service_name}}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}} <span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="upcoming_weekly_maintenance_from_date" class="form-control" name="from_date">
                            </div>
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control to_date" id="upcoming_weekly_maintenance_to_date" name="to_date">
                            </div>
                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>



                          </div>
                          <div>
                          <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form>

                        </div>

                        <div class="tab-pane fade" id="upcomming-sch-maintenance" role="tabpanel" aria-labelledby="v-pills-upcomming-sch-maintenance-tab">

                          <form method="post" action="{{route('admin.reports.upcoming_schedule_maintenance_report')}}" id="upcoming_schedule_maintenance_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.upcoming_schedule_maintenance_title')}}</u></h4>
             
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}}<span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="upcoming_schedule_maintenance_from_date" class="form-control" name="from_date">
                            </div>
                
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control to_date" id="upcoming_schedule_maintenance_to_date" name="to_date">
                            </div>

                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>


                          </div>
                          <div>
                          <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form>
                        </div>
                        <div class="tab-pane fade" id="work-order" role="tabpanel" aria-labelledby="v-pills-work-order-tab">

                          <form method="post" action="{{route('admin.reports.work_order_report')}}" id="work_order_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.work_order_report_title')}}</u></h4>
                            <div class="form-group required">
                               <label for="work_order_status">{{__('report_module.labels.work_order_status')}} <span class="error">*</span></label>
                                <select class="form-control" id="work_order_status" name="work_order_status" style="width: 100%;">
                                  
                                  <option value="all" selected>All Work Orders</option>
                                  <option value="closed" >Closed Work Orders</option>
                                  <option value="requested" >Requested Work Orders</option>
                                  <option value="overdue" >Overdue Work Orders</option>
                                </select>
                            </div>
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}} <span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="work_order_from_date" class="form-control from_date" name="from_date">
                            </div>
                
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control to_date" id="work_order_to_date" name="to_date">
                            </div>
                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>

                          </div>
                          <div>
                          <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form>

                        </div>
   
                        <div class="tab-pane fade" id="completed-wo-per-month" role="tabpanel" aria-labelledby="v-pills-completed-wo-per-month-tab">

                          <form method="post" action="{{route('admin.reports.work_order_completed_per_month_report')}}" id="work_order_completed_per_month_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.completed_wo_per_month_title')}}</u></h4>
             
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}} <span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="work_order_completed_per_month_from_date" class="form-control from_date" name="from_date">
                            </div>
                
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control to_date" id="work_order_completed_per_month_to_date" name="to_date">
                            </div>
                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>

                          </div>
                          <div>
                          <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form>


                        </div>

                        <div class="tab-pane fade" id="wo-req-vs-completed" role="tabpanel" aria-labelledby="v-pills-wo-req-vs-completed-tab">
                          <form method="post" action="{{route('admin.reports.work_order_requested_vs_completed_report')}}" id="work_order_requested_vs_completed_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.wo_requested_vs_completed_title')}}</u></h4>
             
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}} <span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="work_order_requested_vs_completed_form_date" class="form-control from_date" name="from_date">
                            </div>
                
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control to_date" id="work_order_requested_vs_completed_to_date" name="to_date">
                            </div>
                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>
                          </div>
                          <div>
                          <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form>
                        </div>
                        <div class="tab-pane fade" id="contract-status" role="tabpanel" aria-labelledby="v-pills-contract-status-tab">
                          <form method="post" action="{{route('admin.reports.contract_status_report')}}" id="contract_status_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.contract_status_title')}}</u></h4>
             
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}} <span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="contract_status_from_date" class="form-control from_date" name="from_date">
                            </div>
                
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control to_date" id="contract_status_to_date" name="to_date">
                            </div>
                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>

                            
                          </div>
                          <div>
                          <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form>
                        </div>
        

                        <div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="v-pills-financial-tab">
                          <form method="post" action="{{route('admin.reports.payment_report')}}" id="payment_report_form">
                          @csrf
                          <div>
                            <h4><u>{{__('report_module.financial_report_title')}}</u></h4>
             
                            <div class=" form-group required">
                             <label for="from_date">{{__('report_module.labels.date_from')}} <span class="error">*</span></label>
                             <input type="text" readonly="readonly" autocomplete="off" id="payment_report_from_date" class="form-control from_date" name="from_date">
                            </div>
                
                            <div class="form-group required">
                               <label for="to_date">{{__('report_module.labels.date_to')}} <span class="error">*</span></label>
                               <input type="text"  readonly="readonly" autocomplete="off" class="form-control to_date" id="payment_report_to_date" name="to_date">
                            </div>
                            <div class="mb-3">
                              <label >{{__('report_module.download_as')}} &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">{{__('report_module.excel')}}
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">{{__('report_module.pdf')}}
                                </label>
                              </div>
                            </div>
                            
                          </div>
                          <div>
                          <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.download_report')}}</button>
                          </div>
                          </form>
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
<script type="text/javascript" src="{{asset('js/admin/reports/admin_reports.js')}}"></script>
@endpush
