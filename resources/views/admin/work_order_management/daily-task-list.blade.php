@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Task Calendar</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item active">Task</li>
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


            <div class="card-header">
              <div class="d-flex justify-content-between" >
                <div>
                  <!-- <a class="btn btn-success" style="text-align: right;" onclick="assignLabourTask()" href="{{route('admin.work-order-management.labourTaskCreate', $work_order_list->id)}}"> -->
                  <a class="btn btn-success" style="text-align: right;" onclick="assignLabourTask()" href="javascript:void(0)">
                   Assign Labour Task
                  </a>
                </div>
              </div>
            </div>
            
            <table class="table table-bordered" id="daily_task_management_table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Task Date</th>
                        <th>Labour Name</th>
                        <th>User Feedback</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <input type="hidden" id="labour_assigned_task_list" value="{{route('admin.work-order-management.list')}}">

            <div class="modal fade" id="addTaskModal" role="dialog">
              <div class="modal-dialog">
              
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    
                    <h4 class="modal-title">Add Task</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <div class="card-body">
                            @if(Session::has('success'))
                              <div class="alert alert-success alert-dismissable __web-inspector-hide-shortcut__">
                                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                  {{ Session::get('success') }}
                              </div>
                            @endif

                            @if(Session::has('error'))
                              <div class="alert alert-danger alert-dismissable">
                                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                  {{ Session::get('error') }}
                              </div>
                            @endif


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
                                      <input class="form-control parent_role_select2" style="width: 100%;" type="text" name="task_title" id="task_title" value="{{(old('task_title'))? old('task_id'):''}}">
                                      
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

                                    <div class="form-group required">
                                      <label for="labour_list_id">Labour List <span class="error">*</span></label>
                                      <select class="form-control   mdb-select md-form" multiple searchable="Search for..."  name="user_id" id="user_id" 
                                        >
                                         
                                         @forelse($labour_list as $labour_data)
                                               <option value="{{@$labour_data->id}}" {{(old('contract_id')== @$labour_data->id)? 'selected':''}}>{{@$labour_data->name}}</option>
                                          @empty
                                          <option value="">No Labour Found</option>
                                          @endforelse     
                                                                   
                                        </select>
                                      @if($errors->has('contract_id'))
                                      <span class="text-danger">{{$errors->first('contract_id')}}</span>
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
                                      <input type="text" class="form-control float-right" id="date_range" name="date_range" value="">
                                    </div>
                                    <!-- /.input group -->
                                  </div>
                                    

                                  <div class="form-group">
                                    <label for="service_id">Task Description</label>
                                    <textarea class="form-control float-right" name="task_description" id="task_description">{{old('task_description')}}</textarea>
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

@endsection

@push('custom-scripts')


<script>
 function assignLabourTask()
    {
      $('#addTaskModal').modal('show');
    }
    function addFeedback(id)
    {
      $('#task_details_id').val(id);
      $('#addFeedbackModal').modal('show');
    }

    setTimeout(function() {
        $('.alert-dismissable').fadeOut('fast');
    }, 5000); 

  

    $('#user_id').multiselect({
      includeSelectAllOption: true,
      enableFiltering: true,
      enableCaseInsensitiveFiltering: true,
      filterPlaceholder: 'Search for something...',
      buttonWidth:'350px'
     });

    $('.multiselect-clear-filter').hide();

    $( document ).ready(function() {
        $('#date_range').daterangepicker({
          //autoUpdateInput: false,
                 locale: { format: 'DD/MM/YYYY' },
                 minDate: new Date('<?=@$work_order_list->start_date?>'),
                 startDate: new Date('<?=@$work_order_list->start_date?>'),
                 endDate: new Date('<?=@$work_order_list->start_date?>'),
              })
    });
</script>

<script type="text/javascript" src="{{asset('js/admin/work_order_management/daily-task-list.js')}}"></script>
@endpush


