@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Settings Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.settings')}}">Settings</a></li>
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
                <h3 class="card-title">Edit Settings</h3>
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
                      <form  method="post" id="admin_update_setting_form" action="{{route('admin.updateSetting')}}">
                            @csrf
                            @method('PUT')
                            <div>

                              @if(count($settings))
                                 @foreach($settings as $setting)

                                  @if($setting->input_type=='textarea')

                                     <div class="form-group required">
                                      <label for="{{$setting->slug}}">{{$setting->name}}</label>

                                      <textarea class="form-control required" rows="3" name="{{$setting->slug}}" id="{{$setting->slug}}"  placeholder="Enter {{$setting->name}}">{{$setting->value}}</textarea>
                                      @if($errors->has($setting->slug))
                                      <span class="text-danger">{{$errors->first($setting->slug)}}</span>
                                      @endif
                                    </div>
                                  @else
                                    <div class="form-group required">
                                      <label for="{{$setting->slug}}">{{$setting->name}}</label>

                                      @if($setting->slug=='tax')
                                      <input type="{{$setting->input_type}}" class="form-control required" name="{{$setting->slug}}" id="{{$setting->slug}}" min="0.0" step="0.1" min="0.0" max="100" placeholder="Enter {{$setting->name}}" value="{{$setting->value}}">
                                      @else
                                      <input type="{{$setting->input_type}}" class="form-control required" name="{{$setting->slug}}" id="{{$setting->slug}}" value="{{$setting->value}}"  placeholder="Enter {{$setting->name}}">
                                      @endif
                        

                                      @if($errors->has($setting->slug))
                                      <span class="text-danger">{{$errors->first($setting->slug)}}</span>
                                      @endif
                                    </div>
 
                                  @endif



                                 @endforeach
                              @else
                               <p>No settings found</p>
                              @endif

                            </div>
                            <div>
                              <button type="submit" class="btn btn-primary">Update</button>
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
<script type="text/javascript" src="{{asset('js/admin/shared_service/edit.js')}}"></script>
@endpush
