@extends('admin.layouts.after-login-layout')
@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Contract Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.contracts.list')}}">Contracts</a></li>
              <li class="breadcrumb-item active">
                {{($contract->creation_complete)?'Edit':'Create'}}
              </li>
            </ol>
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
                <h3 class="card-title">{{($contract->creation_complete)?'Edit':'Create'}} Contract</h3>
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
                  <input type="hidden" name="contract_start_date" id="contract_start_date" value="{{$contract->start_date}}">
                  <input type="hidden" name="contract_end_date" id="contract_end_date" value="{{$contract->end_date}}">

                  <div class="row justify-content-center">
                    @include('admin.contracts.partials.multi_step_links')
                    <div class="col-md-11 col-sm-12">
                      <div id="accordion">
                        <div class="card">
                          <div  id="headingOne">
                              <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                View Added Services
                              </button>
                          </div>

                          <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body" style="padding: 10px 3px;">
                                <table class="table table-bordered" id="contract_services_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Service</th>
                                            <th>Service Type</th>
                                            <th>Price ({{Helper::getSiteCurrency()}})</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <input type="hidden" value="{{route('admin.contracts.services',$contract->id)}}" id="contract_services_data_url">
                            </div>
                          </div>
                        </div>
                      </div>
                      <form action="{{route('admin.contracts.services.store',$contract->id)}}"  method="post" id="add_service_form">
                        @csrf
                        <div> 
                          <h6>Add New Service</h6>
                          <div class="form-group required">
                             <label for="services">Select service<span class="error">*</span></label>
                              <select class="form-control " name="service" id="service" style="width: 100%;">
                                <option value="">Select service</option>
                                @forelse($services as $service)
                                   <option data-service_price="{{$service->price}}" data-service_name="{{$service->service_name}}" value="{{$service->id}}">{{$service->service_name}} ({{Helper::getSiteCurrency()}} {{$service->price}}) </option>
                                @empty
                                <option value="">No Service Found</option>
                                @endforelse                                
                              </select>
                          </div>
                          <div class="form-group required">
                              <label for="service_type">Service Type <span class="error">*</span></label>
                              <select class="form-control " name="service_type" id="service_type" style="width: 100%;">
                                 <option value="">Select service type</option>
                                 <option value="Maintenance">Maintenance</option> 
                                 <option value="On Demand">On Demand</option>  
                                 <option value="Free">Free</option>    
                              </select>
                          </div>
                          <div class="form-group required" id="frequency_type_holder" style="display: none;">
                             <label for="frequency_types">How frequently service will be maintain?<span class="error">*</span></label>
                              <select class="form-control " name="frequency_type" id="frequency_type" style="width: 100%;">
                                <option value="">Select services</option>
                                @forelse($frequency_types as $frequency_type)
                                   <option data-interval_days="{{$frequency_type->no_of_days}}" data-type_name="{{$frequency_type->type}}" value="{{$frequency_type->id}}">{{$frequency_type->type}} </option>
                                @empty
                                <option value="">No Service Found</option>
                                @endforelse                                
                              </select>
                          </div>

                          <div id="reccurence_container" style="display: none;">

                           <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Maintenance Time:</legend>
                              <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Start Time <span class="error">*</span></label>

                                  <div class="input-group date"  data-target-input="nearest">
                                    <input type="text" name="start_time" id="start_time" class="form-control datetimepicker-input" data-target="#start_time"/>
                                    <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    </div>
                                    <div id="start_time_error_holder"></div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>End Time <span class="error">*</span></label>

                                  <div class="input-group date"  data-target-input="nearest">
                                    <input type="text" name="end_time" id="end_time" class="form-control datetimepicker-input" data-target="#end_time"/>
                                    <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                    </div>
                                    <div id="end_time_error_holder"></div>
                                </div>
                              </div>
                              </div>
                           </fieldset>

                           <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Reccurence Pattern:</legend>
                            <div class="row">
                            <div class="col-md-2" style="border-right: 1px groove green;">
                              <div class="list-group" id="list-tab" role="tablist">
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="daily" checked name="interval_type" class="custom-control-input" value="daily" data-reccure_every_text='Days' data-target="#scheduleDaily">
                                  <label class="custom-control-label" for="daily">Daily</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="weekly" value="weekly" data-reccure_every_text='Weeks On:' name="interval_type" class="custom-control-input" data-target="#scheduleWeekly">
                                  <label class="custom-control-label" for="weekly">Weekly</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="monthly" value="monthly" data-reccure_every_text='Months' name="interval_type" class="custom-control-input" data-target="#scheduleMonthly">
                                  <label class="custom-control-label" for="monthly">Monthy</label>
                                </div>
                                <div class="custom-control custom-radio">
                                  <input type="radio" id="yearly" value="yearly" data-reccure_every_text='Years' name="interval_type" class="custom-control-input" data-target="#scheduleYearly">
                                  <label class="custom-control-label" for="yearly">Yealy</label>
                                </div>

                              </div>
                            </div>
                            <div class="col-md-10">

                              <div class="row mb-1">
                                <div class="col-2">
                                  Reccur Every
                                </div>
                                <div class="col-3">
                                  <input type="number" class="form-control" value="1" min="1" step="1" name="reccure_every" id="reccure_every">
                                </div>
                                <div class="col-2">
                                   <span id="reccure_every_text">Days</span>
                                </div>
                              </div>

                              <div class="tab-content">
                                  <div id="scheduleDaily" class="tab-pane active"></div>
                                  <div id="scheduleWeekly" class="tab-pane pt-2">
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox" name="weekly_days[]" id="inlineCheckboxSunday" value="Sunday">
                                      <label class="form-check-label" for="inlineCheckboxSunday">Sunday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxMonday" value="Monday">
                                      <label class="form-check-label" for="inlineCheckboxMonday">Monday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxTuesday" value="Tuesday" >
                                      <label class="form-check-label" for="inlineCheckboxTuesday">Tuesday</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxWednesday" value="Wednesday" >
                                      <label class="form-check-label" for="inlineCheckboxWednesday">Wednesday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxThurseday" value="Thurseday" >
                                      <label class="form-check-label" for="inlineCheckboxThurseday">Thurseday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxFriday" value="Friday" >
                                      <label class="form-check-label" for="inlineCheckboxFriday">Friday</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxSaturday" value="Saturday" >
                                      <label class="form-check-label" for="inlineCheckboxSaturday">Saturday</label>
                                    </div>

                                    <div id="weekly_days_error_holder"></div>
                                  </div>
                                  <div id="scheduleMonthly" class="tab-pane">
                                    <div class="row mb-1">
                                      <div class="col-2">
                                        <input type="radio" checked name="on_or_on_the_m" id="on_or_on_the_m_1" value="on">
                                        On
                                      </div>
                                      <div class="col">
                                        <input class="form-control" value="1" type="number" min="1" max="30" name="day_number_m" id="day_number_m">
                                      </div>
                                      <div class="col">
                                        day of every <span id="reccure_every_month_no">1</span> month(s) 
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-2">
                                        <input type="radio" name="on_or_on_the_m" id="on_or_on_the_m_2" value="on_the">
                                        On the
                                      </div>
                                      <div class="col">
                                        <select class="form-control" id="ordinal_m" name="ordinal_m">
                                          <option value="first">First</option>
                                          <option value="second">Second</option>
                                          <option value="third">Third</option>
                                          <option value="fourth">Fourth</option>
                                          <!-- <option value="fifth">Fifth</option> -->
                                        </select>
                                      </div>

                                      <div class="col">
                                      <select class="form-control" name="week_day_name_m" id="week_day_name_m">
                                        <option value="Sunday">Sunday</option>
                                        <option value="Monday">Monday</option>
                                        <option value="3">Tuesday</option>
                                        <option value="Tuesday">Wednesday</option>
                                        <option value="Thurseday">Thurseday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                      </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div id="scheduleYearly" class="tab-pane pt-2">
                                    <div class="row mb-1">
                                      <div class="col-2">
                                        <input type="radio" checked name="on_or_on_the_y" id="on_or_on_the_y_1" value="on">
                                        On
                                      </div>
                                      <div class="col"> 
                                        <select class="form-control" name="month_name_y1" id="month_name_y1">
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                      </select>
                                      </div>
                                      <div class="col">
                                        <input class="form-control" type="number" value="1" min="1" max="31" name="day_number_y" id="day_number_y">
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-2">
                                        <input type="radio" name="on_or_on_the_y" id="on_or_on_the_y_2" value="on_the">
                                        On the
                                      </div>
                                      <div class="col">
                                      <select class="form-control" name="ordinal_y" id="ordinal_y">
                                        <option value="first">First</option>
                                        <option value="second">Second</option>
                                        <option value="third">Third</option>
                                        <option value="fourth">Fourth</option>
                                        <!-- <option value="fifth">Fifth</option> -->
                                      </select>
                                      </div>

                                      <div class="col">
                                      <select class="form-control" name="week_day_name_y" id="week_day_name_y">
                                        <option value="Sunday">Sunday</option>
                                        <option value="Monday">Monday</option>
                                        <option value="3">Tuesday</option>
                                        <option value="Tuesday">Wednesday</option>
                                        <option value="Thurseday">Thurseday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                      </select>
                                      </div>
                                      <div class="col-1">
                                        Of
                                      </div>
                                      <div class="col"> 
                                        <select class="form-control" name="month_name_y2" id="month_name_y2">
                                        <option value="January">January</option>
                                        <option value="February">February</option>
                                        <option value="March">March</option>
                                        <option value="April">April</option>
                                        <option value="May">May</option>
                                        <option value="June">June</option>
                                        <option value="July">July</option>
                                        <option value="August">August</option>
                                        <option value="September">September</option>
                                        <option value="October">October</option>
                                        <option value="November">November</option>
                                        <option value="December">December</option>
                                      </select>
                                      </div>
                                    </div>


                                  </div>
                              </div>
                            </div>
                            </div>

                           </fieldset>

                           <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Range of recurrence:</legend>
                              <div class="row">
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-3">
                                    Start Date :
                                  </div>
                                  <div class="col">
                                    <input type="text" class="form-control" value="{{old('start_date')?old('start_date'):''}}" autocomplete="off" name="start_date" id="start_date"  placeholder="Start Date">  
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="row">
                                  <div class="col-3">
                                    <input type="radio" name="end_by_or_after" id="end_after" value="end_after">End After :
                                  </div>
                                  <div class="col-4">
                                    <input type="number" class="form-control" value="{{old('no_of_occurrences')?old('no_of_occurrences'):'1'}}"  step="1" autocomplete="off" name="no_of_occurrences" id="no_of_occurrences"  placeholder="No of occurrences">   
                                  </div>
                                  <div class="col">Occurences</div>

                                </div>
                                <div class="row mt-1">
                                  <div class="col-3">
                                    <input type="radio" checked name="end_by_or_after" id="end_by" value="end_by">End By :
                                  </div>
                                  <div class="col">
                                    <input type="text" class="form-control" value="{{old('end_date')?old('end_date'):''}}" autocomplete="off" name="end_date" id="end_date"  placeholder="End Date">  
                                  </div>
                                </div>

                              </div>
                              </div>
                           </fieldset>
                          </div>

                          <div class="form-group required" id="number_of_time_can_used_holder" style="display: none;">
                            <label for="number_of_time_can_used">Number of times can use</label>
                            <input type="text" aria-describedby="numberOfTimesHelp" class="form-control" value="{{old('number_of_time_can_used')?old('number_of_time_can_used'):''}}" name="number_of_time_can_used" id="number_of_time_can_used"  placeholder="Number of times">
                            <small id="numberOfTimesHelp" class="form-text text-muted">You can set the limitation of usage. Leave blank if no limitation.</small>
                            @if($errors->has('number_of_time_can_used'))
                            <span class="text-danger">{{$errors->first('number_of_time_can_used')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="service_price">Service Price ({{Helper::getSiteCurrency()}})<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('service_price')?old('service_price'):''}}" name="service_price" id="service_price"  placeholder="Service Price">
                            @if($errors->has('service_price'))
                            <span class="text-danger">{{$errors->first('service_price')}}</span>
                            @endif
                          </div>
                          <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="note" id="note" class="form-control"></textarea>
                            @if($errors->has('note'))
                            <span class="text-danger">{{$errors->first('note')}}</span>
                            @endif
                          </div>
                          <button class="btn btn-success">Add Service</button>
                        </div>
                        <hr class="mt-5 mb-2">
                        <div>
                           <a href="{{route('admin.contracts.edit',$contract->id)}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Previous</a>
                           <a href="{{route('admin.contracts.payment_info',$contract->id)}}" class="btn btn-success">Next&nbsp;<i class="fas fa-forward"></i></a> 
                        </div>
                      </form>
                    </div>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </section>
</div>
@include('admin.contracts.modals.contract_service_details_modal')
@endsection

@push('custom-scripts')
<script type="text/javascript" src="{{asset('js/admin/contracts/services.js')}}"></script>
@endpush
