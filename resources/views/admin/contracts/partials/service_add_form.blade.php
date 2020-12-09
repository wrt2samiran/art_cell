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
      @if($errors->has('service'))
      <span class="text-danger">{{$errors->first('service')}}</span>
      @endif
    </div>
    <div class="form-group required">
        <label for="service_type">Service Type <span class="error">*</span></label>
        <select class="form-control " name="service_type" id="service_type" style="width: 100%;">
           <option value="">Select service type</option>
           <option value="Maintenance">Maintenance</option> 
           <option value="On Demand">On Demand</option>  
           <option value="Free">Free</option>    
        </select>
        @if($errors->has('service_type'))
        <span class="text-danger">{{$errors->first('service_type')}}</span>
        @endif
    </div>

    <div id="reccurence_container" style="display: none;">


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
        @if($errors->has('interval_type'))
        <div><span class="text-danger">{{$errors->first('interval_type')}}</span></div>
        @endif
      </div>
      <div class="col-md-10">

        <div class="row mb-1">
          <div class="col-2">
            Reccur Every
          </div>
          <div class="col-3">
            <input type="number" class="form-control" value="1" min="1" step="1" name="reccure_every" id="reccure_every">
            @if($errors->has('reccure_every'))
            <div><span class="text-danger">{{$errors->first('reccure_every')}}</span></div>
            @endif
          </div>
          <div class="col-2">
             <span id="reccure_every_text">Days</span>
          </div>
        </div>

        <div class="tab-content">
            <div id="scheduleDaily" class="tab-pane active">
            <div class="row mb-1">
              <div class="col-3">
                <input type="number" class="form-control" value="1" min="1" step="1" name="number_of_times" id="number_of_times">
                @if($errors->has('number_of_times'))
                <div><span class="text-danger">{{$errors->first('number_of_times')}}</span></div>
                @endif
              </div>
              <div class="col-2">
                Number of times
              </div>
            </div>
            </div>
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
              @if($errors->has('weekly_days'))
              <div><span class="text-danger">{{$errors->first('weekly_days')}}</span></div>
              @endif
            </div>
            <div id="scheduleMonthly" class="tab-pane">
              <div class="row mb-1">
                <div class="col-2">
                  <input type="radio" checked name="on_or_on_the_m" id="on_or_on_the_m_1" value="on">
                  On
                </div>
                <div class="col">
                  <input class="form-control" value="1" type="number" min="1" max="30" name="day_number_m" id="day_number_m">

                  @if($errors->has('day_number_m'))
                  <div><span class="text-danger">{{$errors->first('day_number_m')}}</span></div>
                  @endif
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
                  @if($errors->has('ordinal_m'))
                  <div><span class="text-danger">{{$errors->first('ordinal_m')}}</span></div>
                  @endif
                </div>

                <div class="col">
                <select class="form-control" name="week_day_name_m" id="week_day_name_m">
                  <option value="Sunday">Sunday</option>
                  <option value="Monday">Monday</option>
                  <option value="Tuesday">Tuesday</option>
                  <option value="Wednesday">Wednesday</option>
                  <option value="Thurseday">Thurseday</option>
                  <option value="Friday">Friday</option>
                  <option value="Saturday">Saturday</option>
                </select>

                  @if($errors->has('week_day_name_m'))
                  <div><span class="text-danger">{{$errors->first('week_day_name_m')}}</span></div>
                  @endif
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

                  @if($errors->has('month_name_y1'))
                  <div><span class="text-danger">{{$errors->first('month_name_y1')}}</span></div>
                  @endif
                </div>
                <div class="col">
                  <input class="form-control" type="number" value="1" min="1" max="31" name="day_number_y" id="day_number_y">
                  @if($errors->has('day_number_y'))
                  <div><span class="text-danger">{{$errors->first('day_number_y')}}</span></div>
                  @endif
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
                  @if($errors->has('ordinal_y'))
                  <div><span class="text-danger">{{$errors->first('ordinal_y')}}</span></div>
                  @endif
                </div>

                <div class="col">
                <select class="form-control" name="week_day_name_y" id="week_day_name_y">
                  <option value="Sunday">Sunday</option>
                  <option value="Monday">Monday</option>
                  <option value="Tuesday">Tuesday</option>
                  <option value="Wednesday">Wednesday</option>
                  <option value="Thurseday">Thurseday</option>
                  <option value="Friday">Friday</option>
                  <option value="Saturday">Saturday</option>
                </select>
                  @if($errors->has('week_day_name_y'))
                  <div><span class="text-danger">{{$errors->first('week_day_name_y')}}</span></div>
                  @endif
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

                  @if($errors->has('month_name_y2'))
                  <div><span class="text-danger">{{$errors->first('month_name_y2')}}</span></div>
                  @endif
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
              <input type="text" class="form-control" value="{{old('start_date')?old('start_date'):Carbon::parse($contract->start_date)->format('d/m/Y')}}" autocomplete="off" readonly="readonly" name="start_date" id="start_date"  placeholder="Start Date">
              @if($errors->has('start_date'))
              <div><span class="text-danger">{{$errors->first('start_date')}}</span></div>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-6">

          <div class="row ">
            <div class="col-3">
              <input type="radio" checked name="end_by_or_after" id="end_by" value="end_by">End By :
            </div>
            <div class="col">
              <input type="text" class="form-control" readonly="readonly" value="{{old('end_date')?old('end_date'):''}}" autocomplete="off" name="end_date" id="end_date"  placeholder="End Date">  
              @if($errors->has('end_date'))
              <div><span class="text-danger">{{$errors->first('end_date')}}</span></div>
              @endif 
            </div>
          </div>
          <div class="row mt-1">
            <div class="col-3">
              <input type="radio" name="end_by_or_after" id="end_after" value="end_after">End After :
            </div>
            <div class="col-4">
              <input type="number" class="form-control" value="{{old('no_of_occurrences')?old('no_of_occurrences'):'1'}}"  step="1" min="1" autocomplete="off" name="no_of_occurrences" id="no_of_occurrences"  placeholder="No of occurrences">  

              @if($errors->has('no_of_occurrences'))
              <div><span class="text-danger">{{$errors->first('no_of_occurrences')}}</span></div>
              @endif 
            </div>
            <div class="col">Occurences</div>

          </div>

        </div>
        </div>
     </fieldset>

    </div>

    <div class="form-group required">
      <label for="service_price">Service Price ({{Helper::getSiteCurrency()}})<span class="error">*</span></label>
      <input type="text" class="form-control" value="{{old('service_price')?old('service_price'):''}}" name="service_price" id="service_price"  placeholder="Service Price">
      @if($errors->has('service_price'))
      <span class="text-danger">{{$errors->first('service_price')}}</span>
      @endif
    </div>

    <div class="form-group" id="consider_as_on_demand_holder" style="display: none;">
      <label for="consider_as_on_demand" class="">Do you want to consider it as On-Demand Service ?</label><br>
      <input type="checkbox" id="consider_as_on_demand" name="consider_as_on_demand" value="1">
    </div> 
    <div class="form-group required" id="number_of_time_can_used_holder" style="display: none;">
      <label for="number_of_time_can_used">Number of times can use</label>
      <input type="text" aria-describedby="numberOfTimesHelp" class="form-control" value="{{old('number_of_time_can_used')?old('number_of_time_can_used'):''}}" name="number_of_time_can_used" id="number_of_time_can_used"  placeholder="Number of times">
      <small id="numberOfTimesHelp" class="form-text text-muted">You can set the limitation of usage. Leave blank if no limitation.</small>
      @if($errors->has('number_of_time_can_used'))
      <span class="text-danger">{{$errors->first('number_of_time_can_used')}}</span>
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