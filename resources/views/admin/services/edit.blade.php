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
              <li class="breadcrumb-item"><a href="{{route('admin.services.list')}}">Services</a></li>
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
                <h3 class="card-title">Edit Service</h3>
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
                      <form  method="post" id="admin_service_edit_form" action="{{route('admin.services.update',$service->id)}}" method="post" >
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
                                <label for="en_service_name">Service Name (EN)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('en_service_name')?old('en_service_name'):(($service->translate('en'))?$service->translate('en')->service_name:'')}}" 

                                name="en_service_name" id="en_service_name"  placeholder="Service Name in English">
                                @if($errors->has('en_service_name'))
                                <span class="text-danger">{{$errors->first('en_service_name')}}</span>
                                @endif
                              </div>
                              <div class="form-group required">
                                 <label for="en_description">Description (EN)<span class="error">*</span></label>
                                 <textarea rows="5" class="form-control"  name="en_description" id="en_description"  placeholder="Description in English">{{old('en_description')?old('en_description'):(($service->translate('en'))?$service->translate('en')->description :'')}}</textarea>
                                 @if($errors->has('en_description'))
                                  <span class="text-danger">{{$errors->first('en_description')}}</span>
                                 @endif
                              </div>

                            </div>
                            
                          </div>
                          <div class="tab-pane" id="arabic" role="tabpanel">
                            <div>
                              <div class="form-group required">
                                <label for="ar_service_name">Service Name (AR)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('ar_service_name')?old('ar_service_name'):(($service->translate('ar'))?$service->translate('ar')->service_name:'')}}" name="ar_service_name" id="ar_service_name"  placeholder="Service Name in Arabic">
                                @if($errors->has('ar_service_name'))
                                <span class="text-danger">{{$errors->first('ar_service_name')}}</span>
                                @endif
                              </div>
                              <div class="form-group required">
                                 <label for="ar_description">Description (AR)<span class="error">*</span></label>
                                 <textarea rows="5" class="form-control"  name="ar_description" id="ar_description"  placeholder="Description in Arabic">{{old('ar_description')?old('ar_description'):(($service->translate('ar'))?$service->translate('ar')->description :'')}}</textarea>
                                 @if($errors->has('ar_description'))
                                  <span class="text-danger">{{$errors->first('ar_description')}}</span>
                                 @endif
                              </div>

                            </div>

                          </div>

                        </div>
                        <!--  this the url for remote validattion rule for role name -->
                        <input type="hidden" id="ajax_check_service_name_unique_url" value="{{route('admin.services.ajax_check_service_name_unique',$service->id)}}">
                        <div>
                           <a href="{{route('admin.services.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
