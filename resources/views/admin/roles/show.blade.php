@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('group_manage_module.module_title')}}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.roles.list')}}">{{__('general_sentence.breadcrumbs.user_groups')}}</a></li>
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
                  {{__('group_manage_module.group_details')}}
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="role-details-table">
                      <tbody>
                        <tr>
                          <td>{{__('group_manage_module.labels.group_name')}}</td>
                          <td >{{$role->role_name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('group_manage_module.labels.description')}}</td>
                          <td >{{$role->role_description}}</td>
                        </tr>
                        <tr>
                          <td>{{__('group_manage_module.labels.created_by')}}</td>
                          <td >{{$role->creator->name}} ({{$role->creator->email}})</td>
                        </tr>
                        <tr>
                          <td>{{__('group_manage_module.labels.user_type')}}</td>
                          <td >{{$role->user_type->name}}</td>
                        </tr>
                        <tr>
                          <td>{{__('group_manage_module.labels.status')}}</td>
                          <td>
                            <button role="button" class="btn btn-{{($role->status=='A')?'success':'danger'}}">{{($role->status=='A')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>
                        <tr>
                          <td >{{__('group_manage_module.labels.total_users')}}</td>
                          <td >{{$role->users()->count()}}</td>
                        </tr>
                        <tr>
                          <td >{{__('group_manage_module.labels.permissions')}}</td>
                          <td>
                            <div class="container-fluid">
                              <div class="row">
                                @if(count($modules))
                                  @foreach($modules as $module)
                                  <div class="col-sm-4">
                                    <div class="card card-success">
                                      <div  class="card-header">{{$module->module_name}}</div>
                                      <div  class="card-body">
                                        @if(count($module->functionalities))
                                          @foreach($module->functionalities as $functionality)
                                          
                                            <span class="">{{$functionality->function_name}}</span><br>
                                          
                                          @endforeach
                                        @endif
                                      </div>
                                    </div>
                                  </div>
                                  @endforeach
                                @else
                                <div class="col-sm-12">
                                  <p>No Module Found</p>
                                </div>
                                @endif
                              </div>                            
                            </div>

                          </td>
                        </tr>
                        <tr>
                          <td>{{__('group_manage_module.labels.created_at')}}</td>
                          <td>{{$role->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.roles.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a></td>
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

