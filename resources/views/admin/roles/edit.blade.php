@extends('admin.layouts.after-login-layout')

@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User Groups Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.roles.list')}}">Groups</a></li>
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
                <h3 class="card-title">Edit Group</h3>
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
                      <form  method="post" id="admin_roles_edit_form" action="{{route('admin.roles.update',$role->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="role_name">Group Name <span class="error">*</span></label>
                            <input type="text"  class="form-control" value="{{old('role_name')?old('role_name'):$role->role_name}}" name="role_name" id="role_name"  placeholder="Group Name">
                            @if($errors->has('role_name'))
                            <span class="text-danger">{{$errors->first('role_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="role_description">Group Description <span class="error">*</span></label>
                             <textarea rows="5" class="form-control"  name="role_description" id="role_description"  placeholder="Group Description">{{old('role_description')?old('role_description'):$role->role_description}}</textarea>
                             @if($errors->has('role_description'))
                              <span class="text-danger">{{$errors->first('role_description')}}</span>
                             @endif
                          </div>
                          @if($current_user->can_select_user_type_during_group_creation())
                            @php
                            if($role->is_main_role || ($role->creator->role && $role->creator->role->user_type->slug!='super-admin')){
                              $disabled='disabled';
                            }else{
                              $disabled='';
                            }
                            @endphp
                          <div class="form-group required">
                             <label for="user_type_id">Please select user type you creating the group for<span class="error">*</span></label>
                              <select {{$disabled}} class="form-control user_type_select2" name="user_type_id" id="user_type_id" style="width: 100%;">
                                <option value="">Select user type</option>
                                @forelse($user_types as $user_type)
                                   <option value="{{$user_type->id}}" {{($user_type->id==$role->user_type_id)?'selected':''}}>{{$user_type->name}}</option>
                                @empty
                                <option value="">No user type found</option>
                                @endforelse                                
                              </select>
                              <div id="user_type_error"></div>
                          </div>
                          @endif

                        <div id="permissions_error" class="text-danger"></div>
                        <div id="module_permissions_container">
                          <div class="row">
                            @if(count($modules))
                              @foreach($modules as $module)
                              <div class="col-sm-4">
                                <div class="card card-success">
                                  <div  class="card-header">{{$module->module_name}}</div>
                                  <div  class="card-body" id="module_no_{{$module->id}}">
                                    @if(count($module->functionalities))
                                      @foreach($module->functionalities as $functionality)
                                      <div class="custom-control custom-checkbox">
                         
                                        <input  class="custom-control-input permission_checkbox" type="checkbox" id="permission_{{$module->id}}_{{$functionality->slug}}" name="functionalities[]" value="{{$functionality->id}}"
                                        {{(in_array($functionality->id,$current_functionalities_id_array))?'checked':''}}
                                        >
                                        <label for="permission_{{$module->id}}_{{$functionality->slug}}" class="custom-control-label">{{$functionality->function_name}}</label>

                                      </div>
                                      @endforeach
                                    @endif
                                  </div>
                                </div>
                              </div>
                              @endforeach
                            @else
                            <div class="col-sm-12">
                              <p>No Module Found</p>
                            </div>
                            @endif
                          </div>
                        </div>
                        </div>
                        <!--  this the url for remote validattion rule for role name -->
                        <input type="hidden" id="ajax_check_role_name_unique_url" value="{{route('admin.roles.ajax_check_role_name_unique',$role->id)}}">
                        <div>
                           <a href="{{route('admin.roles.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/roles/edit.js')}}"></script>
@endpush
