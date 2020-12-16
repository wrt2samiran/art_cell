 
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
          <a href="{{route('frontend.create_quotation')}}" target="_blank" class="btn btn-outline-success btn-sm" >
            Click here to submit a quote
          </a>
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



@endsection