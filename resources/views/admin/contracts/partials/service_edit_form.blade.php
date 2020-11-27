<form action="{{route('admin.contracts.services.update',[$contract->id,$contract_service->id])}}"  method="post" id="update_service_form">
  @csrf
  @method('PUT')
  <div> 
    <h6>Edit Service</h6>
    <div class="form-group required">
      <label for="services">Select service<span class="error">*</span></label>
      <select class="form-control " name="service" id="service" style="width: 100%;">
        <option value="">Select service</option>
        @forelse($services as $service)
           <option {{($contract_service->service_id==$service->id)?'selected':''}} data-service_price="{{$service->price}}" data-service_name="{{$service->service_name}}" value="{{$service->id}}">{{$service->service_name}} ({{Helper::getSiteCurrency()}} {{$service->price}}) </option>
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
        <select class="form-control " {{($contract_service->service_type=='Maintenance')?'disabled':''}} name="service_type" id="service_type" style="width: 100%;">
           <option value="">Select service type</option>
           <option {{($contract_service->service_type=='Maintenance')?'selected':''}} value="Maintenance" disabled>Maintenance</option> 
           <option {{($contract_service->service_type=='On Demand')?'selected':''}} value="On Demand">On Demand</option>  
           <option {{($contract_service->service_type=='Free')?'selected':''}} value="Free">Free</option>    
        </select>
        @if($errors->has('service_type'))
        <span class="text-danger">{{$errors->first('service_type')}}</span>
        @endif
    </div>

@if($contract_service->service_type=='Maintenance')
    @php
    $recurrence=$contract_service->recurrence_details;
    @endphp
    <div id="reccurence_container" >
     <fieldset class="scheduler-border">
      <legend class="scheduler-border">Maintenance Time:</legend>
        <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Start Time <span class="error">*</span></label>

            <div class="input-group date"  data-target-input="nearest">
              <input type="text"  name="start_time" id="start_time" class="form-control datetimepicker-input" data-target="#start_time"/>
              <div class="input-group-append" data-target="#start_time" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
              </div>
              </div>
              <div id="start_time_error_holder"></div>
              @if($errors->has('start_time'))
              <div><span class="text-danger">{{$errors->first('start_time')}}</span></div>
              @endif
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>End Time <span class="error">*</span></label>

            <div class="input-group date"  data-target-input="nearest">
              <input type="text"   name="end_time" id="end_time" class="form-control datetimepicker-input" data-target="#end_time"/>
              <div class="input-group-append" data-target="#end_time" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="far fa-clock"></i></div>
              </div>
              </div>
              <div id="end_time_error_holder"></div>
              @if($errors->has('end_time'))
              <div><span class="text-danger">{{$errors->first('end_time')}}</span></div>
              @endif
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
            <input type="radio" id="daily" {{($recurrence->interval_type=='daily')?'checked':''}} name="interval_type" class="custom-control-input" value="daily" data-reccure_every_text='Days' data-target="#scheduleDaily">
            <label class="custom-control-label" for="daily">Daily</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="weekly" value="weekly" {{($recurrence->interval_type=='weekly')?'checked':''}} data-reccure_every_text='Weeks On:' name="interval_type" class="custom-control-input" data-target="#scheduleWeekly">
            <label class="custom-control-label" for="weekly">Weekly</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="monthly" value="monthly" {{($recurrence->interval_type=='monthly')?'checked':''}} data-reccure_every_text='Months' name="interval_type" class="custom-control-input" data-target="#scheduleMonthly">
            <label class="custom-control-label" for="monthly">Monthy</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="yearly" value="yearly" {{($recurrence->interval_type=='yearly')?'checked':''}} data-reccure_every_text='Years' name="interval_type" class="custom-control-input" data-target="#scheduleYearly">
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
            <input type="number" class="form-control" value="{{$recurrence->reccure_every}}" min="1" step="1" name="reccure_every" id="reccure_every">
            @if($errors->has('reccure_every'))
            <div><span class="text-danger">{{$errors->first('reccure_every')}}</span></div>
            @endif
          </div>
          <div class="col-2">
             <span id="reccure_every_text">
              @if($recurrence->interval_type=='yearly')
              Year(s)
              @elseif($recurrence->interval_type=='monthly')
              Month(s)
              @elseif($recurrence->interval_type=='weekly')
              Week(s)
              @else
              Day(s)
              @endif
             </span>
          </div>
        </div>

        <div class="tab-content">
            <div id="scheduleDaily" class="tab-pane {{($recurrence->interval_type=='daily')?'active':''}}"></div>
            <div id="scheduleWeekly" class="tab-pane pt-2 {{($recurrence->interval_type=='weekly')?'active':''}}">
              @php
              if($recurrence->interval_type=='weekly' && $recurrence->weekly_days){
                $weekly_days_array=explode(',',$recurrence->weekly_days);
              }else{
                $weekly_days_array=[];
              }
              @endphp
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="weekly_days[]" id="inlineCheckboxSunday" value="Sunday" {{(in_array('Sunday',$weekly_days_array))?'checked':''}}>
                <label class="form-check-label" for="inlineCheckboxSunday">Sunday</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxMonday" value="Monday" {{(in_array('Sunday',$weekly_days_array))?'checked':''}}>
                <label class="form-check-label" for="inlineCheckboxMonday">Monday</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxTuesday" value="Tuesday" {{(in_array('Tuesday',$weekly_days_array))?'checked':''}}>
                <label class="form-check-label" for="inlineCheckboxTuesday">Tuesday</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxWednesday" value="Wednesday" {{(in_array('Wednesday',$weekly_days_array))?'checked':''}}>
                <label class="form-check-label" for="inlineCheckboxWednesday">Wednesday</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxThurseday" value="Thurseday" {{(in_array('Thurseday',$weekly_days_array))?'checked':''}}>
                <label class="form-check-label" for="inlineCheckboxThurseday">Thurseday</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxFriday" value="Friday" {{(in_array('Friday',$weekly_days_array))?'checked':''}}>
                <label class="form-check-label" for="inlineCheckboxFriday">Friday</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox"  name="weekly_days[]" id="inlineCheckboxSaturday" value="Saturday" {{(in_array('Saturday',$weekly_days_array))?'checked':''}}>
                <label class="form-check-label" for="inlineCheckboxSaturday">Saturday</label>
              </div>

              <div id="weekly_days_error_holder"></div>
              @if($errors->has('weekly_days'))
              <div><span class="text-danger">{{$errors->first('weekly_days')}}</span></div>
              @endif
            </div>
            <div id="scheduleMonthly" class="tab-pane {{($recurrence->interval_type=='monthly')?'active':''}}">
              <div class="row mb-1">
                <div class="col-2">
                  <input type="radio"
                   {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on')?'checked':'checked'}}  name="on_or_on_the_m" id="on_or_on_the_m_1" value="on">
                  On
                </div>
                <div class="col">
                  <input class="form-control" value="{{($recurrence->interval_type=='monthly' &&  $recurrence->on_or_on_the=='on' && $recurrence->day_number)?$recurrence->day_number:'1'}}" type="number" min="1" max="30" name="day_number_m" id="day_number_m">

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
                  <input type="radio"
                  {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the')?'checked':''}}
                  name="on_or_on_the_m"
                  id="on_or_on_the_m_2"
                  value="on_the">
                  On the
                </div>
                <div class="col">
                  <select class="form-control" id="ordinal_m" name="ordinal_m">
                    <option value="first" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='first')?'selected':''}} >First</option>
                    <option  value="second" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='second')?'selected':''}}>Second</option>
                    <option value="third" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='third')?'selected':''}}>Third</option>
                    <option value="fourth" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='fourth')?'selected':''}}>Fourth</option>
                    <!-- <option value="fifth">Fifth</option> -->
                  </select>
                  @if($errors->has('ordinal_m'))
                  <div><span class="text-danger">{{$errors->first('ordinal_m')}}</span></div>
                  @endif
                </div>

                <div class="col">
                <select class="form-control" name="week_day_name_m" id="week_day_name_m">
                  <option value="Sunday" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Sunday')?'selected':''}}>Sunday</option>
                  <option value="Monday" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Monday')?'selected':''}}>Monday</option>
                  <option value="Tuesday" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Tuesday')?'selected':''}}>Tuesday</option>
                  <option value="Wednesday" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Wednesday')?'selected':''}}>Wednesday</option>
                  <option value="Thurseday" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Thurseday')?'selected':''}}>Thurseday</option>
                  <option value="Friday" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Friday')?'selected':''}}>Friday</option>
                  <option value="Saturday" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Saturday')?'selected':''}}>Saturday</option>
                </select>

                  @if($errors->has('week_day_name_m'))
                  <div><span class="text-danger">{{$errors->first('week_day_name_m')}}</span></div>
                  @endif
                </div>
              </div>
            </div>
            <div id="scheduleYearly" class="tab-pane pt-2 {{($recurrence->interval_type=='yearly')?'active':''}}">
              <div class="row mb-1">
                <div class="col-2">
                  <input type="radio" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on')?'checked':'checked'}} name="on_or_on_the_y" id="on_or_on_the_y_1" value="on">
                  On
                </div>
                <div class="col"> 
                <select class="form-control" name="month_name_y1" id="month_name_y1">
                  <option value="January" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='January')?'selected':''}}>January</option>
                  <option value="February" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='February')?'selected':''}}>February</option>
                  <option value="March" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='March')?'selected':''}}>March</option>
                  <option value="April" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='April')?'selected':''}}>April</option>
                  <option value="May" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='May')?'selected':''}}>May</option>
                  <option value="June" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='June')?'selected':''}}>June</option>
                  <option value="July" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='July')?'selected':''}}>July</option>
                  <option value="August" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='August')?'selected':''}}>August</option>
                  <option value="September" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='September')?'selected':''}}>September</option>
                  <option value="October" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='October')?'selected':''}}>October</option>
                  <option value="November" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='November')?'selected':''}}>November</option>
                  <option value="December" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name=='December')?'selected':''}}>December</option>
                </select>

                  @if($errors->has('month_name_y1'))
                  <div><span class="text-danger">{{$errors->first('month_name_y1')}}</span></div>
                  @endif
                </div>
                <div class="col">

                  <input class="form-control" value="{{($recurrence->interval_type=='yearly' &&  $recurrence->on_or_on_the=='on' && $recurrence->day_number)?$recurrence->day_number:'1'}}" type="number" min="1" max="30" name="day_number_y" id="day_number_y">

                  @if($errors->has('day_number_y'))
                  <div><span class="text-danger">{{$errors->first('day_number_y')}}</span></div>
                  @endif
                </div>
              </div>
              <div class="row">
                <div class="col-2">
                  <input type="radio" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the')?'checked':''}} name="on_or_on_the_y" id="on_or_on_the_y_2" value="on_the">
                  On the
                </div>
                <div class="col">
                <select class="form-control" name="ordinal_y" id="ordinal_y">
                    <option value="first" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='first')?'selected':''}} >First</option>
                    <option  value="second" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='second')?'selected':''}}>Second</option>
                    <option value="third" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='third')?'selected':''}}>Third</option>
                    <option value="fourth" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='fourth')?'selected':''}}>Fourth</option>
                  <!-- <option value="fifth">Fifth</option> -->
                </select>
                  @if($errors->has('ordinal_y'))
                  <div><span class="text-danger">{{$errors->first('ordinal_y')}}</span></div>
                  @endif
                </div>

                <div class="col">
                <select class="form-control" name="week_day_name_y" id="week_day_name_y">
                  <option value="Sunday" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Sunday')?'selected':''}}>Sunday</option>
                  <option value="Monday" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Monday')?'selected':''}}>Monday</option>
                  <option value="Tuesday" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Tuesday')?'selected':''}}>Tuesday</option>
                  <option value="Wednesday" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Wednesday')?'selected':''}}>Wednesday</option>
                  <option value="Thurseday" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Thurseday')?'selected':''}}>Thurseday</option>
                  <option value="Friday" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Friday')?'selected':''}}>Friday</option>
                  <option value="Saturday" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name=='Saturday')?'selected':''}}>Saturday</option>
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
                  <option value="January" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='January')?'selected':''}}>January</option>
                  <option value="February" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='February')?'selected':''}}>February</option>
                  <option value="March" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='March')?'selected':''}}>March</option>
                  <option value="April" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='April')?'selected':''}}>April</option>
                  <option value="May" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='May')?'selected':''}}>May</option>
                  <option value="June" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='June')?'selected':''}}>June</option>
                  <option value="July" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='July')?'selected':''}}>July</option>
                  <option value="August" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='August')?'selected':''}}>August</option>
                  <option value="September" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='September')?'selected':''}}>September</option>
                  <option value="October" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='October')?'selected':''}}>October</option>
                  <option value="November" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='November')?'selected':''}}>November</option>
                  <option value="December" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name=='December')?'selected':''}}>December</option>
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
              <input type="text" class="form-control" value="{{old('start_date')?old('start_date'):Carbon::parse($recurrence->start_date)->format('d/m/Y')}}" autocomplete="off" name="start_date" id="start_date"  placeholder="Start Date">
              @if($errors->has('start_date'))
              <div><span class="text-danger">{{$errors->first('start_date')}}</span></div>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-3">
              
              <input type="radio" name="end_by_or_after" {{($recurrence->end_by_or_after=='end_after')?'checked':''}} id="end_after" value="end_after">End After :
              
            </div>
            <div class="col-4">
              <input type="number" class="form-control" value="{{($recurrence->end_by_or_after=='end_after')?$recurrence->no_of_occurrences:'1'}}"  step="1" autocomplete="off" name="no_of_occurrences" id="no_of_occurrences"  placeholder="No of occurrences">  

              @if($errors->has('no_of_occurrences'))
              <div><span class="text-danger">{{$errors->first('no_of_occurrences')}}</span></div>
              @endif 
            </div>
            <div class="col">Occurences</div>

          </div>
          <div class="row mt-1">
            <div class="col-3">
              <input type="radio" {{($recurrence->end_by_or_after=='end_by')?'checked':''}} name="end_by_or_after" id="end_by" value="end_by">End By :
            </div>
            <div class="col">
              <input type="text" class="form-control" value="{{($recurrence->end_by_or_after=='end_by')?Carbon::parse($recurrence->end_time)->format('d/m/Y'):''}}" autocomplete="off" name="end_date" id="end_date"  placeholder="End Date">  
              @if($errors->has('end_date'))
              <div><span class="text-danger">{{$errors->first('end_date')}}</span></div>
              @endif 
            </div>
          </div>

        </div>
        </div>
     </fieldset>
    </div>
@endif


    <div class="form-group required" id="number_of_time_can_used_holder" style="display: {{($contract_service->service_type=='On Demand')?'block':'none'}};">
      <label for="number_of_time_can_used">Number of times can use</label>
      <input type="text" aria-describedby="numberOfTimesHelp" class="form-control" value="{{old('number_of_time_can_used')?old('number_of_time_can_used'):$contract_service->number_of_time_can_used}}" name="number_of_time_can_used" id="number_of_time_can_used"  placeholder="Number of times">
      <small id="numberOfTimesHelp" class="form-text text-muted">You can set the limitation of usage. Leave blank if no limitation.</small>
      @if($errors->has('number_of_time_can_used'))
      <span class="text-danger">{{$errors->first('number_of_time_can_used')}}</span>
      @endif
    </div>

    <div class="form-group required">
      <label for="service_price">Service Price ({{Helper::getSiteCurrency()}})<span class="error">*</span></label>
      <input type="text" class="form-control" value="{{old('service_price')?old('service_price'):$contract_service->price}}" name="service_price" id="service_price"  placeholder="Service Price">
      @if($errors->has('service_price'))
      <span class="text-danger">{{$errors->first('service_price')}}</span>
      @endif
    </div>
    <div class="form-group">
      <label for="note">Note</label>
      <textarea name="note" id="note" class="form-control">{!! $contract_service->note !!}</textarea>
      @if($errors->has('note'))
      <span class="text-danger">{{$errors->first('note')}}</span>
      @endif
    </div>
    <button class="btn btn-success">Update Service</button>
  </div>
  <hr class="mt-5 mb-2">
  <div>
     <a href="{{route('admin.contracts.edit',$contract->id)}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Previous</a>
     <a href="{{route('admin.contracts.payment_info',$contract->id)}}" class="btn btn-success">Next&nbsp;<i class="fas fa-forward"></i></a> 
  </div>
</form>