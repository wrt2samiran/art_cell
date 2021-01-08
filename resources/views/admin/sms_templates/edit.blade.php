@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('sms_template_manage_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.email.list')}}">{{__('general_sentence.breadcrumbs.sms_templates')}}</a></li>
              <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.edit')}}</li>
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
                <h3 class="card-title">{{__('sms_template_manage_module.edit_sms_template')}}</h3>
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
                      <form  method="post" id="admin_sms_template_edit_form" action="{{route('admin.sms_templates.update',$sms_template->id)}}" method="post" >
                        @csrf
                        @method('PUT')
                        <div>
                          <div class="form-group required">
                            <label for="name">{{__('sms_template_manage_module.labels.template_for')}}<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('template_name')?old('template_name'):$sms_template->template_name}}" name="template_name" id="template_name"  placeholder="{{__('sms_template_manage_module.placeholders.template_for')}}" readonly>
                            @if($errors->has('template_name'))
                            <span class="text-danger">{{$errors->first('template_name')}}</span>
                            @endif
                          </div>

                          <div class="form-group required">
                            <label for="variable_name">{{__('sms_template_manage_module.labels.variable_names')}}<span class="error">*</span></label>
                            <input type="text" class="form-control" value="{{old('variable_name')?old('variable_name'):$sms_template->variable_name}}" name="variable_name" id="variable_name"  placeholder="{{__('sms_template_manage_module.placeholders.variable_names')}}" readonly>
                            @if($errors->has('variable_name'))
                            <span class="text-danger">{{$errors->first('variable_name')}}</span>
                            @endif
                          </div>
                          <div class="form-group required">
                             <label for="description">{{__('sms_template_manage_module.labels.content')}} <span class="error">*</span></label>
                             <textarea rows="5" class="form-control"  name="content" id="content"  placeholder="{{__('sms_template_manage_module.placeholders.content')}}">{{old('content')?old('content'):$sms_template->content}}</textarea>
                             @if($errors->has('content'))
                              <span class="text-danger">{{$errors->first('content')}}</span>
                             @endif
                          </div>

                        
                        </div>
         
                        <div>
                           <a href="{{route('admin.sms_templates.list')}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a>
                           <button type="submit" class="btn btn-success">{{__('general_sentence.button_and_links.submit')}}</button> 
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

<script type="text/javascript" src="{{asset('js/admin/sms_templates/edit.js')}}"></script>
@endpush
