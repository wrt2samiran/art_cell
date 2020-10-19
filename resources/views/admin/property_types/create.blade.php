@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Property Type Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.property_types.list')}}">Property Types</a></li>
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
                <h3 class="card-title">Create Property Type</h3>
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
                      <form  method="post" id="admin_property_type_create_form" action="{{route('admin.property_types.store')}}" method="post" >
                        @csrf

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
                                <label for="en_type_name">Type Name (EN)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('en_type_name')?old('en_type_name'):''}}" name="en_type_name" id="en_type_name"  placeholder="Type Name in English">
                                @if($errors->has('en_type_name'))
                                <span class="text-danger">{{$errors->first('en_type_name')}}</span>
                                @endif
                              </div>
                              <div class="form-group required">
                                 <label for="en_description">Description (EN)<span class="error">*</span></label>
                                 <textarea rows="5" class="form-control"  name="en_description" id="en_description"  placeholder="Description in English">{{old('en_description')?old('en_description'):''}}</textarea>
                                 @if($errors->has('en_description'))
                                  <span class="text-danger">{{$errors->first('en_description')}}</span>
                                 @endif
                              </div>

                            </div>
                            
                          </div>
                          <div class="tab-pane" id="arabic" role="tabpanel">
                            <div>
                              <div class="form-group required">
                                <label for="ar_type_name">Type Name (AR)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('ar_type_name')?old('ar_type_name'):''}}" name="ar_type_name" id="ar_type_name"  placeholder="Type Name in Arabic">
                                @if($errors->has('ar_type_name'))
                                <span class="text-danger">{{$errors->first('ar_type_name')}}</span>
                                @endif
                              </div>
                              <div class="form-group required">
                                 <label for="ar_description">Description (AR)<span class="error">*</span></label>
                                 <textarea rows="5" class="form-control"  name="ar_description" id="ar_description"  placeholder="Description in Arabic">{{old('ar_description')?old('ar_description'):''}}</textarea>
                                 @if($errors->has('ar_description'))
                                  <span class="text-danger">{{$errors->first('ar_description')}}</span>
                                 @endif
                              </div>

                            </div>

                          </div>

                        </div>

                        <!--  this the url for remote validattion rule for role name -->
                        <input type="hidden" id="ajax_check_type_name_unique_url" value="{{route('admin.property_types.ajax_check_type_name_unique')}}">
                        <div>
                           <a href="{{route('admin.property_types.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/property_types/create.js')}}"></script>
@endpush
