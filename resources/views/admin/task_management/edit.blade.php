@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Task</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.task_management.list')}}">Task</a></li>
              <li class="breadcrumb-item active">Edit</li>
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
                <h3 class="card-title">Edit Task</h3>
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
                     
                      <form  method="post" id="admin_task_add_form" action="{{route('admin.task_management.edit', $details->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div>  
                              </div>
                              <div class="form-group required">
                                <label for="service_id">Contract Title <span class="error">*</span></label>
                                <select class="form-control parent_role_select2"  style="width: 100%;" name="contract_id" id="contract_id" 
                                  onchange="onContractChange(this.value)">
                                   <option value="">Select Contract</option>
                                   @forelse($contract_list as $contract_data)
                                         <option value="{{$contract_data->id}}" {{$details->contract_id == $contract_data->id? 'selected':''}}>{{@$contract_data->title}} ({{@$contract_data->code}})</option>
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
                                    <option value=""> Select Property</option>
                                    @foreach($contract_list.property as $property_data)
                                    <option value="{{$property_data->id}}" @if($property_data->id == $details->property_id) selected="selected" @endif>{{$property_data->property_name}} </option>
                                    @endforeach
                                  </select>
                                @if($errors->has('property_id'))
                                <span class="text-danger">{{$errors->first('property_id')}}</span>
                                @endif
                              </div>

                              <div class="form-group required">
                                <label for="service_id">Service <span class="error">*</span></label>
                                <select class="form-control parent_role_select2"  style="width: 100%;" name="service_id" id="service_id" 
                                 >
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
                                        <option value="">Select State</option>
                                </select>
                                 @if($errors->has('city_id'))
                                    <span class="text-danger">{{$errors->first('city_id')}}</span>
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
                              <div class="form-group required">
                                <label for="service_id">Task Title <span class="error">*</span></label>
                                <input type="text" class="form-control float-right" id="task_title" name="task_title">
                                 @if($errors->has('task_title'))
                                  <span class="text-danger">{{$errors->first('task_title')}}</span>
                                 @endif
                              </div>

                              <div class="form-group">
                                <label for="service_id">Task Description</label>
                                <textarea class="form-control float-right" name="task_desc" id="task_desc">{{old('task_desc')}}</textarea>
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

  function onCountryChange(country_id){
     $.ajax({
       
        url: "{{route('admin.cities.getStates')}}",
        type:'post',
        dataType: "json",
        data:{country_id:country_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allState);
             var stringified = JSON.stringify(response.allStates);
            var statedata = JSON.parse(stringified);
             var state_list = '<option value=""> Select State</option>';
             $.each(statedata,function(index, state_id){
                    state_list += '<option value="'+state_id.id+'">'+ state_id.name +'</option>';
             });
                $("#state_id").html(state_list);
            }
        });
    }

</script>
<script type="text/javascript" src="{{asset('js/admin/city/edit.js')}}"></script>
@endpush
