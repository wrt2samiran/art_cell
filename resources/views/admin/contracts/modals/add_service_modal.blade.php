<div class="modal" id="add_service_modal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Service</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
<form  method="post" id="add_service_form"  enctype="multipart/form-data">
      <!-- Modal body -->
      <div class="modal-body">
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
                   <option value="General">General</option> 
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
            <div class="form-group required">
              <label for="service_price">Service Price ({{Helper::getSiteCurrency()}})<span class="error">*</span></label>
              <input type="text" class="form-control" value="{{old('service_price')?old('service_price'):''}}" name="service_price" id="service_price"  placeholder="Service Price">
              @if($errors->has('service_price'))
              <span class="text-danger">{{$errors->first('service_price')}}</span>
              @endif
            </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
       <button class="btn btn-success">Add Service</button> <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
 </form>
    </div>
  </div>
</div>