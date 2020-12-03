 
@extends('admin.layouts.before-login-layout')

@section('content')
<style type="text/css">
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
</style>
<div class="login-logo">
  <a href=""><b>SMMS </b>Admin Login</a>
</div>
<div class="container">
  <div class="row">
    <div class="col-md-6">
           
      <div class="quoatation-message">
          <h4>Submit Quote</h4>
          <p>
              Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
              Ipsum has been the industry's standard dummy text ever since the 1500s.
          </p>

      </div>     
  
    </div>

    <div class="form-group col-md-6">
      <div class="card">
        <div class="card-body login-card-body">

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
    </div>
  </div>
</div> 

<!-- submit quoatation modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Submit Quoatation</h4>
      </div>
      <div class="modal-body">
        
          <form  method="POST" id="admin_quotetion" action="{{route('admin.quotetion')}}" enctype="multipart/form-data">
              @csrf
                  <div class="row">
                    <div class="col-md-6 form-group">
                      <label for="property_id">First Name<span class="error">*</span></label>
                      <input type="text" class="form-control" id="first_name" name="first_name">
                      @if($errors->has('first_name'))
                        <span class="text-danger">{{$errors->first('first_name')}}</span>
                      @endif
                    </div>
                    <div class="col-md-6 form-group">
                      <label for="property_id">Last Name<span class="error">*</span></label>
                      <input type="text" class="form-control" id="last_name" name="last_name">
                      @if($errors->has('last_name'))
                        <span class="text-danger">{{$errors->first('last_name')}}</span>
                      @endif
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6  form-group">
                      <label for="property_id">Email<span class="error">*</span></label>
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
                    <div class="col-md-6  form-group">
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
                  </div>

                  <div class="row">

                    <div class="col-md-12  form-group">
                      <label for="landmark">Address<span class="error">*</span></label>
                        <textarea name="landmark" id="landmark" class="form-control"></textarea>
                          @if($errors->has('landmark'))
                            <span class="text-danger">{{$errors->first('landmark')}}</span>
                          @endif
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12  form-group">
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
   
                  <div class="form-group">
                    <label for="details">Description</label>
                    <textarea class="form-control float-right" name="details" id="details">{{old('details')}}</textarea>
                  </div>  

                <div>
              <button type="submit" class="btn btn-success">Submit</button> 
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