@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('user_manage_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.users.list')}}">{{__('general_sentence.breadcrumbs.users')}}</a></li>
              <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.edit')}}</li>
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
                <h3 class="card-title">{{__('user_manage_module.edit_user')}}</h3>
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
                      <form  method="post" id="admin_user_edit_form" action="{{route('admin.users.update',$user->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="first_name">{{__('user_manage_module.labels.first_name')}} <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('first_name')?old('first_name'):$user->first_name}}" name="first_name" id="first_name"  placeholder="{{__('user_manage_module.placeholders.first_name')}}">
                            @if($errors->has('first_name'))
                            <span class="text-danger">{{$errors->first('first_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="last_name">{{__('user_manage_module.labels.last_name')}} <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('last_name')?old('last_name'):$user->last_name}}" name="last_name" id="last_name"  placeholder="{{__('user_manage_module.placeholders.last_name')}}">
                            @if($errors->has('last_name'))
                            <span class="text-danger">{{$errors->first('last_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="email">{{__('user_manage_module.labels.email')}} <span class="error">*</span></label>
                            <input type="email" class="form-control" value="{{old('email')?old('email'):$user->email}}" name="email" id="email"  placeholder="{{__('user_manage_module.placeholders.email')}}">
                            @if($errors->has('email'))
                            <span class="text-danger">{{$errors->first('email')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="password">{{__('user_manage_module.labels.password')}}</label>
                            <input type="password" aria-describedby="passwordHelp" class="form-control" value="{{old('password')?old('password'):''}}" name="password" id="password"  placeholder="{{__('user_manage_module.placeholders.password')}}">
                            
                            <small id="passwordHelp" class="form-text text-muted">
                            {{__('user_manage_module.edit_password_help_text')}}</small>
                            @if($errors->has('password'))
                            <span class="text-danger">{{$errors->first('password')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="phone">{{__('user_manage_module.labels.contact_number')}} <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('phone')?old('phone'):$user->phone}}" name="phone" id="phone"  placeholder="{{__('user_manage_module.placeholders.contact_number')}}">
                            @if($errors->has('phone'))
                            <span class="text-danger">{{$errors->first('phone')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="parent_role">{{__('user_manage_module.labels.group')}}<span class="error">*</span></label>
                              <select class="form-control " id="role_id" name="role_id" style="width: 100%;">
                                <option value="">{{__('user_manage_module.placeholders.group')}}</option>
                                @forelse($roles as $role)
                                   <option value="{{$role->id}}" {{($role->id==$user->role_id)?'selected':''}}>{{$role->role_name}} - Created by {{$role->creator->name}} ({{$role->user_type->name}}) </option>
                                @empty
                                <option value="">No Group Found</option>
                                @endforelse
                              </select>
                          </div>
                        </div>
                        <!--  this the url for remote validattion rule for user email -->
                        <input type="hidden" id="ajax_check_user_email_unique" value="{{route('ajax.check_user_email_unique',$user->id)}}">
                        <div>
                           <a href="{{route('admin.users.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a>
                           <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.submit')}}</button> 
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
<script type="text/javascript" src="{{asset('js/admin/users/edit.js')}}"></script>
@endpush
