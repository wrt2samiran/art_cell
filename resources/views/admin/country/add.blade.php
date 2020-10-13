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
                        @csrf
                        <div>
                          <div class="form-group required">
                            <label for="name">Country Name <span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('name')?old('name'):''}}" name="name" id="name"  placeholder="Please Enter Country Name">
                            @if($errors->has('name'))
                            <span class="text-danger">{{$errors->first('name')}}</span>
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
