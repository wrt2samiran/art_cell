@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Labour Task Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              
              <li class="breadcrumb-item active">Assign Labour Task</li>
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
                <h3 class="card-title">Assign Labour Task</h3>
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
                      <form  method="post" id="admin_labour_assign_form" action="{{route('admin.work-order-management.taskAssign')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <?php //dd($sqltaskData);?>
                              <div>  
                                </div>
                                <div class="form-group required">
                                  <label for="service_id">Task Title <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2"  style="width: 100%;" name="task_id" id="task_id" 
                                    >
                                           <option value="{{@$sqltaskData->id}}" {{(old('task_id')== @$sqltaskData->id)? 'selected':''}}>{{@$sqltaskData->task_title}}</option>
                                     
                                    </select>
                                  @if($errors->has('task_id'))
                                  <span class="text-danger">{{$errors->first('task_id')}}</span>
                                  @endif
                                </div>
                                
                                <div class="form-group required">
                                  <label for="property_id">Service <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2" style="width: 100%;" name="service_id" id="service_id" >
                                           <option value="{{@$sqltaskData->service_id}}" {{(old('service_id')== @$sqltaskData->service_id)? 'selected':''}}>{{@$sqltaskData->service->service_name}}</option>
                                    </select>
                                  @if($errors->has('property_id'))
                                  <span class="text-danger">{{$errors->first('property_id')}}</span>
                                  @endif
                                </div>

                                <div class="form-group required">
                                  <label for="service_id">Labour List <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2"  style="width: 100%;" name="user_id" id="user_id" 
                                    >
                                     <option value="">Select Labour</option>
                                     @forelse($labour_list as $labour_data)
                                           <option value="{{@$labour_data->id}}" {{(old('contract_id')== @$labour_data->id)? 'selected':''}}>{{@$labour_data->name}}</option>
                                      @empty
                                      <option value="">No Labour Found</option>
                                      @endforelse     
                                                               
                                    </select>
                                  @if($errors->has('contract_id'))
                                  <span class="text-danger">{{$errors->first('contract_id')}}</span>
                                  @endif
                                </div>
                                
                                <div class="form-group">
                                  <label>Date range <span class="error">*</span></label>

                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="date_range" name="date_range">
                                  </div>
                                  <!-- /.input group -->
                                </div>
                                

                                <div class="form-group">
                                  <label for="service_id">Task Description</label>
                                  <textarea class="form-control float-right" name="task_description" id="task_description">{{old('task_description')}}</textarea>
                                </div>                                            
                            <div>
                           <button type="submit" class="btn btn-success">Submit</button> 
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

              // *******Changing calendar start date and end date as per the service, alloted by the sub-admin********//

              $('#date_range').daterangepicker({
                 minDate: new Date(propertyData.start_date),
                 maxDate: new Date(propertyData.end_date),
                 startDate: new Date(propertyData.start_date),
                 endDate: new Date(propertyData.end_date),
              })

              // *******Changing calendar start date and end date as per the service, alloted by the sub-admin********//
            }
        });
    }

    $( document ).ready(function() {
        $('#date_range').daterangepicker({
                 minDate: new Date('<?=@$sqltaskData->start_date?>'),
                 maxDate: new Date('<?=@$sqltaskData->end_date?>'),
                 startDate: new Date('<?=@$sqltaskData->start_date?>'),
                 endDate: new Date('<?=@$sqltaskData->end_date?>'),
              })
    });

    
</script>

<script type="text/javascript" src="{{asset('js/admin/work-order-management/create.js')}}">

  
</script>
@endpush
