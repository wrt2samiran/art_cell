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
                  <li class="breadcrumb-item "><a href="{{route('admin.work-order-management.labourTaskList', $task_data->work_order_id)}}">{{__('work_order_module.task_list')}}</a></li>
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
            </div>
            <div class="container-fluid">
            <div class="row">
              <div class="col-sm-12">
              <table class="table table-bordered table-hover" id="country-details-table">
                <tbody>
                  <?php //dd($service_allocation_data);?>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.task_title')}}</td>
                    <td >{{$task_data->task_title}}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.contract_code')}}</td>
                    <td >{{$task_data->contract->code}}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.work_order_title')}}</td>
                    <td >{{$task_data->work_order->task_title}}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.property_name')}}</td>
                    <td >{{$task_data->property->property_name}}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.service')}}</td>
                    <td >{{$task_data->service->service_name}}</td>
                  </tr>
                  <tr>
                    <td >{{__('work_order_module.work_order_task_column_name.service_type')}}</td>
                    <td >{{$task_data->contract_services->service_type}}</td>
                  </tr>
                  <tr>
                    <td>{{__('work_order_module.work_order_task_column_name.task_details')}}</td>
                    <td >{!! $task_data->task_desc!!}</td>
                  </tr>
                  <tr>
                    <td>{{__('work_order_module.work_order_task_column_name.create_at')}}</td>
                    <td >{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', @$task_data->created_at)->format('d/m/Y')}}</td>
                  </tr>
                  <tr>
                    <td>{{__('work_order_module.work_order_task_column_name.completed')}}</td> 
                    <td ><div class="progress"><div class="progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="{{$task_data->task_complete_percent}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$task_data->task_complete_percent}}%">{{$task_data->task_complete_percent}}% </div></div></td>
                  </tr>
                  <tr>
                    <td>{{__('work_order_module.status')}}</td>
                    <td>
                      @php
                        if($task_data->status=='0'){
                            $button = 'warning';
                            $status = 'Pending';
                          }
                          
                        else if($task_data->status=='1'){
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
                  
           
                <table class="table table-bordered" id="task_labour_list_management_table">
                    <thead>

                        <tr>
                          <th>Id</th>
                          <th>{{__('work_order_module.work_order_task_column_name.labour_title')}}</th>
                          <th>{{__('work_order_module.work_order_task_column_name.task_finish_date_time_assigned')}}</th>
                          <th>{{__('work_order_module.work_order_task_column_name.task_slot')}}</th>
                          <th>{{__('work_order_module.work_order_task_column_name.task_mode')}}</th>
                          <th>{{__('work_order_module.work_order_task_column_name.labour_feedback')}}</th>
                          <th>{{__('work_order_module.work_order_task_column_name.feedback_date_time')}}</th>
                          <th>{{__('work_order_module.status')}}</th>
                          @if($task_data->contract_services->service_type=='Maintenance')
                          <th>{{__('work_order_module.action')}} @if($task_data->task_complete_percent!=100 and $task_action>0)<input type="checkbox" name="all_labour_task_maintain" id="all_labour_task_maintain">
                                    <i class="right fas fa-trash-alt" id='delete_selected_maintain'></i> 
                           @endif 
                          </th>
                          @else
                            <th>{{__('work_order_module.action')}} @if($task_data->task_complete_percent!=100 and $task_action>0)<input type="checkbox" name="all_labour_task" id="all_labour_task">
                                @if($task_data->task_complete_percent!=100 and $task_action>0)
                                      <i class="right fas fa-trash-alt" id='delete_selected'></i> 
                                @endif 
                            @endif 
                          </th>
                          @endif
                        </tr>
                    </thead>
                </table>

                  
            
            
                
                <!-- Reschedule Labour Task requested by the Labour -->

                <div class="modal fade" id="taskRescheduleModal" role="dialog">
                  <div class="modal-dialog">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        
                        <h4 class="modal-title">Add Reschedule</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="card-body">
                            <div class="row justify-content-center">
                              <div class="col-md-10 col-sm-12">
                                
                                <form  id="service_provider_reschedule" action="{{route('admin.work-order-management.labourTaskReschedule')}}" method="post" enctype="multipart/form-data">
                                  @csrf
                                        <div>  
                                          <input type="hidden" name="task_details_id" id="task_details_id" />
                                   
                                          <div class="form-group">

                                            <div class="form-group required">
                                              <label for="task_date">Task Date <span class="error">*</span></label>
                                              <input type="text" class="form-control" value="{{old('task_date')?old('task_date'):''}}" readonly="readonly" name="task_date" id="task_date" autocomplete="off"  placeholder="Task Date" onchange="checkOnDemandFree()">
                                              @if($errors->has('task_date'))
                                              <span class="text-danger">{{$errors->first('task_date')}}</span>
                                              @endif
                                            </div>
                                            <div class="form-group required">
                                              <label for="finish_time">Task Finish Time<span class="error">*</span></label>
                                                  
                                                  <input type="text" class="form-control clockpicker" readonly="" value="" name="assigned_finish_time" id="assigned_finish_time" onchange="checkOnDemandFree()">
                                                
                                            </div> 
                                            <div class="form-group required">
                                              <label for="task_description">Task Details</label>
                                              <textarea class="form-control" name="task_description" id="task_description">{{old('task_description')}}</textarea>
                                               @if($errors->has('task_description'))
                                                <span class="text-danger">{{$errors->first('task_description')}}</span>
                                               @endif
                                            </div>
                          
                                            <!-- /.input group -->
                                          </div>
                                          
                                          <div>
                                             <button type="submit" class="btn btn-success" disabled="">Submit</button> 
                                             <div class="live_list" id="live_list" content="width=device-width, initial-scale=1"></div>

                                          </div>
                                        </div>
                                </form>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Reschedule Labour Task requested by the Labour end -->



                <!-- Edit Labour Task by the Service provider -->

                <div class="modal fade" id="updateLabourTaskModal" role="dialog">
                  <div class="modal-dialog">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        
                        <h4 class="modal-title">Edit Labour Task</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="card-body">
                            <div class="row justify-content-center">
                              <div class="col-md-10 col-sm-12">
                                
                                <form  id="edit_labour_task" action="{{route('admin.work-order-management.labourTaskUpdate')}}" method="post" enctype="multipart/form-data">
                                  @csrf
                                        <div>  
                                          <input type="hidden" name="update_task_details_id" id="update_task_details_id" />
                                   
                                          <div class="form-group">

                                            <div class="form-group required">
                                               <label for="labour_user">Labour <span class="error">*</span></label>
                                                <select class="form-control select-labour" id="labour_user" name="labour_user" style="width: 100%;" onchange="updateCheckAvailablity()">
                                                  @forelse($labour_list as $labour_data)
                                                       <option value="{{@$labour_data->id}}" >{{@$labour_data->name}}( Woring Hour : {{@$labour_data->start_time}} to {{@$labour_data->end_time}})</option>
                                                  @empty
                                                  <option value="">No Labour Found</option>
                                                  @endforelse 
                                                </select>
                                            </div>

                                            <div class="form-group required">
                                              <label for="task_date">Task Date <span class="error">*</span></label>
                                              <input type="text" class="form-control" value="{{old('task_date')?old('task_date'):''}}" name="modified_task_date" id="modified_task_date" autocomplete="off"  placeholder="Task Date" onchange="updateCheckAvailablity()">
                                              @if($errors->has('modified_task_date'))
                                              <span class="text-danger">{{$errors->first('modified_task_date')}}</span>
                                              @endif
                                            </div>
                                            <div class="form-group required">
                                              <label for="finish_time">Task Finish Time<span class="error">*</span></label>
                                                  
                                                  <input type="text" class="form-control clockpicker" readonly="" value="" name="modified_assigned_finish_time" id="modified_assigned_finish_time" onchange="updateCheckAvailablity()">
                                                
                                            </div> 
                                            <div class="form-group required">
                                              <label for="task_description">Task Details</label>
                                              <textarea class="form-control" name="task_description" id="task_description">{{old('task_description')}}</textarea>
                                               @if($errors->has('task_description'))
                                                <span class="text-danger">{{$errors->first('task_description')}}</span>
                                               @endif
                                            </div>
                          
                                            <!-- /.input group -->
                                          </div>
                                          
                                          <div>
                                             <button type="submit" class="btn btn-success btn-update" disabled="">Submit</button> 
                                             <div class="live_list" id="live_list" content="width=device-width, initial-scale=1"></div>

                                          </div>
                                        </div>
                                </form>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Reschedule Labour Task by the Service provider end -->


                <!-- Edit Labour Task by the Service provider -->

                <div class="modal fade" id="updateLabourMaintanenceTaskModal" role="dialog">
                  <div class="modal-dialog">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        
                        <h4 class="modal-title">Edit Labour Task</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="card-body">
                            <div class="row justify-content-center">
                              <div class="col-md-10 col-sm-12">
                                
                                <form  id="edit_labour_task" action="{{route('admin.work-order-management.labourTaskUpdateMaintanence')}}" method="post" enctype="multipart/form-data">
                                  @csrf
                                        <div>  
                                          <input type="hidden" name="update_task_details_id_maintain" id="update_task_details_id_maintain" />
                                   
                                          <div class="form-group">

                                            <div class="form-group required">
                                               <label for="labour_user_maintain">Labour <span class="error">*</span></label>
                                                <select class="form-control select-labour" id="labour_user_maintain" name="labour_user_maintain" style="width: 100%;" onchange="updateCheckMaintanenceAvailablity()">
                                                  @forelse($labour_list as $labour_data)
                                                       <option value="{{@$labour_data->id}}" >{{@$labour_data->name}}( Woring Hour : {{@$labour_data->start_time}} to {{@$labour_data->end_time}})</option>
                                                  @empty
                                                  <option value="">No Labour Found</option>
                                                  @endforelse 
                                                </select>
                                            </div>

                                           
                                            <div class="form-group required">
                                              <label for="finish_time">Task Finish Time<span class="error">*</span></label>
                                                  
                                                  <input type="text" class="form-control clockpicker" readonly="" value="" name="modified_assigned_finish_time_maintain" id="modified_assigned_finish_time_maintain" onchange="updateCheckMaintanenceAvailablity()">
                                                
                                            </div> 
                                            <div class="form-group required">
                                              <label for="task_description">Task Details</label>
                                              <textarea class="form-control" name="task_description_maintain" id="task_description_maintain">{{old('task_description_maintain')}}</textarea>
                                               @if($errors->has('task_description_maintain'))
                                                <span class="text-danger">{{$errors->first('task_description_maintain')}}</span>
                                               @endif
                                            </div>
                          
                                            <!-- /.input group -->
                                          </div>
                                          
                                          <div>
                                             <button type="submit" class="btn btn-success btn-update-maintain" disabled="">Submit</button> 
                                             <div class="live_list" id="live_list" content="width=device-width, initial-scale=1"></div>

                                          </div>
                                        </div>
                                </form>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Edit Labour Task by the Service provider end -->


                <div class="modal fade" id="reviewRatingModal" role="dialog">
                  <div class="modal-dialog">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        
                        <h4 class="modal-title">Add Review and Rating</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="card-body">
                            <div class="row justify-content-center">
                              <div class="col-md-10 col-sm-12">
                                
                                <form  id="service_provider_reschedule" action="{{route('admin.work-order-management.labourTaskReviewRating')}}" method="post" enctype="multipart/form-data">
                                  @csrf
                                    <input type="hidden" name="taskdetails_id" id="taskdetails_id">
                                    <div>  
                                      <div class="form-group">
                                        <div class="form-group required">
                                          <label for="task_description">Review</label>
                                          <textarea class="form-control" name="labour_task_review" id="labour_task_review">{{old('labour_task_review')}}</textarea>
                                           @if($errors->has('labour_task_review'))
                                              <span class="text-danger">{{$errors->first('labour_task_review')}}</span>
                                           @endif
                                        </div>
                                        <div class="form-group required">
                                          <label for="task_description">Rating</label>
                                            <input type="hidden" name="rating" id="rating" value="">
                                            <section class='rating-widget'>
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
                                              
                                                <div class='success-box' style="display: none;">
                                                  <div class='clearfix'></div>
                                                    <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                                                  <div class='text-message'></div>
                                                  <div class='clearfix'></div>
                                                </div>
                                            </section>
                                        </div>  
                                      </div>
                                      <div>
                                         <button type="submit" class="btn btn-success submit-review-rating" disabled="">Submit</button> 
                                         <div class="live_list" id="live_list" content="width=device-width, initial-scale=1"></div>

                                      </div>
                                    </div>
                                </form>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                 <div>
                    <div>
                        <a href="{{route('admin.work-order-management.labourTaskList', $task_data->work_order_id)}}"  class="btn btn-primary"><i class="fas fa-backward"></i>&nbsp;Back</a>
                    </div>                    
                 </div>
                </div>
               </div>
             </div>

        </section>
    </div>    
              <!-- Labour Feedback END-->  

@endsection

@push('custom-scripts')
<script type="text/javascript">
$( document ).ready(function() {
      $(".clockpicker").clockpicker({
           timeFormat: 'HH:mm',
           interval: 30,
           scrollbar: true,
       });

      $('#modified_task_date').datepicker({
         minDate: 1,
         dateFormat: 'dd/mm/yy',
      });
});


$("#all_labour_task").click(function(){
   $("input[type=checkbox]").prop('checked', $(this).prop('checked'));

});

$("#all_labour_task_maintain").click(function(){
   $("input[type=checkbox]").prop('checked', $(this).prop('checked'));

});

  

  $("#delete_selected").on('click',function(e){
      e.preventDefault();
      var checkboxValues = [];
      $('input[name=labour_task_list]:checked').map(function() {
            checkboxValues.push($(this).val());
      });
      if(checkboxValues.length>0){

        console.log(checkboxValues);

        swal({
        title: "Are you sure?",
        //text: "Once Assigned, you will not be able to modify this task!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        }).then((isConfirm) => {
          if(isConfirm){
            $.LoadingOverlay("show");
            $.ajax({
        
                url: "{{route('admin.work-order-management.deleteSubTask')}}",
                type:'post',
                dataType: "json",
                data: {checkboxValues : checkboxValues, _token:"{{ csrf_token() }}"},
                cache: false,
                }).done(function(response) {
                   
                   console.log(response.status);
                   if(response.status==true){
                      if(response.redirect==true)
                      {
                        window.location.href = "{{url('/admin/work-order-management')}}";
                      }
                      else
                      {
                        location.reload();
                      }
                      
                    
                    }

                    
                });
            }
        });


      }
      else{

         swal({
        title: "Pleace select labour task first!",
        //text: "Once Assigned, you will not be able to modify this task!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        });
      }
      

  }); 


  
  $("#delete_selected_maintain").on('click',function(e){
      e.preventDefault();
      var checkboxValues = [];
      $('input[name=labour_task_list_maintain]:checked').map(function() {
            checkboxValues.push($(this).val());
      });
      if(checkboxValues.length>0){

        console.log(checkboxValues);

        swal({
        title: "Are you sure?",
        //text: "Once Assigned, you will not be able to modify this task!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        }).then((isConfirm) => {
          if(isConfirm){
            $.LoadingOverlay("show");
            $.ajax({
        
                url: "{{route('admin.work-order-management.deleteMaintanenceSubTask')}}",
                type:'post',
                dataType: "json",
                data: {checkboxValues : checkboxValues, _token:"{{ csrf_token() }}"},
                cache: false,
                }).done(function(response) {
                   
                   console.log(response.status);
                   if(response.status==true){
                      if(response.redirect==true)
                      {
                        window.location.href = "{{url('/admin/work-order-management')}}";
                      }
                      else
                      {
                        location.reload();
                      }
                      
                    
                    }

                    
                });
            }
        });


      }
      else{

         swal({
        title: "Pleace select labour task first!",
        //text: "Once Assigned, you will not be able to modify this task!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        });
      }
      

  }); 


function reviewRating(taskdetails_id)
{
  
  $("#reviewRatingModal").modal();
   
    var check_rating = document.getElementById('rating').value;
    if(check_rating<1){
    /* 1. Visualizing things on Hover - See next part for action on click */
    $('#stars li').on('mouseover', function(){
      var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
     
      // Now highlight all the stars that's not after the current hovered star
      $(this).parent().children('li.star').each(function(e){
        if (e < onStar) {
          $(this).addClass('hover');
        }
        else {
          $(this).removeClass('hover');
        }
      });
      
    }).on('mouseout', function(){
      $(this).parent().children('li.star').each(function(e){
        $(this).removeClass('hover');
      });
    });
  
  
    /* 2. Action to perform on click */
    $('#stars li').on('click', function(){
      var onStar = parseInt($(this).data('value'), 10); // The star currently selected
      var stars = $(this).parent().children('li.star');
      
      for (i = 0; i < stars.length; i++) {
        $(stars[i]).removeClass('selected');
      }
      
      for (i = 0; i < onStar; i++) {
        $(stars[i]).addClass('selected');
      }
      
      // JUST RESPONSE 
      var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
      if(ratingValue>0)
      {
        $( ".submit-review-rating" ).prop( "disabled", false );
        //$("#review_rating").val(ratingValue);
        $('#taskdetails_id').val(taskdetails_id);
        $('#rating').val(ratingValue);

      }

      else
      {
        $( ".submit-review-rating" ).prop( "disabled", true );
      }
    }); 
  } 
};



function responseMessage(msg) {
  
  $('#stars li').unbind('mouseover');
  $("#stars li").off('click'); 
  $('.success-box').fadeIn(200).show();  
  $('.success-box div.text-message').html("<span>" + msg + "</span>");
  setTimeout(function() { 
       $('.success-box').fadeOut(); 
   }, 5000);
}

function checkOnDemandFree()
    {

      if (($('#task_date').val().length > 0) && ($('#assigned_finish_time').val().length  > 0))
      {
        $(".live_list").empty();
        $('.btn-success').prop('disabled', false);
        //var userDataString =  document.getElementById('user_id').innerHTML;
        //var userDataString = $('#user_id option:selected').val();
        var userDataString = $('#user_id').val();
        var dateString = $('#date_range').val();
        var work_order_id = $('#work_order_id');
        console.log(dateString);
        var userData = JSON.stringify(userDataString);
        var selectedDate = JSON.stringify(dateString);
        var work_order_id = JSON.stringify(work_order_id);
        $.ajax({
        
        url: "{{route('admin.work-order-management.checkAvailablity')}}",
        type:'post',
        dataType: "json",
        data: {data : userData, selectedDate : selectedDate, work_order_id : work_order_id, _token:"{{ csrf_token() }}"},
        cache: false,
        }).done(function(response) {
           
           console.log(response.status);
           if(response.status){
            //console.log(response.userLeaveList);
            // var stringifiedWeeklyLeave = JSON.stringify(response.weeklyLeave);
             var userLeaveList = JSON.stringify(response.userLeaveList);
             var userLeaveData = JSON.parse(userLeaveList);
             var all_leave_list = '';
             console.log(userLeaveList.length);
            if (userLeaveList.length>0) {
               // alert('Positive ::'+userLeaveList.length);
                  document.getElementById("live_list").setAttribute("style","overflow:auto;height:150px;width:350px;color: red; font-weight: bold;");
                  $.each(userLeaveData,function(index, leave_all_dates){
                    all_leave_list += index+' have leave on below days : '+'<br>';
                    $.each(leave_all_dates,function(indexval, leave_date){
                    console.log(index);
                    console.log(leave_date);
                    all_leave_list += leave_date+'<br>';
                    });
                  });  
                 // $("#live_list").text('dta set');
                  $("div.live_list").html(all_leave_list );
              }
            
            }

            
        });
      }
      else
      {
        $('.btn-success').prop('disabled', true);
      }
    }


function updateCheckAvailablity()
    {
        if (($('#labour_user').val().length > 0) && ($('#modified_task_date').val().length  > 0) && ($('#modified_assigned_finish_time').val().length  > 0))
        {
          $('.btn-update').prop('disabled', false);
        }

        else
        {
          $('.btn-update').prop('disabled', true);
        }
    }

function updateCheckMaintanenceAvailablity()
    {
        if (($('#labour_user_maintain').val().length > 0)  && ($('#modified_assigned_finish_time_maintain').val().length  > 0))
        {
          $('.btn-update-maintain').prop('disabled', false);
        }

        else
        {
          $('.btn-update-maintain').prop('disabled', true);
        }
    }    


</script>

<script type="text/javascript" src="{{asset('js/admin/work_order_management/task-labour-list.js')}}"></script>
@endpush


