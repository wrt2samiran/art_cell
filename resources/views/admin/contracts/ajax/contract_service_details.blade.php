<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover table-bordered">
                <tbody>
	                <tr>
	                	<td>Service Name</td>
	                	<td>{{$contract_service->service->service_name}}</td>
	                </tr>
	               	<tr>
	                	<td>Service Type</td>
	                	<td>{{$contract_service->service_type}}</td>
	                </tr>
	               	<tr>
	                	<td>How Frequently</td>
	        		    <td>
	                        @if($contract_service->frequency_type)
	                        {{$contract_service->frequency_type->type}}

	                        (x{{$contract_service->frequency_number}})
	                        @endif
	                        
	                        @if($contract_service->number_of_time_can_used)
	                        <span>Can use {{$contract_service->number_of_time_can_used}} times</span>
	                        @endif
	                    </td>
	                </tr>
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