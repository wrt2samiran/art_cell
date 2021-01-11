@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Unit Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.unit.list')}}">Unit</a></li>
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
                <h3 class="card-title">Edit Unit</h3>
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
                      <form  method="post" id="unit_edit_form" action="{{route('admin.unit.update',$unit->id)}}">
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
                                <label for="en_unit_name">Unit Name (EN)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('en_unit_name')?old('en_unit_name'):(($unit->translate('en'))?$unit->translate('en')->unit_name:'')}}" name="en_unit_name" id="en_unit_name"  placeholder="Unit Name in English">
                                @if($errors->has('en_unit_name'))
                                <span class="text-danger">{{$errors->first('en_unit_name')}}</span>
                                @endif
                              </div>
                            </div>
                          </div>
                          <div class="tab-pane" id="arabic" role="tabpanel">
                            <div>
                              <div class="form-group required">
                                <label for="ar_unit_name">Unit Name (AR)<span class="error">*</span></label>
                                <input type="text" class="form-control" value="{{old('ar_unit_name')?old('ar_unit_name'):(($unit->translate('ar'))?$unit->translate('ar')->unit_name:'')}}" name="ar_unit_name" id="ar_unit_name"  placeholder="Unit Name in Arabic">
                                @if($errors->has('ar_unit_name'))
                                <span class="text-danger">{{$errors->first('ar_unit_name')}}</span>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                        <!--  this the url for remote validation rule for unit name -->
                        <input type="hidden" id="ajax_check_unit_name_unique_url" value="{{route('admin.unit.ajax_check_unit_name_unique',$unit->id)}}">
                        <div>
                           <a href="{{route('admin.unit.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
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
<script type="text/javascript" src="{{asset('js/admin/unit/edit.js')}}"></script>
@endpush
