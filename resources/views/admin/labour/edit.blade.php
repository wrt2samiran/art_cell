@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.labour.list')}}">Labour</a></li>
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
                <h3 class="card-title">Edit labour</h3>
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
                      <form  method="post" id="labour_edit_form" action="{{route('admin.labour.update',$user->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="first_name">First Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('first_name')?old('first_name'):$user->first_name}}" name="first_name" id="first_name"  placeholder="First Name">
                            @if($errors->has('first_name'))
                            <span class="text-danger">{{$errors->first('first_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="last_name">Last Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('last_name')?old('last_name'):$user->last_name}}" name="last_name" id="last_name"  placeholder="Last Name">
                            @if($errors->has('last_name'))
                            <span class="text-danger">{{$errors->first('last_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="email">Email <span class="error">*</span></label>
                            <input type="email" class="form-control" value="{{old('email')?old('email'):$user->email}}" name="email" id="email"  placeholder="Last Name">
                            @if($errors->has('email'))
                            <span class="text-danger">{{$errors->first('email')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="password">Password</label>
                            <input type="password" aria-describedby="passwordHelp" class="form-control" value="{{old('password')?old('password'):''}}" name="password" id="password"  placeholder="Password">
                            
                            <small id="passwordHelp" class="form-text text-muted">Leave blank if you do not want to update password.</small>
                            @if($errors->has('password'))
                            <span class="text-danger">{{$errors->first('password')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="phone">Phone/Contact Number <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('phone')?old('phone'):$user->phone}}" name="phone" id="phone"  placeholder="Phone/Contact Number">
                            @if($errors->has('phone'))
                            <span class="text-danger">{{$errors->first('phone')}}</span>
                            @endif
                          </div>

                          <div class="form-group">
                            <label for="weekly_off">Select Working Day</label>
                             <select class="form-control " id="weekly_off" name="weekly_off" style="width: 100%;">
                               <option value="">Select Working Day</option>
                               <option {{old('weekly_off',$user->weekly_off)=="monday"? 'selected':''}} value="monday">Monday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="tuesday"? 'selected':''}} value="tuesday">Tuesday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="wednesday"? 'selected':''}} value="wednesday">Wednesday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="thursday"? 'selected':''}} value="thursday">Thursday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="friday"? 'selected':''}} value="friday">Friday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="saturday"? 'selected':''}} value="saturday">Saturday</option>
                               <option {{old('weekly_off',$user->weekly_off)=="sunday"? 'selected':''}} value="sunday">Sunday</option>
                             </select>
                         </div>
                         
                          <div class="form-group">
                            <label for="start_time">Select a Start time:</label>
                            <input class="form-control" type="time" id="start_time" name="start_time" value="{{old('start_time')?old('start_time'):$user->start_time}}">
                          </div>
                          <div class="form-group">
                            <label for="end_time">Select a End time:</label>
                            <input class="form-control" type="time" id="end_time" name="end_time" value="{{old('end_time')?old('end_time'):$user->end_time}}">
                          </div>
                          
                        </div>
                        <!--  this the url for remote validattion rule for user email -->
                        <input type="hidden" id="ajax_check_user_email_unique" value="{{route('ajax.check_user_email_unique',$user->id)}}">
                        <div>
                           <a href="{{route('admin.labour.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/labour/edit.js')}}"></script>
@endpush
