@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Group/Role Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.roles.list')}}">Roles</a></li>
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
                <h3 class="card-title">Create Role</h3>
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
                      <form  method="post" id="admin_roles_create_form" action="{{route('admin.roles.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                          <div class="form-group required">
                            <label for="role_name">Role Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('role_name')?old('role_name'):''}}" name="role_name" id="role_name"  placeholder="Category Name">
                            @if($errors->has('role_name'))
                            <span class="text-danger">{{$errors->first('role_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="role_description">Role Description <span class="error">*</span></label>
                             <textarea rows="5" class="form-control"  name="role_description" id="role_description"  placeholder="Role Description">{{old('role_description')?old('role_description'):''}}</textarea>
                             @if($errors->has('role_description'))
                              <span class="text-danger">{{$errors->first('role_description')}}</span>
                             @endif
                          </div>
                          <div class="form-group required">
                             <label for="parent_role">Role for which group <span class="error">*</span></label>
                              <select class="form-control select2" style="width: 100%;" name="parent_role">
                                <option value="">Select a group</option>
                                @forelse($parent_roles as $parent_role)
                                   <option value="{{$parent_role->id}}" {{(old('parent_role') && old('menu_category')== $parent_role->id)? 'selected':''}}>{{$parent_role->role_name}}</option>
                                @empty
                               <option value="">No Parent Group Found</option>
                                @endforelse
            
                              </select>
                             @if($errors->has('parent_role'))
                              <span class="text-danger">{{$errors->first('parent_role')}}</span>
                             @endif
                          </div>
                        </div>
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
<script type="text/javascript" src="{{asset('js/admin/roles/create.js')}}"></script>
@endpush
