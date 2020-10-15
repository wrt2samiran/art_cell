@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Password Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item active">Change Password</li>
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
                <h3 class="card-title">Change Password</h3>
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
                      <form  method="post" id="admin_change_password_form" action="{{route('admin.profile.update_password')}}" method="post" >
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="current_password">Current Password <span class="error">*</span></label>
                            <input type="password" class="form-control"  name="current_password" id="current_password"  placeholder="Enter Current Password">
                            @if($errors->has('current_password'))
                            <span class="text-danger">{{$errors->first('current_password')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="new_password">New Password <span class="error">*</span></label>
                            <input type="password" class="form-control"  name="new_password" id="new_password"  placeholder="Enter New Password">
                            @if($errors->has('new_password'))
                            <span class="text-danger">{{$errors->first('new_password')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="confirm_password">Confirm New Password <span class="error">*</span></label>
                            <input type="password" class="form-control"  name="confirm_password" id="confirm_password"  placeholder="Confirm New Password">
                            @if($errors->has('confirm_password'))
                            <span class="text-danger">{{$errors->first('confirm_password')}}</span>
                            @endif
                          </div>
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
<script type="text/javascript" src="{{asset('js/admin/profile/change_password.js')}}"></script>
@endpush
