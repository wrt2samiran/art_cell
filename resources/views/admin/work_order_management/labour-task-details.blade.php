@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('work_order_module.work_order_task_column_name.task_details')}}</h1>
                <?php //dd($task_data);?>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item "><a href="{{route('admin.work-order-management.labourTaskList', $task_data->task->work_order_id)}}">{{__('work_order_module.task_list')}}</a></li>
                  <li class="breadcrumb-item active">{{__('work_order_module.task_labour_list')}}</li>
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

              <table class="table table-bordered table-hover" id="task-details-table">
                <tbody>
                  <input type="hidden" name="task_details_id" id="task_details_id" value="{{$task_data->id}}">
                  <input type="hidden" name="rating" id="rating" value="{{$task_data->rating}}">
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.task_title')}}</td>
                    <td >{{$task_data->task->task_title}}</td>
                  </tr>
                 
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.work_order_title')}}</td>
                    <td >{{$task_data->task->work_order->task_title}}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.property_name')}}</td>
                    <td >{{$task_data->task->property->property_name}}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.service')}}</td>
                    <td >{{$task_data->service->service_name}}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.task_finish_date_time_assigned')}}</td>
                    <td >{{ Carbon\Carbon::parse($task_data->task_date)->format('d/m/Y H:i a') }}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.slot')}}</td>
                    <td >

                      @if(@$task_data->work_order_slot->daily_slot == 1)
                          {{__('work_order_module.first_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 2)
                          {{__('work_order_module.second_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 3)
                          {{__('work_order_module.third_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 4)
                          {{__('work_order_module.fourth_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 5)
                          {{__('work_order_module.fifth_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 6)
                          {{__('work_order_module.sixth_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 7)
                          {{__('work_order_module.seventh_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 8)
                          {{__('work_order_module.eighth_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 9)
                          {{__('work_order_module.nineth_slot')}}
                      @elseif(@$task_data->work_order_slot->daily_slot == 10)
                          {{__('work_order_module.tenth_slot')}}
                      @else
                          {{__('work_order_module.no_slot')}}                 
                      @endif

                    </td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.task_mode')}}</td>
                    <td >@if($task_data->reschedule_task_details_id>0)Rescheduled @else Normal @endif
                  </tr>
                  <tr>
                    <td >{{__('general_sentence.breadcrumbs.country')}}</td>
                    <td >{{$task_data->task->property->country->name}}</td>
                  </tr>
                  <tr>
                    <td >{{__('general_sentence.breadcrumbs.state')}}</td>
                    <td >{{$task_data->task->property->state->name}}</td>
                  </tr>
                  <tr>
                    <td >{{__('general_sentence.breadcrumbs.city')}}</td>
                    <td >{{$task_data->task->property->city->name}}</td>
                  </tr>
                  
                  <tr>
                    <td>{{__('work_order_module.work_order_task_column_name.task_details')}}</td>
                    <td >{!! $task_data->task->task_desc!!}</td>
                  </tr>

                  <tr>
                    <td>{{__('work_order_module.work_order_task_column_name.labour_feedback')}}</td>
                    <td >{!! $task_data->user_feedback!!}</td>
                  </tr>

                  <tr>
                    <td>{{__('work_order_module.work_order_task_column_name.feedback_file')}}</td>
                    <td >
                        <table>
                            <tr>
                              @foreach($task_data->task_details_feedback_files as $feedbackFile)
                                <img width="10%" src="{{asset('uploads/feedback_attachments/'.$feedbackFile->feedback_file)}}">
                              @endforeach  
                            </tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.feedback_date_time')}}</td>
                    <td >{{ Carbon\Carbon::parse($task_data->task_finish_date_time)->format('d/m/Y H:i a') }}</td>
                  </tr>

                  <tr>
                    <td>{{__('work_order_module.work_order_task_column_name.feedback_on_time')}}</td>
                    <td >@if($task_data->late_feedback=="N") No @else Yes @endif</td>
                  </tr>
                  
                 
                  <tr>
                    <td>{{__('work_order_module.status')}}</td>
                    <td>

                      @php
                          $color_code = $task_data->work_order_status->color_code;
                          $status = $task_data->work_order_status->status_name;
                      @endphp  

                      <button role="button" class="btn btn" style="color: {{$color_code}}">{{$status}}</button>

                    </td>
                  </tr>
                  @if($task_data->status=='2' || $task_data->status=='4')
                    <tr>
                      <td>{{__('work_order_module.work_order_task_column_name.review_rating')}}</td>
                      <td>
                          {{$task_data->review}}
                          <section class='rating-widget'>
  
                            <!-- Rating Stars Box -->
                            <div class='rating-stars text-left'>
                              <ul id='stars'>
                                @php 
                                  $starTitleArray = array(1=>"Poor",2=>"Fair",3=>"Good", 4=>"Excellent", 5=>"WOW!!!");
                                @endphp
                                @for( $star =1; $star<=5; $star++)
                                <li class='star @if($task_data->rating>0 and $task_data->rating>=$star){{ "selected" }} @endif' title='@if(array_key_exists($star, $starTitleArray)){{ $starTitleArray[$star] }} @endif' data-value='{{$star}}'>
                                  <i class='fa fa-star fa-fw'></i>
                                </li>
                                @endfor 
                               
                              </ul>
                            </div>
                          
                          </section>
                      </td>
                    </tr>
                  @endif
                 </tbody>
                    <tfoot>
                      <tr>
                        @if(Auth::guard('admin')->user()->role->slug=='labour')
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.work-order-management.list')}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a></td>
                        @else
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.work-order-management.taskLabourList', $task_data->task->id)}}"><i class="fas fa-backward"></i>&nbsp;{{__('general_sentence.button_and_links.back')}}</a></td>
                        @endif  
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

@push('custom-scripts')


<script type="text/javascript">



</script>


@endpush


