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
                          <td >{{$work_order_list->contract_services->service_type}} Date</td>
                          <td >{{$work_order_list->start_date}}</td>
                        </tr>
                        <tr>
                          <td >Service Provider</td>
                          <td >{{$work_order_list->userDetails->name}}</td>
                        </tr>
                        <tr>
                          <td> Date of Service</td> 
                          <td >
                            @if(@$work_order_list->contract_services->service_type=='Maintenance'  and @$work_order_list->contract_service_recurrence->interval_type != 'daily') 
                              <table class="table table-bordered" >
                                  <tr> 
                                        <tr>
                                          <td scope="col" style="text-align:center;" colspan="6"><strong>Date</strong> </td>
                                        </tr>
                                       @foreach(@$work_order_list->contract_service_dates as $valueDate)
                                          <td>
                                           {{Carbon\Carbon::createFromFormat('Y-m-d', @$valueDate->date)->format('d/m/Y')}}                             
                                          </td>
                                       @endforeach 
                                  </tr>
                              </table>
                            @elseif(@$work_order_list->contract_services->service_type=='Maintenance'  and @$work_order_list->contract_service_recurrence->interval_type == 'daily')  
                            <div class="scrollit" style="overflow:scroll; height:300px;">
                              <table class="table table-bordered">

                                @php $arraySlot = array('1'=> 'First Slot', '2'=> 'Second Slot', '3'=> 'Third Slot', '4'=> 'Fourth Slot', '5'=> 'Fifth Slot', '6'=>'Sixth Slot', '7'=> 'Seventh Slot', '8'=> 'Eight Slot', '9'=>'Nineth Slot', '10'=>'Tenth Slot'); @endphp
                                @if(!empty($available_dates))
                                  <tr>
                                    <td scope="col"><strong>Date</strong> </td>
                                    <td scope="col" colspan="{{count($slot_data)/count(@$available_dates)}}" style="text-align:center;"><strong>Slot</strong></td>
                                  </tr>
                                 @foreach(@$available_dates as $valueDate)
                                  
                                  <tr>
                                    <td scope="row">    
                                    {{@$valueDate->contract_service_dates->date}} 
                                  </td>

                                        @foreach($slot_data as $slotValue)
                                          @if(@$valueDate->contract_service_dates->id == $slotValue->contract_service_date_id)
                                          <td scope="row">
                                            @if(array_key_exists($slotValue->daily_slot, $arraySlot)){{$arraySlot[$slotValue->daily_slot]}}@endif
                                          </td>
                                          @endif 
                                        @endforeach 
                                  </tr>        
                                  @endforeach     
                                 @endif     
                              </table>   
                            </div>
                            @else
                              {{Carbon\Carbon::createFromFormat('Y-m-d', @$work_order_list->start_date)->format('d/m/Y')}}  
                            @endif   
                          </td>
                        </tr>
                        <tr>
                          <td>Completed</td> 
                          <td ><div class="progress"><div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="{{$work_order_list->work_order_complete_percent}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$work_order_list->work_order_complete_percent}}%">{{$work_order_list->work_order_complete_percent}}% </div></div></td>
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

