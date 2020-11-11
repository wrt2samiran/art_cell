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
              <li class="breadcrumb-item active">Create</li>
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
                <h3 class="card-title">Create Contract</h3>
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
                          <div class="form-group required" id="number_of_time_can_used_holder" style="display: none;">
                            <label for="number_of_time_can_used">Number of times can use</label>
                            <input type="text" aria-describedby="numberOfTimesHelp" class="form-control" value="{{old('number_of_time_can_used')?old('number_of_time_can_used'):''}}" name="number_of_time_can_used" id="number_of_time_can_used"  placeholder="Number of times">
                            <small id="numberOfTimesHelp" class="form-text text-muted">You can set the limitation of usage. Leave blank if no limitation.</small>
                            @if($errors->has('number_of_time_can_used'))
                            <span class="text-danger">{{$errors->first('number_of_time_can_used')}}</span>
                            @endif
                          </div>
                          <div class="form-group required" id="frequency_number_holder" style="display: none;">
                            <label for="frequency_number">Number of frequency </label>
                            <input type="number" min="1" step="1"  class="form-control" value="{{old('frequency_number')?old('frequency_number'):''}}" name="frequency_number" id="frequency_number"  placeholder="Number of frequency">
                            
                            @if($errors->has('frequency_number'))
                            <span class="text-danger">{{$errors->first('frequency_number')}}</span>
                            @endif
                          </div>
                          <div class="row" id="date_time_row" style="display: none;">
                            <div class="col-md-4">
                              <div class="form-group required">
                                <label for="start_date">Start Date<span class="error">*</span></label>
                                <input autocomplete="off" type="text" name="start_date" class="form-control datepicker" value="" id="start_date"  placeholder="Start Date">

                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Start Time <span class="error">*</span></label>

                                <div class="input-group date" id="start_time" data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input" data-target="#start_time"/>
                                  <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="far fa-clock"></i></div>
                                  </div>
                                  </div>
                          
                              </div>
                            </div>
                            <div class="col-md-4">

                              <div class="form-group">
                                <label>End Time <span class="error">*</span></label>

                                <div class="input-group date" id="end_time" data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input" data-target="#end_time"/>
                                  <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i class="far fa-clock"></i></div>
                                  </div>
                                  </div>
                          
                              </div>

                            </div>
                            <div class="col-md-12" style="display: none;" id="weekly_day_container">
                            <div class="form-group required">
                              <label for="weekly_day">Select Day <span class="error">*</span></label>
                              <select name="weekly_day" id="weekly_day" class="form-control">
                                <option>Sunday</option>
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thurseday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                              </select>
                            </div>
                              
                            </div>
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
