@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('user_manage_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.users.list')}}">{{__('general_sentence.breadcrumbs.users')}}</a></li>
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
                  {{__('user_manage_module.user_details')}}
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="service-provider-details-table">
                      <tbody>
                        <tr>
                          <td>{{__('user_manage_module.labels.first_name')}}</td>
                          <td >{{$user->first_name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('user_manage_module.labels.last_name')}}</td>
                          <td >{{$user->last_name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('user_manage_module.labels.email')}}</td>
                          <td >{{$user->email}}</td>
                        </tr>
                        <tr>
                          <td >{{__('user_manage_module.labels.contact_number')}}</td>
                          <td >{{$user->phone}}</td>
                        </tr>
                        <tr>
                          <td >{{__('user_manage_module.labels.secondary_contact_number')}}</td>
                          <td >{{$user->secondary_contact_number?$user->secondary_contact_number:'Not Available'}}</td>
                        </tr>


                        <tr>
                          <td >{{__('user_manage_module.labels.group')}}</td>
                          <td >{{$user->role->role_name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('user_manage_module.labels.status')}}</td>
                          <td>
                            <button role="button" class="btn btn-{{($user->status=='A')?'success':'danger'}}">{{($user->status=='A')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>

                        <tr>
                          <td>{{__('user_manage_module.labels.created_at')}}</td>
                          <td>{{$user->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.users.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a></td>
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

