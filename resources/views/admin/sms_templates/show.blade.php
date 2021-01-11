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
              <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.details')}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
                <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card card-success">
                <div class="card-header">
                  {{__('sms_template_manage_module.sms_template_details')}}
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover" id="sms-template-details-table">
                      <tbody>
                        <tr>
                          <td>{{__('sms_template_manage_module.labels.template_for')}}</td>
                          <td>{{$sms_template->template_name}}</td>
                        </tr>
  
                        <tr>
                          <td>{{__('sms_template_manage_module.labels.variable_names')}}</td>
                          <td>{{ $sms_template->variable_name }}</td>
                        </tr>

                        <tr>
                          <td>{{__('sms_template_manage_module.labels.content')}}</td>
                          <td>{!! $sms_template->content !!}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.sms_templates.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a></td>
                        </tr>
                      </tfoot>
                  </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
</div>
@endsection 

