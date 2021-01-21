@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Status Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.statuses.list')}}">Statuses</a></li>
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
                <h3 class="card-title">Create Status</h3>
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
                      <form  method="post" id="admin_status_create_form" action="{{route('admin.statuses.store')}}" method="post" >
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
                                <label for="en_status_name">Status Name (EN)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('en_status_name')?old('en_status_name'):''}}" name="en_status_name" id="en_status_name"  placeholder="Status Name in English">
                                @if($errors->has('en_status_name'))
                                <span class="text-danger">{{$errors->first('en_status_name')}}</span>
                                @endif
                              </div>
                            </div>
                          </div>
                          <div class="tab-pane" id="arabic" role="tabpanel">
                            <div>
                              <div class="form-group required">
                                <label for="ar_status_name">Status Name (AR)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('ar_status_name')?old('ar_status_name'):''}}" name="ar_status_name" id="ar_status_name"  placeholder="Status Name in Arabic">
                                @if($errors->has('ar_status_name'))
                                <span class="text-danger">{{$errors->first('ar_status_name')}}</span>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                        <hr>
                        <div class="form-group required">
                          <label for="status_for">Status For<span class="error">*</span></label>
                          <select class="form-control " id="status_for" name="status_for" style="width: 100%;">
                            <option value="">Status For</option>
                            @forelse($status_for as $for)
                               <option value="{{$for->status_for}}" >{{ucfirst($for->status_for)}}</option>
                            @empty
                            @endforelse
                          </select>
                        </div>
                        <div class="form-group required">
                          <label for="color_code">Color Code<span class="error">*</span></label>
                          <input type="color" class="form-control" value="{{old('color_code')?old('color_code'):''}}" name="color_code" id="color_code"  placeholder="Color Code">
                          @if($errors->has('color_code'))
                          <span class="text-danger">{{$errors->first('color_code')}}</span>
                          @endif
                        </div>
                        <input type="hidden" id="ajax_check_status_name_unique_url" value="{{route('admin.statuses.ajax_check_status_name_unique')}}">
                        <div>
                           <a href="{{route('admin.statuses.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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

<script type="text/javascript" src="{{asset('js/admin/statuses/create.js')}}"></script>
@endpush
