@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Service Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.skills.list')}}">Skill</a></li>
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
                <h3 class="card-title">Edit Skill</h3>
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
                      <form  method="post" id="admin_service_edit_form" action="{{route('admin.skills.update',$skill->id)}}" method="post" >
                        @csrf
                        @method('PUT')
                        <ul class="nav nav-tabs" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#english" role="tab">English</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#arabic" role="tab">Arabic</a>
                          </li>
                        </ul><!-- Tab panes -->
                        <div class="tab-content tab-validate pt-3">
                          <div class="tab-pane active" id="english" role="tabpanel">
                            <div>
                              <div class="form-group required">
                                <label for="en_service_name">Skill Title (EN)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('en_skill_title')?old('en_skill_title'):(($skill->translate('en'))?$skill->translate('en')->skill_title:'')}}" 

                                name="en_skill_title" id="en_skill_title"  placeholder="Skill Title in English">
                                @if($errors->has('en_skill_title'))
                                <span class="text-danger">{{$errors->first('en_skill_title')}}</span>
                                @endif
                              </div>
                            </div>
                            
                          </div>
                          <div class="tab-pane" id="arabic" role="tabpanel">
                            <div>
                              <div class="form-group required">
                                <label for="ar_service_name">Skill Title (AR)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('ar_skill_title')?old('ar_skill_title'):(($skill->translate('ar'))?$skill->translate('ar')->skill_title:'')}}" name="ar_skill_title" id="ar_skill_title"  placeholder="Skill Title in Arabic">
                                @if($errors->has('ar_skill_title'))
                                <span class="text-danger">{{$errors->first('ar_skill_title')}}</span>
                                @endif
                              </div>

                            </div>

                          </div>

                        </div>
                        <hr>
                        <div class="form-group required">
                          <label for="price">Price ({{Helper::getSiteCurrency()}})<span class="error">*</span></label>
                          <select class="form-control status-filter"  name="role_id" id="role_id">
                            <option value="">Select Role</option>
                              @forelse($user_role as $roleData)
                                 <option value="{{$roleData->id}}" @if($roleData->id==@$skill->role_id) selected @endif >{{$roleData->role_name}}</option>
                              @empty
                              <option value="">No Role Found</option>
                              @endforelse
                          </select>  
                          @if($errors->has('role_id'))
                          <span class="text-danger">{{$errors->first('role_id')}}</span>
                          @endif

                        </div>
                        
                        <div>
                           <a href="{{route('admin.skills.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/services/edit.js')}}"></script>
@endpush
