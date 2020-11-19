@extends('admin.layouts.before-login-layout')

@section('content')
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href=""><b>SMMS </b>Admin Login</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
        @if(count($errors) > 0)
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}</span><br/>
                @endforeach
            </div>
        @endif

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
      <p class="login-box-msg">Sign in to access your panel</p>

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
        <h3>Submit Quotation</h3>
        <form  method="POST" id="admin_quotetion" action="{{route('admin.quotetion')}}" enctype="multipart/form-data">
          @csrf
                <div>  
                  </div>
                  
                  
                  <div class="row form-group">
                    <div class="col-md-6">
                      <label for="property_id">First Name<span class="error">*</span></label>
                      <input type="text" class="form-control" id="first_name" name="first_name">
                      @if($errors->has('first_name'))
                        <span class="text-danger">{{$errors->first('first_name')}}</span>
                      @endif
                    </div>
                    <div class="col-md-6">
                      <label for="property_id">Last Name<span class="error">*</span></label>
                      <input type="text" class="form-control" id="last_name" name="last_name">
                      @if($errors->has('last_name'))
                        <span class="text-danger">{{$errors->first('last_name')}}</span>
                      @endif
                    </div>
                  </div>

                  <div class="row form-group">
                    <div class="col-md-6">
                      <label for="property_id">Email<span class="error">*</span></label>
                      <input type="text" class="form-control" id="email" name="email">
                      @if($errors->has('email'))
                        <span class="text-danger">{{$errors->first('email')}}</span>
                      @endif
                    </div>
                    <div class="col-md-6">
                      <label for="property_id">Contact Number<span class="error">*</span></label>
                      <input type="text" class="form-control" id="contact_number" name="contact_number">
                      @if($errors->has('contact_number'))
                        <span class="text-danger">{{$errors->first('contact_number')}}</span>
                      @endif
                    </div>
                  </div>

                  

                  <div class="row form-group">
                    {{-- <div class="col-md-6 form-group">
                      <label for="name">Country Name<span class="error">*</span></label>
                      <select class="form-control" id="country_id" name="country_id" style="width: 100%;">
                        <option value="">Select Country</option>
                        @forelse($countryList as $country)
                           <option value="{{$country->id}}" >{{$country->name}}</option>
                        @empty
                        <option value="">No City Found</option>
                        @endforelse
                      </select>
                          @if($errors->has('country_id'))
                            <span class="text-danger">{{$errors->first('country_id')}}</span>
                          @endif
                    </div> --}}
                    <div class="col-md-6">
                      <label for="country_id">State<span class="error">*</span></label>
                      <select class="form-control " id="state_id" name="state_id" style="width: 100%;">
                        <option value="">Select State</option>
                        @forelse($stateList as $state)
                           <option value="{{$state->id}}" >{{$state->name}}</option>
                        @empty
                        <option value="">No State Found</option>
                        @endforelse
                      </select>
                       @if($errors->has('state_id'))
                          <span class="text-danger">{{$errors->first('state_id')}}</span>
                       @endif
                    </div>
                  </div>

                  <div class="row form-group">
                    <div class="col-md-6">
                      <label for="name">City Name<span class="error">*</span></label>
                      <select class="form-control" id="city_id" name="city_id" style="width: 100%;">
                        <option value="">Select city</option>
                        @forelse($cityList as $city)
                           <option value="{{$city->id}}" >{{$city->name}}</option>
                        @empty
                        <option value="">No City Found</option>
                        @endforelse
                      </select>
                          @if($errors->has('city_id'))
                            <span class="text-danger">{{$errors->first('city_id')}}</span>
                          @endif
                    </div>
                    <div class="col-md-6">
                      <label for="landmark">Address<span class="error">*</span></label>
                        <textarea name="landmark" id="landmark" class="form-control"></textarea>
                          @if($errors->has('landmark'))
                            <span class="text-danger">{{$errors->first('landmark')}}</span>
                          @endif
                    </div>
                  </div>

                  <div class="row form-group">
                    <div class="col-md-6">
                      <label for="contract_duration">Contract Period<span class="error">*</span></label>
                      <input type="text" class="form-control" id="contract_duration" name="contract_duration">
                      @if($errors->has('contract_duration'))
                      <span class="text-danger">{{$errors->first('contract_duration')}}</span>
                      @endif
                    </div>
                    
                  </div>
                  
                    

                    <div class="form-group required">
                      <label>Add Service</label>
                      <div>
                        <button type="button" id="add_new_file" class="btn btn-outline-success"><i class="fa fa-plus"></i>&nbsp;Add Service</button>
                      </div>
                    </div>
                    <div id="files_container">

                    </div>
                    {{-- <p>Total Amount : </p>
                    <p id="totalAmountText">0</p> --}}
                  <div class="form-group">
                    <label for="details">Description</label>
                    <textarea class="form-control float-right" name="details" id="details">{{old('details')}}</textarea>
                  </div>                                            
              <div>
             <button type="submit" class="btn btn-success">Submit</button> 
          </div>
        </form>
      </div>
    </div>
</div>
  
</div>
<!-- /.login-box -->



@endsection


