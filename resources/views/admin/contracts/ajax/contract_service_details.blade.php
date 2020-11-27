<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover table-bordered">
                <tbody>
	                <tr>
	                	<td>Service Name</td>
	                	<td>
	                		{{$contract_service->service->service_name}}
	                	</td>
	                </tr>
	               	<tr>
	                	<td>Service Type</td>
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
	                	<td>Recurrence Details</td>
	                	<td>
	                		<div>
	                			<span>From {{Carbon::parse($contract_service->recurrence_details->start_date)->format('d/m/Y')}}</span> To 
	                			<span>
	                			@if($contract_service->recurrence_details->end_by_or_after=='end_by')
	                				{{Carbon::parse($contract_service->recurrence_details->end_date)->format('d/m/Y')}}
	                			@else
	                				after {{$contract_service->recurrence_details->no_of_occurrences}} occurrences
	                			@endif
	                			</span>

	                		</div>
	                		<div>
	                			{{Carbon::parse($contract_service->recurrence_details->start_time)->format('g:i A')}}-{{Carbon::parse($contract_service->recurrence_details->end_time)->format('g:i A')}}
	                		</div>

	                		<div>
	                			
	                			@if($contract_service->recurrence_details->interval_type=='yearly')
								<div>Recurre every {{$contract_service->recurrence_details->reccure_every}} year(s)</div>
								<div>
									@if($contract_service->recurrence_details->on_or_on_the=='on')
									On <span>{{$contract_service->recurrence_details->day_number}} {{$contract_service->recurrence_details->month_name}}</span>
									@else
									On the <span>{{$contract_service->recurrence_details->ordinal}}, {{$contract_service->recurrence_details->week_day_name}}, {{$contract_service->recurrence_details->month_name}}</span>
									@endif
								</div>
	                			@elseif($contract_service->recurrence_details->interval_type=='monthly')
								<div>Recurre every {{$contract_service->recurrence_details->reccure_every}} month(s)</div>
								<div>
									@if($contract_service->recurrence_details->on_or_on_the=='on')
									On <span>{{$contract_service->recurrence_details->day_number}} day 
									@else
									On the <span>{{$contract_service->recurrence_details->ordinal}}, {{$contract_service->recurrence_details->week_day_name}}</span>
									@endif
									Of every {{$contract_service->recurrence_details->reccure_every}} month(s)
								</div>

	                			@elseif($contract_service->recurrence_details->interval_type=='weekly')
								<div>Recurre every {{$contract_service->recurrence_details->reccure_every}} week(s)</div>
								<div>({{$contract_service->recurrence_details->weekly_days}})</div>
	                			@else
								<div>Recurre every {{$contract_service->recurrence_details->reccure_every}} day(s)</div>
	                			@endif

	                		</div>
	                		
	                	</td>
	                </tr>
	                @endif
	              	<tr>
	                	<td>Service Price</td>
	                	<td>
	                		{{$contract_service->currency}} {{$contract_service->price}}
	                	</td>
	                </tr>
	               	<tr>
	                	<td>Note</td>
	                	<td>{{$contract_service->note}}
	                	</td>
	                </tr>
                </tbody>
            </table>
        </div>
    </div>

   
</div>