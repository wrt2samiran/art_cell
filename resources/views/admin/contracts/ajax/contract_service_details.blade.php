<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover table-bordered">
                <tbody>
	                <tr>
	                	<td>{{__('contract_manage_module.labels.service')}}</td>
	                	<td>
	                		{{$contract_service->service->service_name}}
	                	</td>
	                </tr>
	               	<tr>
	                	<td>{{__('contract_manage_module.labels.service_type')}}</td>
	                	<td>
	                		{{$contract_service->service_type}}
	                		@if($contract_service->service_type=='On Demand')
	                		<br>
	                		{{($contract_service->number_of_time_can_used)? $contract_service->number_of_time_can_used.' times':'' }}
	                		@endif
	                	</td>
	                </tr>
	                @if($contract_service->service_type=='Maintenance')
	               	<tr>
	                	<td>{{__('contract_manage_module.recurrence_details')}}</td>
	                	<td>
	                		<div>
	                			<span>{{__('contract_manage_module.from')}}  {{Carbon::parse($contract_service->recurrence_details->start_date)->format('d/m/Y')}}</span> {{__('contract_manage_module.to')}}  
	                			<span>
	                			@if($contract_service->recurrence_details->end_by_or_after=='end_by')
	                				{{Carbon::parse($contract_service->recurrence_details->end_date)->format('d/m/Y')}}
	                			@else
	                				{{__('contract_manage_module.after')}}  {{$contract_service->recurrence_details->no_of_occurrences}} {{__('contract_manage_module.occurences')}} 
	                			@endif
	                			</span>

	                		</div>

	                		<div>
	                			
	                			@if($contract_service->recurrence_details->interval_type=='yearly')
								<div>{{__('contract_manage_module.reccur_every')}} {{$contract_service->recurrence_details->reccure_every}} {{__('general_sentence.years')}}</div>
								<div>
									@if($contract_service->recurrence_details->on_or_on_the=='on')
									{{__('contract_manage_module.on')}} <span>{{$contract_service->recurrence_details->day_number}} {{$contract_service->recurrence_details->month_name}}</span>
									@else
									{{__('contract_manage_module.on_the')}} <span>{{$contract_service->recurrence_details->ordinal}}, {{$contract_service->recurrence_details->week_day_name}}, {{$contract_service->recurrence_details->month_name}}</span>
									@endif
								</div>
	                			@elseif($contract_service->recurrence_details->interval_type=='monthly')
								<div>{{__('contract_manage_module.reccur_every')}} {{$contract_service->recurrence_details->reccure_every}} {{__('general_sentence.months')}}</div>
								<div>
									@if($contract_service->recurrence_details->on_or_on_the=='on')
									{{__('contract_manage_module.on')}} <span>{{$contract_service->recurrence_details->day_number}} {{__('general_sentence.day')}} 
									@else
									{{__('contract_manage_module.on_the')}} <span>{{$contract_service->recurrence_details->ordinal}}, {{$contract_service->recurrence_details->week_day_name}}</span>
									@endif
									{{__('contract_manage_module.of_every')}} {{$contract_service->recurrence_details->reccure_every}} {{__('general_sentence.months')}}
								</div>

	                			@elseif($contract_service->recurrence_details->interval_type=='weekly')
								<div>{{__('contract_manage_module.reccur_every')}} {{$contract_service->recurrence_details->reccure_every}} {{__('general_sentence.weeks')}}</div>
								<div>({{$contract_service->recurrence_details->weekly_days}})</div>
	                			@else
								<div>{{__('contract_manage_module.reccur_every')}} {{$contract_service->recurrence_details->reccure_every}} {{__('general_sentence.days')}}</div>
	                			@endif

	                		</div>
	                		
	                	</td>
	                </tr>
	                @endif
	              	<tr>
	                	<td>{{__('contract_manage_module.labels.service_price')}}</td>
	                	<td>
	                		{{$contract_service->currency}} {{$contract_service->price}}
	                	</td>
	                </tr>
	               	<tr>
	                	<td>{{__('contract_manage_module.labels.note')}}</td>
	                	<td>{{$contract_service->note}}
	                	</td>
	                </tr>
                </tbody>
            </table>
        </div>
    </div>

   
</div>