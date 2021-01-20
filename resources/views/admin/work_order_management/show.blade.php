@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('work_order_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  
                  <li class="breadcrumb-item active">{{__('work_order_module.details')}}</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
              @if(Session::has('success-message'))
                  <div class="alert alert-success alert-dismissable">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('success-message') }}
                  </div>
              @endif
              @if(Session::has('error'))
                  <div class="alert alert-danger alert-dismissable">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('error') }}
                  </div>
              @endif

              <table class="table table-bordered table-hover" id="country-details-table">
                <tbody>
                  <?php //dd($service_allocation_data);?>
                        <tr>
                          <td >{{__('work_order_module.work_order_task_column_name.contract_id')}}</td>
                          <td >{{$work_order_list->contract->code}}</td>
                        </tr>
                        <tr>
                          <td >{{__('work_order_module.work_order_task_column_name.title')}}</td>
                          <td >{{$work_order_list->task_title}}</td>
                        </tr>
                        <tr>
                          <td >{{__('work_order_module.details')}}</td>
                          <td >{!!$work_order_list->task_desc!!}</td>
                        </tr>
                        <tr>
                          <td >{{__('work_order_module.work_order_task_column_name.property_name')}}</td>
                          <td >{{$work_order_list->property->property_name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('work_order_module.work_order_task_column_name.service')}}</td>
                          <td >{{$work_order_list->service->service_name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('work_order_module.work_order_task_column_name.service_type')}}</td>
                          <td >{{$work_order_list->contract_services->service_type}}</td>
                        </tr>
                        <tr>
                          <td >{{__('general_sentence.breadcrumbs.country')}}</td>
                          <td >{{$work_order_list->property->country->name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('general_sentence.breadcrumbs.state')}}</td>
                          <td >{{$work_order_list->property->state->name}}</td>
                        </tr>
                        <tr>
                          <td >{{__('general_sentence.breadcrumbs.city')}}</td>
                          <td >{{$work_order_list->property->city->name}}</td>
                        </tr>
                        <tr>
                          <td >{{$work_order_list->contract_services->service_type}} Date</td>
                          <td >{{$work_order_list->start_date}}</td>
                        </tr>
                        <tr>
                          <td >{{__('work_order_module.work_order_task_column_name.service_type')}}</td>
                          <td >{{$work_order_list->userDetails->name}}</td>
                        </tr>
                        <tr>
                          <td> {{__('work_order_module.work_order_task_column_name.date_of_service')}}</td> 
                          <td >
                            @if(@$work_order_list->contract_services->service_type=='Maintenance'  and @$work_order_list->contract_service_recurrence->interval_type != 'daily') 
                              <table class="table table-bordered" >
                                  <tr> 
                                        <tr>
                                          <td scope="col" style="text-align:center;" colspan="6"><strong>{{__('work_order_module.date')}}</strong> </td>
                                        </tr>
                                       @foreach(@$work_order_list->contract_service_dates as $valueDate)
                                          <td>
                                           {{Carbon\Carbon::createFromFormat('Y-m-d', @$valueDate->date)->format('d/m/Y')}}                             
                                          </td>
                                       @endforeach 
                                  </tr>
                              </table>
                            @elseif(@$work_order_list->contract_services->service_type=='Maintenance'  and @$work_order_list->contract_service_recurrence->interval_type == 'daily')  
                              @if(count(@$all_available_dates)>0)
                                <div class="scrollit" style="overflow:scroll; height:300px;">
                                  <table class="table table-bordered">
                                    
                                    @php $arraySlot = array('1'=> 'First Slot', '2'=> 'Second Slot', '3'=> 'Third Slot', '4'=> 'Fourth Slot', '5'=> 'Fifth Slot', '6'=>'Sixth Slot', '7'=> 'Seventh Slot', '8'=> 'Eight Slot', '9'=>'Nineth Slot', '10'=>'Tenth Slot'); @endphp
                                    @if(!empty($available_dates))
                                      <tr>
                                        <td scope="col"><strong>{{__('work_order_module.date')}}</strong> </td>
                                        <td scope="col" colspan="{{count(@$all_slot_data)/count(@$all_available_dates)}}" style="text-align:center;"><strong>{{__('work_order_module.slot')}}</strong></td>
                                      </tr>
                                     @foreach(@$all_available_dates as $valueDate)
                                      
                                      <tr>
                                        <td scope="row">    
                                        {{@$valueDate->contract_service_dates->date}} 
                                      </td>

                                            @foreach($all_slot_data as $slotValue)
                                              @if(@$valueDate->contract_service_dates->id == $slotValue->contract_service_date_id)
                                              <td scope="row"  @if(@$slotValue->booked_status=='Y') style="color: red" @endif>
                                                @if(array_key_exists($slotValue->daily_slot, $arraySlot)){{$arraySlot[$slotValue->daily_slot]}}@endif
                                              </td>
                                              @endif 
                                            @endforeach 
                                      </tr>        
                                      @endforeach     
                                     @endif     
                                  </table>   
                                </div>
                               @endif 
                            @else
                              {{Carbon\Carbon::createFromFormat('Y-m-d', @$work_order_list->start_date)->format('d/m/Y')}}  
                            @endif   
                          </td>
                        </tr>  
                  
                        <tr>
                          <td>{{__('work_order_module.work_order_task_column_name.completed')}}</td> 
                          <td ><div class="progress"><div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="{{$work_order_list->work_order_complete_percent}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$work_order_list->work_order_complete_percent}}%">{{$work_order_list->work_order_complete_percent}}% </div></div></td>
                        </tr>

                        @if($work_order_list->status=='2')
                          <tr>
                            <td>{{__('work_order_module.work_order_task_column_name.review_rating')}}</td>
                            <td>
                                {{$work_order_list->review}}
                                <section class='rating-widget'>
        
                                  <!-- Rating Stars Box -->
                                  <div class='rating-stars text-left'>
                                    <ul id='stars'>
                                      @php 
                                        $starTitleArray = array(1=>"Poor",2=>"Fair",3=>"Good", 4=>"Excellent", 5=>"WOW!!!");
                                      @endphp
                                      @for( $star =1; $star<=5; $star++)
                                      <li class='star @if($work_order_list->rating>0 and $work_order_list->rating>=$star){{ "selected" }} @endif' title='@if(array_key_exists($star, $starTitleArray)){{ $starTitleArray[$star] }} @endif' data-value='{{$star}}'>
                                        <i class='fa fa-star fa-fw'></i>
                                      </li>
                                      @endfor 
                                     
                                    </ul>
                                  </div>
                                
                                </section>
                            </td>
                          </tr>
                        @endif
                       
                        <tr>
                          <td>{{__('work_order_module.status')}}</td>
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
              </table>    
              @if($work_order_list->warning>0)
              <table class="table table-bordered" id="task_labour_list_management_table">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Labour Title</th>
                          <th>Task Slot</th>
                          <th>Labour Feedback</th>
                          <th>Feedback Date And Time</th>
                          <th>Action</th>
                      </tr>
                  </thead>
              </table>
              @endif

              <div>
                 <a href="{{route('admin.work-order-management.list')}}" class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
              </div>
            
            </div>
        </section>  
    </div>


              

              <!-- Labour Feedback END-->  

@endsection

@push('custom-scripts')


<script type="text/javascript" src="{{asset('js/admin/work_order_management/show.js')}}"></script>
@endpush


