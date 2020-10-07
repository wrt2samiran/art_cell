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
                 <table class="table table-bordered table-hover" id="role-details-table">
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
                          <td >Parent Role/Group</td>
                          <td >{{$role->parent? $role->parent->role_name:'--' }}</td>
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

