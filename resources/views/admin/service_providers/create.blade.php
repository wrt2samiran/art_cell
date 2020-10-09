@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Service Provider Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.service_providers.list')}}">Service Providers</a></li>
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
                <h3 class="card-title">Create Service Provider</h3>
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
                      <form  method="post" id="admin_service_provider_create_form" action="{{route('admin.service_providers.store')}}" method="post">
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
                            <label for="email">Email <span class="error">*</span></label>
                            <input type="email" class="form-control" value="{{old('email')?old('email'):''}}" name="email" id="email"  placeholder="Last Name">
                            @if($errors->has('email'))
                            <span class="text-danger">{{$errors->first('email')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="password">Password <span class="error">*</span></label>
                            <input type="password" class="form-control" value="{{old('password')?old('password'):''}}" name="password" id="password"  placeholder="Password">
                            @if($errors->has('password'))
                            <span class="text-danger">{{$errors->first('password')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="phone">Phone/Contact Number <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('phone')?old('phone'):''}}" name="phone" id="phone"  placeholder="Phone/Contact Number">
                            @if($errors->has('phone'))
                            <span class="text-danger">{{$errors->first('phone')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="parent_role">Group/Role</label>
                              <select class="form-control " disabled style="width: 100%;">
                                <option value="">Select a group</option>
                                @forelse($roles as $role)
                                   <option value="{{$role->id}}" selected>{{$role->role_name}}</option>
                                @empty
                                <option value="">No Service Provider Group Found</option>
                                @endforelse
                              </select>
                          </div>
                        </div>
                        <!--  this the url for remote validattion rule for role name -->
                        <input type="hidden" id="ajax_check_user_email_unique" value="{{route('ajax.check_user_email_unique')}}">
                        <div>
                           <a href="{{route('admin.service_providers.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/service_providers/create.js')}}"></script>
@endpush
