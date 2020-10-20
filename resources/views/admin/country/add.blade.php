@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Country Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.country.list')}}">Country</a></li>
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
                <h3 class="card-title">Create Country</h3>
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
                      <form  method="post" id="admin_country_add_form" action="{{route('admin.country.country.add')}}" method="post" enctype="multipart/form-data">
                        <!-- @csrf
                        <div>
                          <div class="form-group required">
                            <label for="ar_name">Country Name (Arabic)<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('ar_name')?old('ar_name'):''}}" name="ar_name" id="ar_name"  placeholder="Please Enter Country Name">
                            @if($errors->has('ar_name'))
                            <span class="text-danger">{{$errors->first('ar_name')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="en_name">Country Name (English)<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('en_name')?old('en_name'):''}}" name="en_name" id="en_name"  placeholder="Please Enter Country Name">
                            @if($errors->has('en_name'))
                            <span class="text-danger">{{$errors->first('en_name')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="country_code">Code <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('country_code')?old('country_code'):''}}" name="country_code" id="country_code"  placeholder="Please Enter Code">
                            @if($errors->has('country_code'))
                            <span class="text-danger">{{$errors->first('country_code')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                            <label for="dial_code">Dial Code <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('dial_code')?old('dial_code'):''}}" name="dial_code" id="dial_code"  placeholder="Please Enter Dial Code">
                            @if($errors->has('dial_code'))
                            <span class="text-danger">{{$errors->first('dial_code')}}</span>
                            @endif
                          </div>
                          
                          
                        </div> -->

                        <ul class="nav nav-tabs" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#english" role="tab">English</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#arabic" role="tab">Arabic</a>
                          </li>
                        </ul><!-- Tab panes -->
                        @csrf
                        <div class="tab-content tab-validate pt-3">
                          <div class="tab-pane active" id="english" role="tabpanel">
                                <div class="form-group">
                                    <label class="required" for="en_title">Country Name (ENGLISH)</label>
                                    <input class="form-control {{ $errors->has('en_name') ? 'is-invalid' : '' }}" type="text" name="en_name" id="en_name" value="{{ old('en_name', '') }}" >
                                    @if($errors->has('en_name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('en_name') }}
                                        </div>
                                    @endif
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group required">
                                  <label for="country_code">Code <span class="error">*</span></label>
                                  <input type="text" class="form-control" value="{{old('country_code')?old('country_code'):''}}" name="country_code" id="country_code"  placeholder="Please Enter Code">
                                  @if($errors->has('country_code'))
                                    <span class="text-danger">{{$errors->first('country_code')}}</span>
                                  @endif
                              </div>
                              <div class="form-group required">
                                  <label for="dial_code">Dial Code <span class="error">*</span></label>
                                  <input type="text" class="form-control" value="{{old('dial_code')?old('dial_code'):''}}" name="dial_code" id="dial_code"  placeholder="Please Enter Dial Code">
                                  @if($errors->has('dial_code'))
                                      <span class="text-danger">{{$errors->first('dial_code')}}</span>
                                  @endif
                              </div>
                            </div>
                            <div class="tab-pane" id="arabic" role="tabpanel">
                                   <div class="form-group">
                                    <label class="required" for="title">Country Name (ARABIC)</label>
                                    <input class="form-control {{ $errors->has('ar_name') ? 'is-invalid' : '' }}" type="text" name="ar_name" id="ar_title" value="{{ old('ar_name', '') }}" >
                                    @if($errors->has('ar_name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('ar_name') }}
                                        </div>
                                    @endif
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div>
                           <a href="{{route('admin.country.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/country/create.js')}}"></script>
@endpush
