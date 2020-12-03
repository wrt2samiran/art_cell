 
@extends('admin.layouts.before-login-layout')

@section('content')
<style type="text/css">
  .pac-container {
    background-color: #FFF;
    z-index: 2001;
    position: fixed;
    display: inline-block;
    float: left;
}

.quoatation-message
{

    padding: 20px;
    border-left: 3px solid #eee;
}
.quoatation-message h4
{
    margin-top: 0;
    margin-bottom: 5px;
}
.quoatation-message p:last-child
{
    margin-bottom: 0;
}
.quoatation-message code
{
    background-color: #fff;
    border-radius: 3px;
}
.quoatation-message
{
    background-color: #F4FDF0;
    border-color: #3C763D;
}
.quoatation-message h4
{
    color: #3C763D;
}
span.error{
  color: red;
}
</style>
<div class="login-logo">
  <a href=""><b>SMMS </b>Admin Login</a>
</div>
<div class="container">
  <div class="row">

    <div class="col-md-6">
      <div >
        @if(Session::has('quotation_success'))
            <div class="alert alert-success alert-dismissable __web-inspector-hide-shortcut__">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                {{ Session::get('quotation_success') }}
            </div>
        @endif
        @if(Session::has('quotation_error'))
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                {{ Session::get('quotation_error') }}
            </div>
        @endif
      </div>
           
      <div class="quoatation-message">
          <h4>Submit Quote</h4>
          <p>Want to make contract with us. Please let us know your requirements.</p>
          <button type="button" class="btn btn-outline-success btn-sm" data-toggle="modal" data-target="#submitQuoteModal">
            Click here to submit a quote
          </button>
      </div>     
  
    </div>

    <div class="form-group col-md-6">
      <div class="card">
        <div class="card-body login-card-body">

          <p class="login-box-msg">Sign in to access your panel</p>
          <div >
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
          </div>
          <form action="{{ route('admin.authentication') }}" method="post" id="login_table">
          @csrf
            <div class="input-group mb-3 ">
              <input type="email" class="form-control" placeholder="Email*" name="email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" placeholder="Password*" name="password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember" name="remember_me">
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
              </div>
              <!-- /.col -->
            </div>
          </form>

        <!-- /.social-auth-links -->

          <p class="mb-1">
            <a href="{{route('admin.forgot.password')}}">I forgot my password</a>
          </p>
        </div>
      <!-- /.login-card-body -->
      </div>
    </div>
  </div>
</div> 

<!-- submit quoatation modal -->
<div class="modal fade" id="submitQuoteModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <h4 class="modal-title">Please fill up the form and submit</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       
      </div>
      <div class="modal-body">
        
        <form  method="POST" id="quotetion_submit_form" action="{{route('admin.submit_quotation')}}" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6 form-group">
              <label for="property_id">First Name <span class="error">*</span></label>
              <input type="text" class="form-control" id="first_name" name="first_name">
              @if($errors->has('first_name'))
                <span class="text-danger">{{$errors->first('first_name')}}</span>
              @endif
            </div>
            <div class="col-md-6 form-group">
              <label for="property_id">Last Name <span class="error">*</span></label>
              <input type="text" class="form-control" id="last_name" name="last_name">
              @if($errors->has('last_name'))
                <span class="text-danger">{{$errors->first('last_name')}}</span>
              @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label for="property_id">Email</label>
              <input type="text" class="form-control" id="email" name="email">
              @if($errors->has('email'))
                <span class="text-danger">{{$errors->first('email')}}</span>
              @endif
            </div>
            <div class="col-md-6  form-group">
              <label for="property_id">Contact Number<span class="error">*</span></label>
              <input type="text" class="form-control" id="contact_number" name="contact_number">
              @if($errors->has('contact_number'))
                <span class="text-danger">{{$errors->first('contact_number')}}</span>
              @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-6  form-group">
              <label for="country_id">State<span class="error">*</span></label>
              <select class="form-control " id="state_id" name="state_id" style="width: 100%;">
                <option value="">Select State</option>
                @forelse($states as $state)
                  <option data-cities="{{$state->cities}}" value="{{$state->id}}" >{{$state->name}}</option>
                @empty
                <option value="">No State Found</option>
                @endforelse
              </select>
              <div id="state_id_error"></div>
              @if($errors->has('state_id'))
                  <span class="text-danger">{{$errors->first('state_id')}}</span>
              @endif
            </div>
            <div class="col-md-6  form-group">
              <label for="name">City<span class="error">*</span></label>
              <select class="form-control" id="city_id" name="city_id" style="width: 100%;">
                <option value="">Select city</option>
                @forelse($cities as $city)
                  <option value="{{$city->id}}" >{{$city->name}}</option>
                @empty
                <option value="">No City Found</option>
                @endforelse
              </select>
              <div id="city_id_error"></div>
              @if($errors->has('city_id'))
                <span class="text-danger">{{$errors->first('city_id')}}</span>
              @endif
            </div>
          </div>
          <div class="row">
            <div class="col-md-12  form-group">
              <label for="landmark">Landmark/Location<span class="error">*</span></label>
              <input type="text" class="form-control" id="landmark" name="landmark">
                  @if($errors->has('landmark'))
                    <span class="text-danger">{{$errors->first('landmark')}}</span>
                  @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-3 form-group">
              <label for="contract_duration">Contract Duration<span class="error">*</span></label>
              <input type="number" min="1" value="1" step="1" class="form-control" id="contract_duration" name="contract_duration">
              @if($errors->has('contract_duration'))
              <span class="text-danger">{{$errors->first('contract_duration')}}</span>
              @endif
            </div>
            <div class="col-md-3 form-group">
              <label for="contract_duration_type">&nbsp;</label>
              <select class="form-control " name="contract_duration_type" id="contract_duration_type">
                <option value="Year(s)">Year(s)</option>
                <option value="Month(s)">Month(s)</option>
                <option value="Week(s)">Week(s)</option>
                <option value="Day(s)">Day(s)</option>
              </select>
            </div>

            <div class="col-md-6  form-group">
              <label for="name">Property Type<span class="error">*</span></label>
              <select class="form-control" id="property_type_id" name="property_type_id" style="width: 100%;">
                <option value="">Select Proeprty Type</option>
                @forelse($property_types as $property_type)
                  <option value="{{$property_type->id}}" >{{$property_type->type_name}}</option>
                @empty
                <option value="">No Property Type Found</option>
                @endforelse
              </select>
              <div id="property_type_id_error"></div>
              @if($errors->has('property_type_id'))
                <span class="text-danger">{{$errors->first('property_type_id')}}</span>
              @endif
            </div>

          </div>



          <div class="row">
            <div class="col-md-12">
              <div class="form-group required">
                <label>Services Required</label>
                <div>
                  <button type="button" id="add_service" class="btn btn-outline-success"><i class="fa fa-plus"></i>&nbsp;Add Service</button>
                </div>
              </div>
            </div>
          </div>  
          <div class="row">
            <div class="col-md-12">
              <div id="services_container">

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="details">Description</label>
                <textarea class="form-control float-right" name="details" id="details">{{old('details')}}</textarea>
              </div>
            </div>
            
          </div>

          <div class="quoatation-message mt-2 mb-2" id="disclaimer" style="display: none;">
          
          <p>Total amount may vary as per your property and site visit</p>
          <p>Total Amount: {{Helper::getSiteCurrency()}}<span id="total_amount">0.00</span></p>
          </div>

          <div class="row mt-2">
            <div class="col-md-12">
              <button type="submit" class="btn btn-success">Submit</button> 
            </div>
            
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- submit quoatation modal end -->

@endsection