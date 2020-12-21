@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Work Order Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              
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
                  Work Order Deatils
                </div> 
              <div class="card-body"> 
                  <table class="table table-bordered table-hover" id="country-details-table">
                      <tbody>
                

                       
                        <tr>
                          <td >Contract Id</td>
                          <td >{{$work_order_list->contract->code}}</td>
                        </tr>
                        <tr>
                          <td >Title</td>
                          <td >{{$work_order_list->task_title}}</td>
                        </tr>
                        <tr>
                          <td >Details</td>
                          <td >{!!$work_order_list->task_desc!!}</td>
                        </tr>
                        <tr>
                          <td >Property Name</td>
                          <td >{{$work_order_list->property->property_name}}</td>
                        </tr>
                        <tr>
                          <td >Service</td>
                          <td >{{$work_order_list->service->service_name}}</td>
                        </tr>
                        <tr>
                          <td >Service Type</td>
                          <td >{{$work_order_list->contract_services->service_type}}</td>
                        </tr>
                        <tr>
                          <td >Country</td>
                          <td >{{$work_order_list->property->country->name}}</td>
                        </tr>
                        <tr>
                          <td >State</td>
                          <td >{{$work_order_list->property->state->name}}</td>
                        </tr>
                        <tr>
                          <td >City</td>
                          <td >{{$work_order_list->property->city->name}}</td>
                        </tr>
                        <tr>
                          <td >Start Date</td>
                          <td >{{$work_order_list->start_date}}</td>
                        </tr>
                        
                       
                        <tr>
                          <td>Status</td>
                          <td>
                            @php
                              if($work_order_list->status=='0'){
                                  $button = 'warning';
                                  $status = 'Pending';
                                }
                                
                              else if($work_order_list->status=='1'){
                                  $button = 'danger';
                                  $status = 'Over Due';

                                }
                                
                              else{

                                  $button = 'success';
                                  $status = 'Completed';
                                }
                                
                              @endphp  

                            <button role="button" class="btn btn-{{$button}}">{{$status}}</button>
                            
                          </td>
                        </tr>
                        <tr>
                          <td>Completed</td>
                          <td>
                              <div class="progress">
                                <div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="{{$work_order_list->work_order_complete_percent}}"
                                aria-valuemin="0" aria-valuemax="100" style="width:{{$work_order_list->work_order_complete_percent}}%">
                                  {{$work_order_list->work_order_complete_percent}}% Complete
                                </div>
                              </div>
                          </td>
                        </tr>
                       
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.work-order-management.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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

