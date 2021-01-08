@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Laboure Leave Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.leave_management.leaveList')}}">Laboure Leave Management</a></li>
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
                          <td >{{$leaveData->userDetails->first_name}}</td>
                        </tr>
                        <tr>
                          <td >Last Name</td>
                          <td >{{$leaveData->userDetails->last_name}}</td>
                        </tr>
                        <tr>
                          <td >Email</td>
                          <td >{{$leaveData->userDetails->email}}</td>
                        </tr>
                        <tr>
                          <td >Phone/Contact Number</td>
                          <td >{{$leaveData->userDetails->phone}}</td>
                        </tr>
                        
                        
                        <tr>
                          <td>Leave On</td>
                         
                          <td> 
                            <?php //dd($leaveData->leave_dates);?>
                               <table>
                                <tr>
                                  @forelse(@$leaveData->leave_dates as $leaveDate)
                                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d', $leaveDate->leave_date)->format('d/m/Y')}}</td>
                                  @empty
                                    <td>No Date Found</td>
                                  @endforelse
                                </tr>
                              </table>
                           </td> 
                        </tr>
                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($leaveData->status=='Approved')?'success':'danger'}}">{{($leaveData->status=='Approved')?'Approved':'Declined'}}</button>
                          </td>
                        </tr>

                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.leaveList')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

