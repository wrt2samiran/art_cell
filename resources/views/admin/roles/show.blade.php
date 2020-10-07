@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Group/Role Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.roles.list')}}">Roles</a></li>
              <li class="breadcrumb-item active">Details</li>
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
                  Role Deatils
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="role-details-table">
                      <tbody>
                        <tr>
                          <td>Role Name</td>
                          <td >{{$role->role_name}}</td>
                        </tr>
                        <tr>
                          <td >Role Description</td>
                          <td >{{$role->role_description}}</td>
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($role->status=='A')?'success':'danger'}}">{{($role->status=='A')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>
                        <tr>
                          <td >Total Users</td>
                          <td >{{$role->users()->count()}}</td>
                        </tr>
                        <tr>
                          <td >Parent Role/Group</td>
                          <td >{{$role->parent? $role->parent->role_name:'--' }}</td>
                        </tr>
                        <tr>
                          <td >Child Role/Groups</td>
                          <td>
                            @if($role->childrens->count())
                              @foreach($role->childrens as $key=> $children)
                              <span>{{($key!=0)?',':''}}{{$children->role_name}}</span>
                              @endforeach
                            @else
                            No child groups
                            @endif
                            
                          </td>
                        </tr>
                        <tr>
                          <td >Module wise permissions</td>
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
                          <td>Created At</td>
                          <td>{{$role->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.roles.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

