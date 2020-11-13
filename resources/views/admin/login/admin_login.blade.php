@extends('admin.layouts.before-login-layout')

@section('content')
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>SMMS </b>Admin Login</a>
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
  <div class="card-body" style="background-color: white">
    <button type="submit" onclick="showQuotation()" class="btn btn-primary btn-block">Submit Quotation</button>
    <div class="modal fade" id="addQuotationModal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style="width: 200%">
          <div class="modal-header">
            
            <h4 class="modal-title">Submit Quotation</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
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
                        <form  method="post" id="admin_labour_task_add_form" action="{{route('admin.calendar.calendardataAdd')}}" method="post" enctype="multipart/form-data">
                          @csrf
                                <div>  
                                  </div>
                                  
                                  
                                  <div class="row">
                                    <div class="col-md-6">
                                      <label for="property_id">First Name <span class="error">*</span></label>
                                      <input type="text" class="form-control float-right" id="first_name" name="first_name">
                                      @if($errors->has('first_name'))
                                        <span class="text-danger">{{$errors->first('first_name')}}</span>
                                      @endif
                                    </div>
                                    <div class="col-md-6">
                                      <label for="property_id">Last Name <span class="error">*</span></label>
                                      <input type="text" class="form-control float-right" id="last_name" name="last_name">
                                      @if($errors->has('first_name'))
                                        <span class="text-danger">{{$errors->first('last_name')}}</span>
                                      @endif
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-6">
                                      <label for="property_id">Email <span class="error">*</span></label>
                                      <input type="text" class="form-control float-right" id="email" name="email">
                                      @if($errors->has('email'))
                                        <span class="text-danger">{{$errors->first('email')}}</span>
                                      @endif
                                    </div>
                                    <div class="col-md-6">
                                      <label for="property_id">Contact Number <span class="error">*</span></label>
                                      <input type="text" class="form-control float-right" id="contact_number" name="contact_number">
                                      @if($errors->has('contact_number'))
                                        <span class="text-danger">{{$errors->first('contact_number')}}</span>
                                      @endif
                                    </div>
                                  </div>

                                  

                                  <div class="row">
                                    <div class="col-md-6">
                                      <label for="country_id">Country <span class="error">*</span></label>
                                        <select class="form-control parent_role_select2" style="width: 100%;" name="country_id" id="country_id">
                                              <option value="">Select Country</option>
                                        </select>
                                      @if($errors->has('country_id'))
                                      <span class="text-danger">{{$errors->first('country_id')}}</span>
                                      @endif
                                    </div>
                                    <div class="col-md-6">
                                      <label for="country_id">State <span class="error">*</span></label>
                                       <select name="state_id" id="state_id" class="form-control">
                                              <option value="">Select State</option>
                                       </select>
                                       @if($errors->has('state_id'))
                                          <span class="text-danger">{{$errors->first('state_id')}}</span>
                                       @endif
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-6">
                                      <label for="name">City Name <span class="error">*</span></label>
                                        <select name="city_id" id="city_id" class="form-control">
                                                <option value="">Select City</option>
                                        </select>
                                          @if($errors->has('city_id'))
                                            <span class="text-danger">{{$errors->first('city_id')}}</span>
                                          @endif
                                    </div>
                                    <div class="col-md-6">
                                      <label for="property_id">Address <span class="error">*</span></label>
                                        <textarea name="" id="" class="form-control"></textarea>
                                          @if($errors->has('property_id'))
                                            <span class="text-danger">{{$errors->first('property_id')}}</span>
                                          @endif
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-6">
                                      <label for="country_id">Property Type <span class="error">*</span></label>
                                        <select class="form-control parent_role_select2" style="width: 100%;" name="country_id" id="country_id">
                                              <option value="">Select Property Type</option>
                                        </select>
                                      @if($errors->has('country_id'))
                                      <span class="text-danger">{{$errors->first('country_id')}}</span>
                                      @endif
                                    </div>
                                    <div class="col-md-6">
                                      <div class="row">
                                        <div class="col-md-6">
                                          <label for="country_id">Contrat Period <span class="error">*</span></label>
                                          <input type="text" class="form-control float-right" id="date_range" name="date_range">
                                        </div>
                                        <div class="col-md-6">
                                          <label for="country_id">Type <span class="error">*</span></label>
                                          <select class="form-control parent_role_select2" style="width: 100%;" name="country_id" id="country_id">
                                              <option value="">Select Property Type</option>
                                        </select>
                                        </div> 
                                      </div>  
                                      @if($errors->has('country_id'))
                                      <span class="text-danger">{{$errors->first('country_id')}}</span>
                                      @endif
                                    </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-6">
                                      <label for="country_id">Select Service <span class="error">*</span></label>
                                        <select class="form-control parent_role_select2" style="width: 100%;" name="country_id" id="country_id">
                                              <option value="">Select Property Type</option>
                                        </select>
                                      @if($errors->has('country_id'))
                                      <span class="text-danger">{{$errors->first('country_id')}}</span>
                                      @endif
                                    </div>
                                    <div class="col-md-6">
                                      <label for="country_id">Service Type <span class="error">*</span></label>
                                        <select class="form-control parent_role_select2" style="width: 100%;" name="country_id" id="country_id">
                                              <option value="">Select Property Type</option>
                                        </select>
                                      @if($errors->has('country_id'))
                                      <span class="text-danger">{{$errors->first('country_id')}}</span>
                                      @endif
                                    </div>
                                  </div>

                                  

                                  <div class="form-group">
                                    <label for="service_id">Task Description</label>
                                    <textarea class="form-control float-right" name="job_desc" id="job_desc">{{old('job_desc')}}</textarea>
                                  </div>                                            
                              <div>
                             <button type="submit" class="btn btn-success">Submit</button> 
                          </div>
                        </form>
                      </div>
                    </div>
                </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->

<script type="text/javascript">
  function showQuotation() {
    $('#addQuotationModal').modal('show');
  }
</script>

@endsection
