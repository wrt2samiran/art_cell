@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Order Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.order.list')}}">Order List</a></li>
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
                <h3 class="card-title">Create Order</h3>
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
                      <form  method="post" id="order_create_form" action="{{route('admin.order.add')}}" method="post">
                        @csrf
                        <div>
                          <div class="form-group required">
                            <label for="first_name">First Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('first_name')?old('first_name'):''}}" name="first_name" id="first_name"  placeholder="First Name">
                            @if($errors->has('first_name'))
                            <span class="text-danger">{{$errors->first('first_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="last_name">Last Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('last_name')?old('last_name'):''}}" name="last_name" id="last_name"  placeholder="Last Name">
                            @if($errors->has('last_name'))
                            <span class="text-danger">{{$errors->first('last_name')}}</span>
                            @endif
                          </div>
                          
                          <div class="form-group required">
                            <label for="phone">Phone/Contact Number <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('customer_contact')?old('customer_contact'):''}}" name="customer_contact" id="customer_contact"  placeholder="Phone/Contact Number">
                            @if($errors->has('customer_contact'))
                            <span class="text-danger">{{$errors->first('customer_contact')}}</span>
                            @endif
                          </div>

                          <div class="tab-pane active" id="english" role="tabpanel">
                            <div class="form-group required">
                              <label for="country_id">Country <span class="error">*</span></label>
                                <select class="form-control parent_role_select2" onchange='onCountryChange(this.value)' style="width: 100%;" name="country_id" id="country_id">
                                    <option value="">Select a Country</option>
                                    @forelse($country_list as $country_data)
                                       <option value="{{$country_data->id}}" {{(old('country_id')== $country_data->id)? 'selected':''}}>{{$country_data->name}}</option>
                                    @empty
                                   <option value="">No Country Found</option>
                                    @endforelse
                
                                </select>
                                @if($errors->has('country_id'))
                                <span class="text-danger">{{$errors->first('country_id')}}</span>
                                @endif
                            </div>

                            <div class="form-group required">
                                <label for="state_id">State <span class="error">*</span></label>
                                 <select name="state_id" id="state_id" class="form-control" onchange='onStateChange(this.value)'>
                                    <option value=""> Select State</option>
                                 </select>
                              @if($errors->has('state_id'))
                              <span class="text-danger">{{$errors->first('state_id')}}</span>
                              @endif
                            </div>

                            <div class="form-group required">
                                <label for="country_id">City <span class="error">*</span></label>
                                 <select name="city_id" id="city_id" class="form-control">
                                    <option value=""> Select City</option>
                                 </select>
                                @if($errors->has('city_id'))
                                <span class="text-danger">{{$errors->first('city_id')}}</span>
                                @endif
                            </div>
                          </div>



                          <div class="form-group">
                            <label for="weekly_off_add">Address</label>
                             <textarea class="form-control" name="customer_address" id="customer_address"></textarea>
                          </div>
                          


                          <div class="form-group required">
                            <label for="mobile_brand_id">Brand Name <span class="error">*</span></label>
                            <select class="form-control parent_role_select2" style="width: 100%;" name="mobile_brand_id" id="mobile_brand_id" onchange="onBrandChange(this.value)">
                                <option value="">Select a Brand</option>
                                @forelse($mobile_brand_data as $brand_data)
                                   <option value="{{$brand_data->id}}" {{(old('mobile_brand_id')== $brand_data->id)? 'selected':''}}>{{$brand_data->name}}</option>
                                @empty
                               <option value="">No Brand Found</option>
                                @endforelse
            
                              </select>
                            @if($errors->has('mobile_brand_id'))
                            <span class="text-danger">{{$errors->first('mobile_brand_id')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                              <label for="mobile_brand_model_id">Model <span class="error">*</span></label>
                               <select name="mobile_brand_model_id" id="mobile_brand_model_id" class="form-control">
                                  <option value=""> Select Model</option>
                               </select>
                              @if($errors->has('mobile_brand_model_id'))
                              <span class="text-danger">{{$errors->first('mobile_brand_model_id')}}</span>
                              @endif
                          </div>

                          <div class="form-group">
                            <label for="start_time">IMEI / Serial  No:</label>
                            <input class="form-control" type="text" id="imei_serial" name="imei_serial">
                          </div>

                          <div class="form-group">
                            <label for="weekly_off_add">Physical Condition <span class="error">*</span></label>
                             <textarea class="form-control" name="physical_condition" id="physical_condition"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="weekly_off_add">Risk aggred by customer</label>
                             <textarea class="form-control" name="risk_agreed_by_customer" id="risk_agreed_by_customer"></textarea>
                          </div>
                          <div class="form-group">
                            <label for="weekly_off_add">Service Complaints <span class="error">*</span></label>
                             <textarea class="form-control" name="service_complaints" id="service_complaints"></textarea>
                          </div>

                          <div class="form-group">
                            <label for="start_time">Warrenty No:</label>
                            <input class="form-control" type="text" id="warrrenty_no" name="warrrenty_no">
                          </div>
                          
                          <div class="form-group">
                            <label for="start_time">Estimated Price:</label>
                            <input class="form-control" type="Number" id="estimated_price" name="estimated_price">
                          </div>

                          <div class="form-group">
                            <label for="start_time">Advance Payment:</label>
                            <input class="form-control" type="Number" id="advanced_payment" name="advanced_payment">
                          </div>

                        
                          
                        
                        </div>
                        <!--  this the url for remote validattion rule for user email -->
                        <!-- <input type="hidden" id="ajax_check_user_email_unique" value="{{route('ajax.check_user_email_unique')}}"> -->
                        <div>
                           <a href="{{route('admin.order.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
       
        url: "{{route('admin.order.getStates')}}",
        type:'post',
        dataType: "json",
        data:{country_id:country_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allStates);
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

    
    function onStateChange(state_id){
     $.ajax({
       
        url: "{{route('admin.order.getCityList')}}",
        type:'post',
        dataType: "json",
        data:{state_id:state_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allCity);
             var stringified = JSON.stringify(response.allCity);
            var citydata = JSON.parse(stringified);
             var city_list = '<option value=""> Select City</option>';
             $.each(citydata,function(index, city_id){
                    city_list += '<option value="'+city_id.id+'">'+ city_id.name +'</option>';
             });
                $("#city_id").html(city_list);
            }
        });
    }

   

   function onBrandChange(brand_id){
     $.ajax({
       
        url: "{{route('admin.order.getModelList')}}",
        type:'post',
        dataType: "json",
        data:{brand_id:brand_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allModels);
             var stringified = JSON.stringify(response.allModels);
            var modeldata = JSON.parse(stringified);
             var model_list = '<option value=""> Select Model</option>';
             $.each(modeldata,function(index, model_id){
                    model_list += '<option value="'+model_id.id+'">'+ model_id.name +'</option>';
             });
                $("#mobile_brand_model_id").html(model_list);
            }
        });
    }

</script>
<script type="text/javascript" src="{{asset('js/admin/order/create.js')}}"></script>

@endpush
