@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Task List</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Task List</li>
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
                    <td >Contract Code</td>
                    <td >{{$work_order_list->contract->code}}</td>
                  </tr>
                  <tr>
                    <td >Work Order Title</td>
                    <td >{{$work_order_list->task_title}}</td>
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
                    <td>Country</td>
                    <td >{{$work_order_list->property->country->name}}</td>
                  </tr>
                  <tr>
                    <td>State</td>
                    <td >{{$work_order_list->property->state->name}}</td>
                  </tr>
                  <tr>
                    <td>City</td>
                    <td >{{$work_order_list->property->city->name}}</td>
                  </tr>
                  <tr>
                    <td>Work Order Details</td>
                    <td >{!! $work_order_list->task_desc!!}</td>
                  </tr>
                  <tr>
                    <td >Service Provider</td>
                    <td >{{$work_order_list->userDetails->name}}</td>
                  </tr>
                  <tr>
                    <td>Start Date</td> 
                    <td >{{Carbon\Carbon::createFromFormat('Y-m-d', $work_order_list->start_date)->format('d-m-Y')}}</td>
                  </tr>

                  
                  
                  <!-- <tr>
                    <td>Status</td>
                    <td>
                      <button role="button" class="btn btn-{{($work_order_list->status=='A')?'success':'danger'}}">{{($work_order_list->status=='A')?'Active':'Inactive'}}</button>
                    </td>
                  </tr> -->
                 
                </tbody>
                
            </table>

            @if(@$work_order_list->task_assigned=='N') 
            <div class="card-header">
              <div class="d-flex justify-content-between" >
                <div>
                  <!-- <a class="btn btn-success" style="text-align: right;" onclick="assignLabourTask()" href="{{route('admin.work-order-management.labourTaskCreate', $work_order_list->id)}}"> -->
                    <?php //dd($work_order_list);?>
                   @if(@$work_order_list->contract_services->service_type=='Maintenance' and @$work_order_list->contract_service_recurrence->interval_type == 'daily')  
                    <a class="btn btn-success" style="text-align: right;" onclick="assignLabourMaintanenceTask()" href="javascript:void(0)">
                   @elseif(@$work_order_list->contract_services->service_type=='Maintenance'  and @$work_order_list->contract_service_recurrence->interval_type != 'daily') 
                   <a class="btn btn-success" style="text-align: right;" onclick="assignLabourOtherMaintanenceTask()" href="javascript:void(0)">
                   @else
                    <a class="btn btn-success" style="text-align: right;" onclick="assignLabourTask()" href="javascript:void(0)">
                   @endif
                      
                   Assign Labour Task
                  </a>
                </div>
              </div>
            </div>
            @endif
            
            <table class="table table-bordered" id="daily_task_management_table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Task Title</th>
                        <th>Created At</th>
                        <th>Completed (%)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <!-- <input type="hidden" id="labour_assigned_task_list" value="{{route('admin.work-order-management.list')}}"> -->

            <!-- On Demand or Free Task -->
            <div class="modal fade" id="addTaskModal" role="dialog">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Add Task For On Deamand or Free</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <div class="card-body">
                      <div class="row justify-content-center">
                        <div class="col-md-10 col-sm-12">
                          <form  method="post" id="admin_labour_task_add_form" action="{{route('admin.work-order-management.taskAssign')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                 
                              <div class="form-group required">
                                  <label for="service_id">Work Order Title <span class="error">*</span></label>
                                  <select class="form-control parent_role_select2" style="width: 100%;" name="work_order_id" id="work_order_id">
                                      <option value="{{@$work_order_list->id}}" {{(old('task_id')== @$work_order_list->id)? 'selected':''}}>{{@$work_order_list->task_title}} ({{@$work_order_list->contract->code}})</option>
                                    </select>
                                  @if($errors->has('work_order_id'))
                                  <span class="text-danger">{{$errors->first('work_order_id')}}</span>
                                  @endif
                              </div>
                                        
                              <div class="form-group required">
                                <label for="property_id">Task Title <span class="error">*</span></label>
                                <input class="form-control parent_role_select2" style="width: 100%;" type="text" name="task_title" id="task_title" value="{{(old('task_title'))? old('task_id'):''}}" onkeyup="checkOnDemandFree()">
                                
                                @if($errors->has('task_title'))
                                <span class="text-danger">{{$errors->first('task_title')}}</span>
                                @endif
                              </div>  
                              <div class="form-group required">
                                <label for="property_id">Service <span class="error">*</span></label>
                                <select class="form-control parent_role_select2" style="width: 100%;" name="service_id" id="service_id" >
                                         <option value="{{@$work_order_list->service_id}}" {{(old('service_id')== @$work_order_list->service_id)? 'selected':''}}>{{@$work_order_list->service->service_name}}</option>
                                  </select>
                                @if($errors->has('property_id'))
                                <span class="text-danger">{{$errors->first('property_id')}}</span>
                                @endif
                              </div>

                              <div class="form-group">
                                <label for="service_id">Task Description</label>
                                <textarea class="form-control float-right" name="task_description" id="task_description">{{old('task_description')}}</textarea>
                              </div>
                                   

                              <div class="form-group required">
                                <label for="labour_list_id">Labour List <span class="error">*</span></label>
                                <select class="form-control" multiple="multiple" searchable="Search for..."  name="user_id[]" id="user_id" onchange="checkOnDemandFree()" >
                                   
                                   @forelse($labour_list as $labour_data)
                                         <option value="{{@$labour_data->id}}" >{{@$labour_data->name}}</option>
                                    @empty
                                    <option value="">No Labour Found</option>
                                    @endforelse     
                                                             
                                  </select>
                                @if($errors->has('user_id'))
                                <span class="text-danger">{{$errors->first('user_id')}}</span>
                                @endif
                              </div> 

                              <div class="form-group">
                                <label>Date range <span class="error">*</span></label>

                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="far fa-calendar-alt"></i>
                                    </span>
                                  </div>
                                  <input type="text" class="form-control float-right" id="date_range" name="date_range">
                                </div>
                                <!-- /.input group -->
                              </div>

                             <div>
                              <button type="submit" class="btn btn-success" id="free_ondemand" disabled="">Submit</button> 
                          </div>
                          <div class="live_list" id="live_list" content="width=device-width, initial-scale=1"></div>
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
            <!-- On Demand or Free Task -->


            <!-- Maintanence Task -->
              <div class="modal fade" id="addMaintanenceTaskModal" role="dialog">
              <div class="modal-dialog">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Add Task For Maintanence</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <div class="card-body">
                            <div class="row justify-content-center">
                              <div class="col-md-10 col-sm-12">
                                <form  method="post" id="admin_maintanence_labour_task_add_form" action="{{route('admin.work-order-management.taskMaintanenceAssign')}}" method="post" enctype="multipart/form-data">
                                      @csrf
                                       
                                    <div class="form-group required">
                                        <label for="service_id">Work Order Title <span class="error">*</span></label>
                                        <select class="form-control parent_role_select2" style="width: 100%;" name="work_order_id" id="work_order_id">
                                            <option value="{{@$work_order_list->id}}" {{(old('task_id')== @$work_order_list->id)? 'selected':''}}>{{@$work_order_list->task_title}} ({{@$work_order_list->contract->code}})</option>
                                          </select>
                                        @if($errors->has('work_order_id'))
                                        <span class="text-danger">{{$errors->first('work_order_id')}}</span>
                                        @endif
                                    </div>
                                        
                                    <div class="form-group required">
                                      <label for="property_id">Task Title <span class="error">*</span></label>
                                      <input class="form-control parent_role_select2" style="width: 100%;" type="text" name="task_title_maintanence_daily" id="task_title_maintanence_daily" value="{{(old('task_title_maintanence_daily'))? old('task_title_maintanence_daily'):''}}" onkeyup="checkMaintanenceDaily()">
                                      
                                      @if($errors->has('task_title'))
                                      <span class="text-danger">{{$errors->first('task_title')}}</span>
                                      @endif
                                    </div>  
                                    <div class="form-group required">
                                      <label for="property_id">Service <span class="error">*</span></label>
                                      <select class="form-control parent_role_select2" style="width: 100%;" name="service_id" id="service_id" >
                                               <option value="{{@$work_order_list->service_id}}" {{(old('service_id')== @$work_order_list->service_id)? 'selected':''}}>{{@$work_order_list->service->service_name}}</option>
                                        </select>
                                      @if($errors->has('property_id'))
                                      <span class="text-danger">{{$errors->first('property_id')}}</span>
                                      @endif
                                    </div>

                                    <div class="form-group">
                                      <label for="service_id">Task Description</label>
                                      <textarea class="form-control float-right" name="task_description" id="task_description">{{old('task_description')}}</textarea>
                                    </div>
                                   

                                    <div class="form-group required">
                                      <label for="labour_list_id">Labour List <span class="error">*</span></label>
                                      <select class="form-control" multiple="multiple" searchable="Search for..."  name="maintanence_user_id[]" id="maintanence_user_id" onchange="checkMaintanenceDaily()">
                                         
                                         @forelse($labour_list as $labour_data)
                                               <option value="{{@$labour_data->id}}" >{{@$labour_data->name}}</option>
                                          @empty
                                          <option value="">No Labour Found</option>
                                          @endforelse     
                                                                   
                                        </select>
                                      @if($errors->has('maintanence_user_id'))
                                      <span class="text-danger">{{$errors->first('maintanence_user_id')}}</span>
                                      @endif
                                    </div>
                                   
                                   
                                     @if(@$work_order_list->contract_services->service_type=='Maintenance'  and @$work_order_list->contract_service_recurrence->interval_type == 'daily') 

                                     <?php //dd($slot_data);?>
                                     @php $arraySlot = array('1'=> 'First Slot', '2'=> 'Second Slot', '3'=> 'Third Slot', '4'=> 'Fourth Slot', '5'=> 'Fifth Slot', '6'=>'Sixth Slot', '7'=> 'Seventh Slot', '8'=> 'Eight Slot', '9'=>'Nineth Slot', '10'=>'Tenth Slot'); @endphp
                                      <div class="form-group required">
                                      <label for="property_id">Work <span class="error">*</span></label>
                                      <select class="form-control work_date" multiple="multiple" style="width: 100%;" name="work_date[]" id="work_date"  onchange="checkMaintanenceDaily()">
                                        @if(!empty($available_dates))
                                           @forelse(@$available_dates as $valueDate)
                                            <ul style="importent">
                                              <li>
                                               <option value="{{@$valueDate->contract_service_dates->date}}" >{{@$valueDate->contract_service_dates->date}}  </option>
                                                 <ul>
                                                  
                                                  @forelse($slot_data as $slotValue)
                                                    @if(@$valueDate->contract_service_dates->id == $slotValue->contract_service_date_id)
                                                      <li>
                                                       <!-- <option value="{{@$slotValue->id}}" >@if(array_key_exists($slotValue->daily_slot, $arraySlot)){{$arraySlot[$slotValue->daily_slot]}}@endif</option> -->
                                                       <option value="{{@$slotValue->id}}" >@if(array_key_exists($slotValue->daily_slot, $arraySlot)){{$arraySlot[$slotValue->daily_slot]}}@endif</option>
                                                      </li> 
                                                    @endif  
                                                  @empty
                                                  <option value="">No Labour Found</option>
                                                  @endforelse 
                                                   
                                                 </ul>   
                                              </li> 
                                            </ul> 
                                             @empty
                                             <option value="">No Date Found</option>
                                           @endforelse
                                           @endif     
                                          </select>
                                        @if($errors->has('work_date'))
                                        <span class="text-danger">{{$errors->first('work_date')}}</span>
                                        @endif
                                    </div>

                                    @endif
                                                                           
                                  <div>
                                   <button type="submit" class="btn btn-success" id="maintanence_daily" disabled="">Submit</button> 
                                </div>
                                <div class="live_list" id="live_list" content="width=device-width, initial-scale=1"></div>
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
            <!-- Maintanence Task End-->

            <!-- Other Maintanence Task -->
              <div class="modal fade" id="addOtherMaintanenceTaskModal" role="dialog">
              <div class="modal-dialog">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Add Task For Maintanence</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <div class="card-body">

                            <div class="row justify-content-center">
                              <div class="col-md-10 col-sm-12">
                                <form  method="post" id="admin_other_maintanence_labour_task_add_form" action="{{route('admin.work-order-management.taskOtherMaintanenceAssign')}}" method="post" enctype="multipart/form-data">
                                      @csrf
                                       
                                    <div class="form-group required">
                                        <label for="service_id">Work Order Title <span class="error">*</span></label>
                                        <select class="form-control parent_role_select2" style="width: 100%;" name="work_order_id" id="work_order_id">
                                            <option value="{{@$work_order_list->id}}" {{(old('task_id')== @$work_order_list->id)? 'selected':''}}>{{@$work_order_list->task_title}} ({{@$work_order_list->contract->code}})</option>
                                          </select>
                                        @if($errors->has('work_order_id'))
                                        <span class="text-danger">{{$errors->first('work_order_id')}}</span>
                                        @endif
                                    </div>
                                        
                                    <div class="form-group required">
                                      <label for="property_id">Task Title <span class="error">*</span></label>
                                      <input class="form-control parent_role_select2" style="width: 100%;" type="text" name="task_title_maintanence_other" id="task_title_maintanence_other" value="{{(old('task_title_maintanence_other'))? old('task_title_maintanence_other'):''}}" onkeyup="checkMaintanenceOther()">
                                      
                                      @if($errors->has('task_title'))
                                      <span class="text-danger">{{$errors->first('task_title')}}</span>
                                      @endif
                                    </div>  
                                    <div class="form-group required">
                                      <label for="property_id">Service <span class="error">*</span></label>
                                      <select class="form-control parent_role_select2" style="width: 100%;" name="service_id" id="service_id" >
                                               <option value="{{@$work_order_list->service_id}}" {{(old('service_id')== @$work_order_list->service_id)? 'selected':''}}>{{@$work_order_list->service->service_name}}</option>
                                        </select>
                                      @if($errors->has('property_id'))
                                      <span class="text-danger">{{$errors->first('property_id')}}</span>
                                      @endif
                                    </div>

                                    <div class="form-group">
                                      <label for="service_id">Task Description</label>
                                      <textarea class="form-control float-right" name="task_description" id="task_description">{{old('task_description')}}</textarea>
                                    </div>
                                   

                                    <div class="form-group required">
                                      <label for="labour_list_id">Labour List <span class="error">*</span></label>
                                      <select class="form-control" multiple="multiple" searchable="Search for..."  name="maintanence_other_user_id[]" id="maintanence_other_user_id" onchange="checkMaintanenceOther()">
                                         
                                         @forelse($labour_list as $labour_data)
                                               <option value="{{@$labour_data->id}}" >{{@$labour_data->name}}</option>
                                          @empty
                                          <option value="">No Labour Found</option>
                                          @endforelse     
                                                                   
                                        </select>
                                      @if($errors->has('maintanence_other_user_id'))
                                      <span class="text-danger">{{$errors->first('maintanence_other_user_id')}}</span>
                                      @endif
                                    </div>
                                   
                                   
                                     @if(@$work_order_list->contract_services->service_type=='Maintenance'  and @$work_order_list->contract_service_recurrence->interval_type != 'daily') 

                                     <?php //dd($work_order_list->contract_service_dates);?>
                                      <div class="form-group required">
                                      <label for="property_id">Work <span class="error">*</span></label>
                                      <select class="form-control work_date" multiple="multiple" style="width: 100%;" name="work_date_other[]" id="work_date_other"  onchange="checkMaintanenceOther()">
                                           @forelse(@$work_order_list->contract_service_dates as $valueDate)
                                             <option value="{{@$valueDate->date}}" >{{@$valueDate->date}} </option>
                                             @empty
                                             <option value="">No Date Found</option>
                                           @endforelse     
                                          </select>
                                        @if($errors->has('work_date'))
                                        <span class="text-danger">{{$errors->first('work_date')}}</span>
                                        @endif
                                    </div>

                                    @endif
                                                                           
                                  <div>
                                   <button type="submit" class="btn btn-success" id="maintanence_other" disabled="">Submit</button> 
                                </div>
                                <div class="live_list" id="live_list" content="width=device-width, initial-scale=1"></div>
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
            <!-- Other Maintanence Task End-->

              
              <!-- Labour Feedback -->
                <div class="modal fade" id="addFeedbackModal" role="dialog">
                  <div class="modal-dialog">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        
                        <h4 class="modal-title">Add Feedback</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="card-body">
                            <div class="row justify-content-center">
                              <div class="col-md-10 col-sm-12">
                                
                                <form  id="admin_labour_task_feedback_form" action="{{route('admin.work-order-management.taskFeedback')}}" method="post" enctype="multipart/form-data">
                                  @csrf
                                        <div>  
                                          <input type="hidden" name="task_details_id" id="task_details_id" />
                                   
                                          <div class="form-group">
                                            <label for="service_id">Task Feedback </label>
                                            <textarea class="form-control float-right" name="user_feedback" id="user_feedback">{{old('user_feedback')}}</textarea>
                                             @if($errors->has('user_feedback'))
                                              <span class="text-danger">{{$errors->first('user_feedback')}}</span>
                                             @endif  
                                          </div>
                                          
                                      <div>
                                     <button type="submit" class="btn btn-success">Submit</button> 

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

              <!-- Labour Feedback END-->  

@endsection

@push('custom-scripts')


<script>
    function assignLabourTask()
    {
      $('#addTaskModal').modal('show');
    }

    function checkOnDemandFree()
    {


     // alert('done');
      if (($('#task_title').val().length > 0) && ($('#user_id').val().length  > 0))
      {

        
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

            if (userLeaveList.length>0) {
                  document.getElementById("live_list").setAttribute("style","overflow:auto;height:150px;width:350px");
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

    function assignLabourMaintanenceTask()
    {
      $('#addMaintanenceTaskModal').modal('show');
    }

    
    function checkMaintanenceDaily()
    {
     // alert($('#task_title_maintanence_daily').val().length);
      if (($('#task_title_maintanence_daily').val().length > 0) && ($('#maintanence_user_id').val().length  > 0)  && ($('#work_date').val().length  > 0))
      {

        
        $('.btn-success').prop('disabled', false);
        //var userDataString =  document.getElementById('user_id').innerHTML;
        //var userDataString = $('#user_id option:selected').val();
        var userDataString = $('#maintanence_user_id').val();
        var dateString = $('#work_date').val();
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

            if (userLeaveList.length>0) {
                  document.getElementById("live_list").setAttribute("style","overflow:auto;height:150px;width:350px");
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

    

    function assignLabourOtherMaintanenceTask()
    {
      $('#addOtherMaintanenceTaskModal').modal('show');
    }
    
    
    function checkMaintanenceOther()
    {
     // alert($('#task_title_maintanence_daily').val().length);
      if (($('#task_title_maintanence_other').val().length > 0) && ($('#maintanence_other_user_id').val().length  > 0)  && ($('#work_date_other').val().length  > 0))
      {

        
        $('.btn-success').prop('disabled', false);
        //var userDataString =  document.getElementById('user_id').innerHTML;
        //var userDataString = $('#user_id option:selected').val();
        var userDataString = $('#maintanence_other_user_id').val();
        var dateString = $('#work_date_other').val();
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

            if (userLeaveList.length>0) {
                  document.getElementById("live_list").setAttribute("style","overflow:auto;height:150px;width:350px");
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



    function addFeedback(id)
    {
      $('#task_details_id').val(id);
      $('#addFeedbackModal').modal('show');
    }

    setTimeout(function() {
        $('.alert-dismissable').fadeOut('fast');
    }, 5000); 

    $('#user_id, #maintanence_user_id, #maintanence_other_user_id').multiselect({
    columns: 1,
    placeholder: 'Select Labour',
    search: true,
    selectAll: true
    });

    

    
    $('#slot_list').multiselect({
    columns: 1,
    placeholder: 'Select Slot',
    search: true,
    selectAll: true
    });


    $('.work_date, .work_date_other').multiselect({
    columns: 1,
    placeholder: 'Select Work Date',
    search: true,
    selectAll: true,
    });

    $( document ).ready(function() {
        $('#date_range').daterangepicker({
          //autoUpdateInput: false,
                 //locale: { format: 'DD/MM/YYYY' },
                 minDate: new Date('<?=@$work_order_list->start_date?>'),
                 startDate: new Date('<?=@$work_order_list->start_date?>'),
                 endDate: new Date('<?=@$work_order_list->start_date?>'),
                 
              }).on("change", function() {
        checkOnDemandFree();
      })
    });


    



    // $("#free_ondemand").on('click',function(e){ //also can use on submit
    //   e.preventDefault(); //prevent submit
    //   swal({
    //       title: "Are you sure?",
    //       type: "warning",
    //       showCancelButton: true,
    //       confirmButtonColor: "#DD6B55",
    //       confirmButtonText: "Yes!",
    //       cancelButtonText: "Cancel",
    //       closeOnConfirm: true
    //   }
    //   }).then(function(value) {
    //       if (value) {
    //         $('#frm_input_srt').submit();
    //       }
    //   });

    $("#free_ondemand").on('click',function(e){
      e.preventDefault();
      swal({
        title: "Are you sure?",
        text: "Once Assigned, you will not be able to modify this task!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        }).then((isConfirm) => {
            jQuery("#admin_labour_task_add_form").submit();
        });

    });     




</script>

<script type="text/javascript" src="{{asset('js/admin/work_order_management/daily-task-list.js')}}"></script>
@endpush


