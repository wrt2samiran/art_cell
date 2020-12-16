@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Laboure Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.labour.list')}}">Laboure</a></li>
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
                Labour Details
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="service-provider-details-table">
                      <tbody>
                        <tr>
                          <td>First Name</td>
                          <td >{{$user->first_name}}</td>
                        </tr>
                        <tr>
                          <td >Last Name</td>
                          <td >{{$user->last_name}}</td>
                        </tr>
                        <tr>
                          <td >Email</td>
                          <td >{{$user->email}}</td>
                        </tr>
                        <tr>
                          <td >Phone/Contact Number</td>
                          <td >{{$user->phone}}</td>
                        </tr>
                         <tr>
                          <td >Country</td>
                          <td >{{@$user->country->name}}</td>
                        </tr>
                        <tr>
                          <td >State</td>
                          <td >{{@$user->state->name}}</td>
                        </tr>
                        <tr>
                          <td >City</td>
                          <td >{{@$user->city->name}}</td>
                        </tr>
                        <tr>
                          <td >Skills</td>
                           <td> 
                               <table>
                                <tr>
                                  @forelse(@$user->user_skills as $skillData)
                                    <td>{{$skillData->skill->skill_title}}</td>
                                  @empty
                                    <td>No Skill Found</td>
                                  @endforelse
                                </tr>
                              </table>
                           </td>  
                        </tr>
                        <tr>
                          <td >Weekly Off Day</td>
                          <td >{{ucfirst($user->weekly_off)}}</td>
                        </tr>
                        
                        
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($user->status=='A')?'success':'danger'}}">{{($user->status=='A')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>

                        <tr>
                          <td>Created At</td>
                          <td>{{$user->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.labour.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

