@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Service Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.cities.list')}}">City</a></li>
              <li class="breadcrumb-item active">Add</li>
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
                <h3 class="card-title">Add Service</h3>
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
                      <form  method="post" id="admin_service_add_form" action="{{route('admin.service_management.addService')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>                        

                          <div class="form-group required">
                            <label for="contract_id">Contract Code <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" onchange='onContractChange(this.value)' style="width: 100%;" name="contract_id" id="contract_id">
                                <option value="">Select a Contract</option>
                                @forelse($contract_list as $contract_data)
                                   <option value="{{$contract_data->id}}" {{(old('contract_id')== $contract_data->id)? 'selected':''}}>{{$contract_data->code}}</option>
                                @empty
                               <option value="">No Contract Found</option>
                                @endforelse
            
                              </select>
                            @if($errors->has('contract_id'))
                            <span class="text-danger">{{$errors->first('contract_id')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="property_id">Property Name <span class="error">*</span></label>
                            
                            <select class="form-control parent_role_select2" style="width: 100%;" name="property_id" id="property_id">
                               <option value="">Select a Property</option>
                               
                            </select>
                            @if($errors->has('property_id'))
                            <span class="text-danger">{{$errors->first('property_id')}}</span>
                            @endif
                          </div>


                          <div class="form-group required">
                            <label for="service_id">Service Required <span class="error">*</span></label>
                            <select class="form-control parent_role_select2"  style="width: 100%;" name="service_id" id="service_id">
                                <option value="">Select a Service</option>
                               
                              </select>
                            @if($errors->has('service_id'))
                            <span class="text-danger">{{$errors->first('service_id')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="service_provider_id">Service Provider <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" style="width: 100%;" name="service_provider_id" id="service_provider_id">
                                <option value="">Select a Service Provider</option>
                                
            
                              </select>
                            @if($errors->has('service_provider_id'))
                            <span class="text-danger">{{$errors->first('service_provider_id')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="country_id">Start Date <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('service_start_date')?old('service_start_date'):''}}" name="service_start_date" id="service_start_date"  placeholder="Please Enter Start Date">
                            @if($errors->has('country_id'))
                            <span class="text-danger">{{$errors->first('service_start_date')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="country_id">End Date <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('service_end_date')?old('service_end_date'):''}}" name="service_end_date" id="service_end_date"  placeholder="Please Enter Country Name">
                            @if($errors->has('service_end_date'))
                            <span class="text-danger">{{$errors->first('service_end_date')}}</span>
                            @endif
                          </div>

                          <!-- <div class="form-group">
                            <label for="contract_files">Attach Files</label>
                            <input  type="file" multiple class="form-control"
                            name="contract_files[]" id="contract_files" aria-describedby="propertyFilesHelp" >

                            <small id="propertyFilesHelp" class="form-text text-muted">Upload PDF files of max. 1mb</small>
                            @if($errors->get('contract_files.*'))
                            
                             @foreach($errors->get('service_files.*') as $err)
                              <span class="text-danger">{{$err[0]}}</span><br>
                              @break
                             @endforeach
                           
                            @endif
                          </div> -->
                          
                        </div>
                        <div>
                           <a href="{{route('admin.service_management.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
       
        url: "{{route('admin.service_management.getData')}}",
        type:'post',
        dataType: "json",
        data:{contract_id:contract_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.contract_service);
            
                
             var stringifiedContractService = JSON.stringify(response.contract_service);
             var contractServiceData = JSON.parse(stringifiedContractService);
             var service_list = '<option value=""> Select Service</option>';
             $.each(contractServiceData,function(index, service_id){
                    service_list += '<option value="'+service_id.service.id+'">'+ service_id.service.service_name +'</option>';
             });
                 $("#service_id").html(service_list);  


             var stringifiedProperty = JSON.stringify(response.sqlProperty);
             var propertyData = JSON.parse(stringifiedProperty);
                 var property_list= '<option value="'+propertyData.property.id+'">'+ propertyData.property.property_name +'</option>';
                $("#property_id").html(property_list);

             var stringifiedProperty = JSON.stringify(response.sqlProperty);
             var propertyData = JSON.parse(stringifiedProperty);
                 var property_list= '<option value="'+propertyData.property.id+'">'+ propertyData.property.property_name +'</option>';
                $("#property_id").html(property_list);

             var stringifiedServiceProvider = JSON.stringify(response.sqlServiceProvider);
             var serviceProviderData = JSON.parse(stringifiedServiceProvider);
             var service_provider_list = '<option value=""> Select Service Provider</option>';
                  service_provider_list+= '<option value="'+serviceProviderData.service_provider.id+'">'+ serviceProviderData.service_provider.name +'</option>';
                $("#service_provider_id").html(service_provider_list);      
                    
     

            }
        });
    }


    $('#service_start_date').datepicker({
    dateFormat:'dd/mm/yy'
});
$('#service_end_date').datepicker({
    dateFormat:'dd/mm/yy'
});
</script>

<script type="text/javascript" src="{{asset('js/admin/service_management/create.js')}}">

  
</script>
@endpush
