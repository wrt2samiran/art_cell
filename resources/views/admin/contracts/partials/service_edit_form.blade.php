<form action="{{route('admin.contracts.services.update',[$contract->id,$contract_service->id])}}"  method="post" id="update_service_form">
  @csrf
  @method('PUT')
  <div> 
    <h6>{{__('contract_manage_module.edit_service')}}</h6>
    <div class="form-group required">
      <label for="services">{{__('contract_manage_module.labels.service')}}<span class="error">*</span></label>
      <select class="form-control " name="service" id="service" style="width: 100%;">
        <option value="">{{__('contract_manage_module.placeholders.service')}}</option>
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
        <label for="service_type">{{__('contract_manage_module.labels.service_type')}} <span class="error">*</span></label>
        <select class="form-control " {{($contract_service->service_type=='Maintenance')?'disabled':''}} name="service_type" id="service_type" style="width: 100%;">
           <option value="">{{__('contract_manage_module.placeholders.service_type')}}</option>
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
      <legend class="scheduler-border">{{__('contract_manage_module.reccurence_pattern')}}</legend>

      <div class="row">
      <div class="col-md-2" style="border-right: 1px groove green;">
        <div class="list-group" id="list-tab" role="tablist">
          <div class="custom-control custom-radio">
            <input type="radio" id="daily" {{($recurrence->interval_type=='daily')?'checked':''}} name="interval_type" class="custom-control-input" value="daily" data-reccure_every_text='Days' data-target="#scheduleDaily">
            <label class="custom-control-label" for="daily">{{__('general_sentence.daily')}}</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="weekly" value="weekly" {{($recurrence->interval_type=='weekly')?'checked':''}} data-reccure_every_text='Weeks On:' name="interval_type" class="custom-control-input" data-target="#scheduleWeekly">
            <label class="custom-control-label" for="weekly">{{__('general_sentence.weekly')}}</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="monthly" value="monthly" {{($recurrence->interval_type=='monthly')?'checked':''}} data-reccure_every_text='Months' name="interval_type" class="custom-control-input" data-target="#scheduleMonthly">
            <label class="custom-control-label" for="monthly">{{__('general_sentence.monthly')}}</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="yearly" value="yearly" {{($recurrence->interval_type=='yearly')?'checked':''}} data-reccure_every_text='Years' name="interval_type" class="custom-control-input" data-target="#scheduleYearly">
            <label class="custom-control-label" for="yearly">{{__('general_sentence.yearly')}}</label>
          </div>

        </div>
        @if($errors->has('interval_type'))
        <div><span class="text-danger">{{$errors->first('interval_type')}}</span></div>
        @endif
      </div>
      <div class="col-md-10">

        <div class="row mb-1">
          <div class="col-2">
            {{__('contract_manage_module.reccur_every')}}
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
              {{__('general_sentence.years')}}
              @elseif($recurrence->interval_type=='monthly')
              {{__('general_sentence.months')}}
              @elseif($recurrence->interval_type=='weekly')
              {{__('general_sentence.weeks')}}
              @else
              {{__('general_sentence.days')}}
              @endif
             </span>
          </div>
        </div>

        <div class="tab-content">
            <div id="scheduleDaily" class="tab-pane {{($recurrence->interval_type=='daily')?'active':''}}">
              <div class="row mb-1">
                <div class="col-3">
                  <input type="number" value="{{($recurrence->interval_type=='daily' && $recurrence->number_of_times)?$recurrence->number_of_times:'1'}}" class="form-control" value="1" min="1" step="1" name="number_of_times" id="number_of_times">
                  @if($errors->has('number_of_times'))
                  <div><span class="text-danger">{{$errors->first('number_of_times')}}</span></div>
                  @endif
                </div>
                <div class="col-2">
                  {{__('contract_manage_module.number_of_times')}}
                </div>
              </div>
            </div>
            <div id="scheduleWeekly" class="tab-pane pt-2 {{($recurrence->interval_type=='weekly')?'active':''}}">
              @php
              if($recurrence->interval_type=='weekly' && $recurrence->weekly_days){
                $weekly_days_array=explode(',',$recurrence->weekly_days);
              }else{
                $weekly_days_array=[];
              }
              @endphp


              @foreach($week_days=__('general_sentence.week_days') as $key=>$week_day)

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="weekly_days[]" id="inlineCheckbox{{$key}}" value="{{$key}}" {{(in_array($key,$weekly_days_array))?'checked':''}} >
                <label class="form-check-label" for="inlineCheckboxSunday">{{$week_day}}</label>
              </div>

              @endforeach


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
                  {{__('contract_manage_module.on')}}
                </div>
                <div class="col">
                  <input class="form-control" value="{{($recurrence->interval_type=='monthly' &&  $recurrence->on_or_on_the=='on' && $recurrence->day_number)?$recurrence->day_number:'1'}}" type="number" min="1" max="30" name="day_number_m" id="day_number_m">

                  @if($errors->has('day_number_m'))
                  <div><span class="text-danger">{{$errors->first('day_number_m')}}</span></div>
                  @endif
                </div>
                <div class="col">
                  {{__('contract_manage_module.day_of_every')}} <span id="reccure_every_month_no">1</span> {{__('general_sentence.months')}} 
                </div>
              </div>
              <div class="row">
                <div class="col-2">
                  <input type="radio"
                  {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the')?'checked':''}}
                  name="on_or_on_the_m"
                  id="on_or_on_the_m_2"
                  value="on_the">
                  {{__('contract_manage_module.on_the')}}
                </div>
                <div class="col">
                  <select class="form-control" id="ordinal_m" name="ordinal_m">
                    <option value="first" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='first')?'selected':''}} >{{__('general_sentence.first')}}</option>
                    <option  value="second" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='second')?'selected':''}}>{{__('general_sentence.second')}}</option>
                    <option value="third" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='third')?'selected':''}}>{{__('general_sentence.third')}}</option>
                    <option value="fourth" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='fourth')?'selected':''}}>{{__('general_sentence.fourth')}}</option>
                    <!-- <option value="fifth">Fifth</option> -->
                  </select>
                  @if($errors->has('ordinal_m'))
                  <div><span class="text-danger">{{$errors->first('ordinal_m')}}</span></div>
                  @endif
                </div>

                <div class="col">
                <select class="form-control" name="week_day_name_m" id="week_day_name_m">


                  @foreach($week_days=__('general_sentence.week_days') as $key=>$week_day)
                  
                  <option value="{{$key}}" {{($recurrence->interval_type=='monthly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name==$key)?'selected':''}}>{{$week_day}}</option>

                  @endforeach

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
                  {{__('contract_manage_module.on')}}
                </div>
                <div class="col"> 
                <select class="form-control" name="month_name_y1" id="month_name_y1">

                  @foreach($months=__('general_sentence.months_array') as $key=>$month)
                
                    <option value="{{$key}}" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on' && $recurrence->month_name==$key)?'selected':''}}>{{$month}}</option>
                  @endforeach

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
                  {{__('contract_manage_module.on_the')}}
                </div>
                <div class="col">
                <select class="form-control" name="ordinal_y" id="ordinal_y">
                    <option value="first" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='first')?'selected':''}} >{{__('general_sentence.first')}}</option>
                    <option  value="second" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='second')?'selected':''}}>{{__('general_sentence.second')}}</option>
                    <option value="third" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='third')?'selected':''}}>{{__('general_sentence.third')}}</option>
                    <option value="fourth" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->ordinal=='fourth')?'selected':''}}>{{__('general_sentence.fourth')}}</option>
                  <!-- <option value="fifth">Fifth</option> -->
                </select>
                  @if($errors->has('ordinal_y'))
                  <div><span class="text-danger">{{$errors->first('ordinal_y')}}</span></div>
                  @endif
                </div>

                <div class="col">
                <select class="form-control" name="week_day_name_y" id="week_day_name_y">


                  @foreach($week_days=__('general_sentence.week_days') as $key=>$week_day)
                  
                  <option value="{{$key}}" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->week_day_name==$key)?'selected':''}}>
                  {{$week_day}}
                  </option>
                  @endforeach

                </select>
                  @if($errors->has('week_day_name_y'))
                  <div><span class="text-danger">{{$errors->first('week_day_name_y')}}</span></div>
                  @endif
                </div>
                <div class="col-1">
                  {{__('contract_manage_module.of')}}
                </div>
                <div class="col"> 
                <select class="form-control" name="month_name_y2" id="month_name_y2">

                  @foreach($months=__('general_sentence.months_array') as $key=>$month)
                   
                    <option value="{{$key}}" {{($recurrence->interval_type=='yearly' && $recurrence->on_or_on_the=='on_the' && $recurrence->month_name==$key)?'selected':''}}>{{$month}}</option>
                  @endforeach

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
      <legend class="scheduler-border">{{__('contract_manage_module.range_of_recurrence')}}</legend>
        <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="col-3">
              {{__('contract_manage_module.labels.start_date')}}
            </div>
            <div class="col">
              <input type="text" class="form-control" value="{{old('start_date')?old('start_date'):Carbon::parse($recurrence->start_date)->format('d/m/Y')}}" autocomplete="off" name="start_date" readonly="readonly" id="start_date"  placeholder="{{__('contract_manage_module.placeholders.start_date')}}">
              @if($errors->has('start_date'))
              <div><span class="text-danger">{{$errors->first('start_date')}}</span></div>
              @endif
            </div>
          </div>
        </div>


        <div class="col-md-6">

          <div class="row ">
            <div class="col-3">
              <input type="radio" {{($recurrence->end_by_or_after=='end_by')?'checked':''}} name="end_by_or_after" id="end_by" value="end_by">{{__('contract_manage_module.end_by')}}
            </div>
            <div class="col">
              <input type="text" class="form-control" readonly="readonly" value="{{($recurrence->end_by_or_after=='end_by')?Carbon::parse($recurrence->end_date)->format('d/m/Y'):''}}" autocomplete="off" name="end_date" id="end_date"  placeholder="{{__('contract_manage_module.placeholders.end_date')}}">  
              @if($errors->has('end_date'))
              <div><span class="text-danger">{{$errors->first('end_date')}}</span></div>
              @endif 
            </div>
          </div>
          <div class="row mt-1">
            <div class="col-3">
              <input type="radio" name="end_by_or_after" {{($recurrence->end_by_or_after=='end_after')?'checked':''}} id="end_after" value="end_after">{{__('contract_manage_module.end_after')}}
            </div>
            <div class="col-4">
              <input type="number" class="form-control" value="{{($recurrence->end_by_or_after=='end_after')?$recurrence->no_of_occurrences:'1'}}"  step="1" min="1" autocomplete="off" name="no_of_occurrences" id="no_of_occurrences"  placeholder="{{__('contract_manage_module.placeholders.no_of_occurrences')}}">  

              @if($errors->has('no_of_occurrences'))
              <div><span class="text-danger">{{$errors->first('no_of_occurrences')}}</span></div>
              @endif 
            </div>
            <div class="col">{{__('contract_manage_module.occurences')}}</div>

          </div>

        </div>


        </div>
     </fieldset>
    </div>
@endif


    <div class="form-group required" id="number_of_time_can_used_holder" style="display: {{($contract_service->service_type=='On Demand')?'block':'none'}};">
      <label for="number_of_time_can_used">{{__('contract_manage_module.labels.no_of_time_can_use')}}</label>
      <input type="text" aria-describedby="numberOfTimesHelp" class="form-control" value="{{old('number_of_time_can_used')?old('number_of_time_can_used'):$contract_service->number_of_time_can_used}}" name="number_of_time_can_used" id="number_of_time_can_used"  placeholder="{{__('contract_manage_module.placeholders.no_of_time_can_use')}}">
      <small id="numberOfTimesHelp" class="form-text text-muted">{{__('contract_manage_module.no_of_time_can_use_help_text')}}</small>
      @if($errors->has('number_of_time_can_used'))
      <span class="text-danger">{{$errors->first('number_of_time_can_used')}}</span>
      @endif
    </div>

    <div class="form-group required">
      <label for="service_price">{{__('contract_manage_module.labels.service_price')}} ({{Helper::getSiteCurrency()}})<span class="error">*</span></label>
      <input type="text" class="form-control" value="{{old('service_price')?old('service_price'):$contract_service->price}}" name="service_price" id="service_price"  placeholder="{{__('contract_manage_module.placeholders.service_price')}}">
      @if($errors->has('service_price'))
      <span class="text-danger">{{$errors->first('service_price')}}</span>
      @endif
    </div>
    <div class="form-group">
      <label for="note">{{__('contract_manage_module.labels.note')}}</label>
      <textarea name="note" id="note" class="form-control" placeholder="{{__('contract_manage_module.placeholders.note')}}">{!! $contract_service->note !!}</textarea>
      @if($errors->has('note'))
      <span class="text-danger">{{$errors->first('note')}}</span>
      @endif
    </div>
    <button class="btn btn-success">{{__('general_sentence.button_and_links.update_service')}}</button>
  </div>
  <hr class="mt-5 mb-2">
  <div>
     <a href="{{route('admin.contracts.edit',$contract->id)}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.previous')}}</a>
     <a href="{{route('admin.contracts.payment_info',$contract->id)}}" class="btn btn-success">{{__('general_sentence.button_and_links.next')}}&nbsp;<i class="fas fa-forward"></i></a> 
  </div>
</form>