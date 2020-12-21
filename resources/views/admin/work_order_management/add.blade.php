@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Work Order Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.work-order-management.list')}}">Task</a></li>
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
                <h3 class="card-title">Create Work Order</h3>
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
                    <div class="col-md-10 col-sm-12">
                      <form  method="post" id="admin_task_add_form" action="{{route('admin.work-order-management.workOrderCreate')}}" method="post" enctype="multipart/form-data">
                        @csrf
                              
                                <div class="form-group required">
                                  <label for="contract_id">Contract Title <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2"  style="width: 100%;" name="contract_id" id="contract_id" 
                                    onchange="onContractChange(this.value)">
                                     <option value="">Select Contract</option>
                                     @forelse($contract_list as $contract_data)
                                           <option value="{{$contract_data->id}}" {{(old('contract_id')== $contract_data->id)? 'selected':''}}>{{@$contract_data->title}} ({{@$contract_data->code}})</option>
                                      @empty
                                      <option value="">No Contract Found</option>
                                      @endforelse                             
                                    </select>
                                  @if($errors->has('contract_id'))
                                  <span class="text-danger">{{$errors->first('contract_id')}}</span>
                                  @endif
                                </div>
                                
                                <div class="form-group required">
                                  <label for="property_id">Property <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2" style="width: 100%;" name="property_id" id="property_id" >
                                          <option value="">Select Property </option>
                                    </select>
                                  @if($errors->has('property_id'))
                                  <span class="text-danger">{{$errors->first('property_id')}}</span>
                                  @endif
                                </div>

                                <div class="form-group required">
                                  <label for="service_id">Service <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2 service_id"  style="width: 100%;" name="service_id" id="service_id" onchange="onServiceSelect(this.value)">
                                     <option>Select Service</option>
                                                                 
                                    </select>
                                  @if($errors->has('service_id'))
                                  <span class="text-danger">{{$errors->first('service_id')}}</span>
                                  @endif
                                </div>

                                <div class="form-group required">
                                  <label for="country_id">Country <span class="error">*</span></label>
                                    <select class="form-control parent_role_select2" style="width: 100%;" name="country_id" id="country_id">
                                          <option value="">Select Country</option>
                                    </select>
                                  @if($errors->has('country_id'))
                                  <span class="text-danger">{{$errors->first('country_id')}}</span>
                                  @endif
                                </div>

                                <div class="form-group required">
                                  <label for="country_id">State <span class="error">*</span></label>
                                   <select name="state_id" id="state_id" class="form-control">
                                          <option value="">Select State</option>
                                   </select>
                                    @if($errors->has('state_id'))
                                      <span class="text-danger">{{$errors->first('state_id')}}</span>
                                    @endif
                                </div>

                                <div class="form-group required">
                                  <label for="name">City Name <span class="error">*</span></label>
                                  <select name="city_id" id="city_id" class="form-control">
                                          <option value="">Select City</option>
                                  </select>
                                   @if($errors->has('city_id'))
                                      <span class="text-danger">{{$errors->first('city_id')}}</span>
                                   @endif
                                </div>
                                
                                <div class="form-group">
                                  <label>Task Date <span class="error">*</span></label>

                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="date_range" name="date_range" autocomplete="off">
                                  </div>
                                  <!-- /.input group -->
                                </div>
                                <div class="form-group required">
                                  <label for="service_id">Work Title <span class="error">*</span></label>
                                  <input type="text" class="form-control" id="task_title" name="task_title">
                                   @if($errors->has('task_title'))
                                    <span class="text-danger">{{$errors->first('task_title')}}</span>
                                   @endif
                                </div>

                                <div class="form-group">
                                  <label for="service_id">Work Description</label>
                                  <textarea class="form-control" name="task_desc" id="task_desc">{{old('task_desc')}}</textarea>
                                </div>                                            
                            <div>
                               <a href="{{route('admin.work-order-management.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
                               <button type="submit" disabled="" class="btn btn-success disable-button">Submit</button> 
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
@endsection 
@push('custom-scripts')


<script type="text/javascript">

  function onContractChange(contract_id){

     $.ajax({
       
        url: "{{route('admin.work-order-management.getContractData')}}",
        type:'get',
        dataType: "json",
        data:{contract_id:contract_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.sqlProperty);
             console.log(response.sqlService);
             var stringifiedProperty = JSON.stringify(response.sqlProperty);
             var propertyData = JSON.parse(stringifiedProperty);
                 var property_list= '<option value="'+propertyData.property.id+'">'+ propertyData.property.property_name +'</option>';
                $("#property_id").html(property_list);
                
             var stringifiedService = JSON.stringify(response.sqlService);
             var serviceData = JSON.parse(stringifiedService);
             var service_list = '<option value=""> Select Service</option>';
             if (serviceData.length > 0) {
                  $.each(serviceData,function(index, service_id){
                    service_list += '<option value="'+service_id.service_id+'">'+ service_id.service.service_name +' ('+service_id.service_type+')'+'</option>';
                  });
              }

              else
              {
                  service_list = '<option value=""> No Service Available</option>';
              }
             
                $("#service_id").html(service_list);
                   
             var stringifiedCity = JSON.stringify(response.sqlCity);
             var cityData = JSON.parse(stringifiedCity);
             var city_list = '<option value="'+cityData.id+'">'+ cityData.name +'</option>';
                $("#city_id").html(city_list);

             var stringifiedState = JSON.stringify(response.sqlState);
             var stateData = JSON.parse(stringifiedState);
             var state_list = '<option value="'+stateData.id+'">'+ stateData.name +'</option>';
                $("#state_id").html(state_list);
                
             var stringifiedCountry = JSON.stringify(response.sqlCountry);
             var countryData = JSON.parse(stringifiedCountry);
             var country_list = '<option value="'+countryData.id+'">'+ countryData.name +'</option>';
                $("#country_id").html(country_list);      

              // *******Changing calendar start date and end date as per the service, alloted to the contract********//

              $('#date_range').datepicker({
                 minDate: new Date(propertyData.start_date),
                 maxDate: new Date(propertyData.end_date),
                 startDate: new Date(propertyData.start_date),
                 endDate: new Date(propertyData.end_date),
                 dateFormat: 'dd/mm/yy',
              })

              // *******Changing calendar start date and end date as per the service, alloted to the contract********//
            }

            else{


                var property_list = '<option value=""> Select Property</option>';
                $("#property_id").html(property_list);
                var service_list = '<option value=""> Select Service</option>';
                $("#service_id").html(service_list);
                var city_list = '<option value=""> Select City</option>';
                $("#city_id").html(city_list);
                var state_list = '<option value=""> Select State</option>';
                $("#state_id").html(state_list);
                var country_list = '<option value=""> Select Country</option>';
                $("#country_id").html(country_list);
                $('#date_range').datepicker({
                 minDate: new Date(),
                 maxDate: new Date(),
                 startDate: new Date(),
                 endDate: new Date(),
                 dateFormat: 'dd/mm/yy',
              })
            }
        });
  }

  
  function onServiceSelect(service_id){
    var contract_id = $('#contract_id option:selected').val();

     $.ajax({
       
        url: "{{route('admin.work-order-management.getContractServiceStatus')}}",
        type:'get',
        dataType: "json",
        data:{service_id:service_id,contract_id:contract_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
           console.log(response.service_status);
            if(response.status){
              console.log(response.sqlProperty);
              console.log(response.sqlService);

              if(response.service_status=='Out of period' || response.service_status=='Not Available'){
                  swal({
                  title: response.service_status,
                  text: "This service is "+response.service_status+ " to add for a new work order! Please contact with Admin.",
                  icon: "warning",
                  dangerMode: true,
                  showCancelButton: false,
                  })
                
                   $('.service_id').val('');
                   //$(this).prop('selected', false);
                    //$("#service_id").val([]);
                    $('.disable-button').prop("disabled", true); // Submit button is now disabled.
                  
              }
              else{
                $('.disable-button').prop("disabled", false); // Submit button is now enabled.
              }
            
            }
        });
  }  

</script>

<script type="text/javascript" src="{{asset('js/admin/work_order_management/create.js')}}">

  
</script>
@endpush
