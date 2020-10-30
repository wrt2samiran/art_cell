<div class="modal" id="edit_service_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Service</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
<form  method="post" id="edit_service_form"  enctype="multipart/form-data">
      <!-- Modal body -->
      <div class="modal-body">
            <input type="hidden" name="edit_contract_service_id" id="edit_contract_service_id">
            <input type="hidden" name="service_details" id="service_details">
            <div class="form-group required">
               <label for="services">Select service<span class="error">*</span></label>
                <select class="form-control " name="service_edit" id="service_edit" style="width: 100%;">
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
                <select class="form-control " name="service_type_edit" id="service_type_edit" style="width: 100%;">
                   <option value="">Select service type</option>
                   <option value="General">General</option> 
                   <option value="Maintenance">Maintenance</option> 
                   <option value="On Demand">On Demand</option>  
                   <option value="Free">Free</option>        
                </select>
            </div>

            <div class="form-group required" id="frequency_type_holder_edit" style="display: none;">
               <label for="frequency_types">How frequently service will be maintain?<span class="error">*</span></label>
                <select class="form-control " name="frequency_type_edit" id="frequency_type_edit" style="width: 100%;">
                  <option value="">Select services</option>
                  @forelse($frequency_types as $frequency_type)
                     <option data-interval_days="{{$frequency_type->no_of_days}}" data-type_name="{{$frequency_type->type}}" value="{{$frequency_type->id}}">{{$frequency_type->type}} </option>
                  @empty
                  <option value="">No Service Found</option>
                  @endforelse                                
                </select>
            </div>
            <div class="form-group required" id="number_of_time_can_used_holder_edit" style="display: none;">
              <label for="number_of_time_can_used_edit">Number of times can use</label>
              <input type="text" aria-describedby="numberOfTimesHelpEdit" class="form-control" value="{{old('number_of_time_can_used_edit')?old('number_of_time_can_used_edit'):''}}" name="number_of_time_can_used_edit" id="number_of_time_can_used_edit"  placeholder="Number of times">
              <small id="numberOfTimesHelpEdit" class="form-text text-muted">You can set the limitation of usage. Leave blank if no limitation.</small>
              @if($errors->has('number_of_time_can_used_edit'))
              <span class="text-danger">{{$errors->first('number_of_time_can_used_edit')}}</span>
              @endif
            </div>
            <div class="form-group required">
              <label for="service_price_edit">Service Price ({{Helper::getSiteCurrency()}})<span class="error">*</span></label>
              <input type="text" class="form-control" value="{{old('service_price_edit')?old('service_price_edit'):''}}" name="service_price_edit" id="service_price_edit"  placeholder="Service Price">
              @if($errors->has('service_price_edit'))
              <span class="text-danger">{{$errors->first('service_price_edit')}}</span>
              @endif
            </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
       <button class="btn btn-success">Update Service</button> <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
 </form>
    </div>
  </div>
</div>