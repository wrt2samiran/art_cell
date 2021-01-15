@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('calendar_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('calendar_module.filter_labels.calendar')}}</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
          <div>
              @if(Session::has('success-message'))
                  <div class=" alert-success alert-dismissable" style="line-height:300%">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('success-message') }}
                      {{ Session::forget('success-message') }}
                  </div>
              @endif
              @if(Session::has('error'))
                  <div class=" alert-danger alert-dismissable" style="line-height:300%">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('error') }}
                      {{ Session::forget('error') }}
                  </div>
              @endif
              @if(@$error)
                  <div class=" alert-danger alert-dismissable" style="line-height:300%">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ @$error}}
                  </div>
              @endif
            </div>
            <div class="container-fluid">             
                <div class="filter-area ">
                
                    <div class="row">
                      <div class="col-lg-12">
                        <?php //dd($request->all()); 
                        //if(!is_array($request->property_id)){ settype($request->property_id, "array");}?>
                        <button class="btn-filter-drop">{{__('calendar_module.filter_labels.filter')}} <i class="fa fa-filter"></i></button> 
                        <form  method="post" id="filter_calendar" action="{{route('admin.calendar.calendardata')}}" method="post" enctype="multipart/form-data">
                          @csrf
                          <div class="row">
                            <input type="hidden" name="search" id="search" value="Search">

                            <div class="col-md-4 form-group" id="status-filter-container">

                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_property')}}" multiple class="chosen-select" tabindex="8" name="property_id[]" id="property_id"  multiple="multiple" onchange="getWorkOderList()">
                                      @foreach($property_list as $property_key=> $property_data)
                                         <option value="{{$property_data->id}}" @if(is_array($request->property_id))@if(in_array($property_data->id, $request->property_id)) selected @endif @elseif($property_data->id == $request->property_id) selected  @endif >{{$property_data->property_name}}</option>
                                    
                                      @endforeach
                                </select>
                                <input type="button" id="property_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                              </div>
                            
                            <div class="col-md-4 form-group" id="status-filter-container">
                             

                               <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_work_order')}}"  class="chosen-select" tabindex="8" name="work_order_id[]" id="work_order_id"  multiple="multiple" onchange="getTaskLIst()">
                                  <option value=""> </option>
                                    
                                      @forelse(@$allPropertyRelatedWorkOrders as $work_order_key=> $work_order_data)
                                         <option value="{{$work_order_data->id}}" @if(is_array($request->work_order_id)) @if(in_array($work_order_data->id, $request->work_order_id)) selected @endif @endif>{{@$work_order_data->task_title}}</option>
                                      @empty
                                      <option value="">No Work Order Found</option>
                                      @endforelse
                                     
                                </select>
                                <input type="button" id="work_order_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>

                            <div class="col-md-4 form-group" id="status-filter-container">
                              <select data-placeholder="{{__('calendar_module.filter_labels.filter_task')}}"  class="chosen-select" tabindex="2"  name="task_id[]" id="task_id" multiple onchange="getLabourList()" >

                                  @forelse(@$task_list as $task_list_key=> $task_list_data)
                                     <option value="{{$task_list_data->id}}" @if(is_array($request->task_id)) @if(in_array($task_list_data->id, $request->task_id)) selected @endif @endif>{{@$task_list_data->task_title}}</option>
                                  @empty
                                  <option value="">No Task Found</option>
                                  @endforelse
                                  
                               </select>
                               <input type="button" id="task_id_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>
                         
                            <div class="col-md-4 form-group" id="status-filter-container">
                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_labour')}}"  class="chosen-select" tabindex="8"  name="labour_id[]" id="labour_id" multiple >
                                  @if(is_array(@$allLabourList))
                                    @forelse(@$allLabourList as $labour_list_key=> $labour_list_data)
                                       <option value="{{$labour_list_data->userDetails->id}}" @if(is_array($request->labour_id)) @if(in_array($labour_list_data->userDetails->id, $request->labour_id)) selected @endif @endif>{{@$labour_list_data->userDetails->name}}</option>
                                    @empty
                                    <option value="">No Labour Found</option>
                                    @endforelse
                                  @endif  
                               </select>

                               <input type="button" id="labour_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>
                            <div class="col-md-4 form-group" id="status-filter-container">
                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_maintanence_type')}}"   class="chosen-select" tabindex="8" name="maintenance_type[]" id="maintenance_type" multiple >
                                  @if(is_array(@$allContractServices))
                                    @forelse(@$allContractServices as $contract_service_list_key=> $contract_service_list_data)
                                       <option value="{{$contract_service_list_data->contract_services->service_id}}" @if(is_array($request->maintenance_type)) @if(in_array($contract_service_list_data->contract_services->service_id, $request->labour_id)) selected @endif @endif>{{@$contract_service_list_data->contract_services->service_type}}</option>
                                    @empty
                                    <option value="">No Maintenance Type Found</option>
                                    @endforelse
                                  @endif  
                                   
                               </select>
                               <input type="button" id="maintenance_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>
                            <div class="col-md-4 form-group" id="status-filter-container">
                                <select data-placeholder="Filter with Service"  class="chosen-select" tabindex="8"  name="service_type[]" id="service_type" multiple >
                                  
                               </select>
                               <input type="button" id="service_all" value="Select All">
                            </div>

                            <div class="col-md-4 form-group" id="status-filter-container">
                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_status')}}"  class="chosen-select" tabindex="8"  name="status[]" id="status" multiple >
                                 
                                  <option value="0" @if(is_array($request->status)) @if(in_array(0, $request->status)) selected @endif @endif>Pending</option>
                                  <option value="1" @if(is_array($request->status)) @if(in_array(1, $request->status)) selected @endif @endif>Overdue</option>
                                  <option value="2" @if(is_array($request->status)) @if(in_array(2, $request->status)) selected @endif @endif>Completed</option>
                                  <option value="4" @if(is_array($request->status)) @if(in_array(4, $request->status)) selected @endif @endif>Warning</option>
                                  
                               </select>
                               <input type="button" id="status_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>
                            
                            <div class="col-md-4 form-group" id="status-filter-container" >
                              <input type="checkbox" name="un_assigned" id="un_assigned" value="1" @if($request->un_assigned==1) checked @endif> {{__('calendar_module.filter_labels.un_assigned')}}
                            </div>
                            <div class="col-md-4 form-group" id="status-filter-container" >
                              <input type="checkbox" name="emergency_service" id="emergency_service" value="1" @if($request->emergency_service==1) checked @endif> {{__('calendar_module.filter_labels.emergency_service')}}
                            </div>
                            <div class="col-md-12 btn-en-ar" id="status-filter-container">
                               <button type="submit" class="btn btn-search disable-button">{{__('general_sentence.button_and_links.search')}}</button> 
                            </div>
                          </div>
                           
                        </form>
                        
                      </div>
                    </div>
                </div>
                <?php //dd($task_list);?>
                
                <section class="content">
                  <div class="">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="sticky-top mb-3">
                          <div class="card">
                            <div class="card-header">
                              <h4 class="card-title">{{__('calendar_module.filter_labels.color_details')}}</h4>
                            </div>
                            <div class="card-body">
                              <!-- the events -->
                              <div id="external-events" style="width: 88px">
                                <div class="external-event bg-success">{{__('general_sentence.status_button.completed')}}</div>
                                <div class="external-event bg-warning">{{__('general_sentence.status_button.pending')}}</div>
                                <div class="external-event btn-secondary">{{__('general_sentence.status_button.overdue')}}</div>
                                <div class="external-event bg-danger">{{__('general_sentence.status_button.warning')}}</div>
                                <!-- <div class="external-event" style="background: #ff6600">Emergency</div> -->
                              </div>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <!-- /.card -->
                         
                        </div>
                      </div>
                      <!-- /.col -->                      
                      <div class="col-md-9">
                        <div class="card card-primary">
                          <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                          </div>

                          <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                      </div> 
                      <!-- /.col -->                     
                    </div>
                    <!-- /.row -->
                  </div><!-- /.container-fluid -->
                </section>

                
                <!-- *********Showing the Day wise Labour Task (Sub-Task) List********** -->

                <div class="modal fade" id="showTaskListModal" role="dialog" style="padding-right: 100px !important;">
                  <div class="modal-dialog">
                    <div class="modal-content" style=" width: 1000px; margin: auto;">
                      <div class="modal-header">
                        <h4 class="modal-title">Day Task List</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="card-body">
                          <table class="table table-bordered" id="task_labour_list_management_table">
                          </table>                                   
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('general_sentence.button_and_links.close')}}</button>
                      </div>
                    </div>                 
                  </div>
                </div>

     

@endsection

@push('custom-scripts')

<!-- fullCalendar 2.2.5 -->
<script src="{{asset('assets/plugins/moment/moment.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@4.4.2/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@4.4.2/main.min.js"></script>
<script src="{{asset('assets/plugins/fullcalendar-timegrid/main.min.js')}}"></script>
<script src="{{asset('assets/plugins/fullcalendar-interaction/main.min.js')}}"></script>
<script src="{{asset('assets/plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
<script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js"></script>
<script>

  $("#property_all").click(function(){
       if($("#property_all").hasClass('property_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#property_all").removeClass('property_selected');
            $("#property_id").trigger("chosen:updated");
            getWorkOderList();
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#property_all").addClass('property_selected');
            $("#property_id").trigger("chosen:updated");
            getWorkOderList();
         }
  });

  $("#work_order_all").click(function(){
       if($("#work_order_all").hasClass('work_order_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#work_order_all").removeClass('work_order_selected');
            $("#work_order_id").trigger("chosen:updated");
            getTaskLIst();
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#work_order_all").addClass('work_order_selected');
            $("#work_order_id").trigger("chosen:updated");
            getTaskLIst();
         }
  });

  $("#task_id_all").click(function(){
       if($("#task_id_all").hasClass('task_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#task_id_all").removeClass('task_selected');
            $("#task_id").trigger("chosen:updated");
            getLabourList();
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#task_id_all").addClass('task_selected');
            $("#task_id").trigger("chosen:updated");
            getLabourList();
         }
  });



  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    //var Draggable = FullCalendarInteraction.Draggable;

    //var containerEl = document.getElementById('external-events');
    //var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');



    var calendar = new Calendar(calendarEl, {
      eventLimit: true,
      views: {
        timeGrid: {
          eventLimit: {
              'month': 2, // adjust to 4 only for months
              'agenda': 2 // display all events for other views
          }
        }
      },
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      header    : {
        left  : 'prevYear,prev,next,nextYear today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      
      dateClick: function(info) {
        checKClickedDate(info.dateStr);
      },
      'themeSystem': 'bootstrap',
      //Random default events
      events    : [
       
       <?php if($request->emergency_service==1 || $request->un_assigned==1){


                    foreach($workOrder as $work_order_data)
                      { 

                        if($work_order_data->emergency_service=='Y')
                          {
                              $color = '#ff6600';
                          }
                          else
                          {
                              if($work_order_data->status==1)
                              {
                                  $color = '#545b62';              
                              }
                              else if($work_order_data->status==0)
                              {
                                $color = '#ffc107';
                              }
                              else if($work_order_data->status==2)
                              {
                                $color = '#28a745';
                              }

                              else if($work_order_data->status==4)
                              {
                                $color = '#dc3d45';
                              }
                          }             
          ?>

                    {
                    title          : '<?=$work_order_data->task_title?>',
                    start          : '<?=$work_order_data->start_date?>',
                    end            : '<?=$work_order_data->start_date?>',
                    backgroundColor: '<?=$color?>', //red
                    borderColor    : '<?=$color?>', //red
                    id             : '<?=$work_order_data->id?>',
                    allDay         : false,
                    
                    description: '{{__("calendar_module.work_order_title")}} : <?=@$work_order_data->task_title?><br>{{__("calendar_module.propety_name")}} : <?=@$work_order_data->property->property_name?><br>{{__("calendar_module.service")}} : <?=@$work_order_data->service->service_name?><br>{{__("calendar_module.service_type")}} : <?=@$work_order_data->contract_services->service_type?><br>{{__("general_sentence.breadcrumbs.country")}} : <?=@$work_order_data->property->country->name?><br>{{__("general_sentence.breadcrumbs.state")}} : <?=@$work_order_data->property->state->name?><br>{{__("general_sentence.breadcrumbs.city")}} : <?=@$work_order_data->property->city->name?><br>{{__("calendar_module.task_start_date")}} : <?=@$work_order_data->start_date?>'
                  },
          <?php  } } else{
                   foreach($task_details_list as $task_data)
                    { 
                      //foreach($task_data->task_details as $detailsData){
                      $user = $task_data->userDetails->name;
                      if($task_data->status==1)
                      {
                          $color = '#545b62';              
                      }
                      else if($task_data->status==0)
                      {
                        $color = '#ffc107';
                      }
                      else if($task_data->status==2)
                      {
                        $color = '#28a745';
                      }
                      else if($task_data->status==4)
                      {
                        $color = '#dc3d45';
                      }
                  
            ?>

                  {
                    title          : '<?=$task_data->task->task_title?>(<?=$user?>)',
                    start          : '<?=$task_data->task_date?>',
                    end            : '<?=$task_data->task_date?>',
                    backgroundColor: '<?=$color?>', //red
                    borderColor    : '<?=$color?>', //red
                    id             : '<?=$task_data->id?>',
                    allDay         : false,
                    
                    description: '{{__("calendar_module.task_title")}} : <?=@$task_data->task->task_title?><br>{{__("calendar_module.propety_name")}} : <?=@$task_data->task->property->property_name?><br>{{__("calendar_module.service")}} : <?=@$task_data->task->service->service_name?><br>{{__("calendar_module.service_type")}} : <?=@$task_data->task->contract_services->service_type?><br>{{__("general_sentence.breadcrumbs.country")}} : <?=@$task_data->task->property->country->name?><br>{{__("general_sentence.breadcrumbs.state")}} : <?=@$task_data->task->property->state->name?><br>{{__("general_sentence.breadcrumbs.city")}} : <?=@$task_data->task->property->city->name?><br>{{__("calendar_module.task_start_date")}} : <?=@$task_data->task_date?>'
                  },


          <?php }} ?>        
      ],
      timeFormat: 'H(:mm)', // uppercase H for 24-hour clock、
      displayEventTime: false,
      eventRender: function(info) {
        var tooltip = new Tooltip(info.el, {
          title: info.event.extendedProps.description,
          placement: 'top',
          trigger: 'click',
          html: true,
          container: 'body'
        });
      },

      editable  : true,
      droppable : false, // this allows things to be dropped onto the calendar !!!
      eventDrop: function(info) {
    

   
    if (confirm("This will change the date which was actually entered while creqated. Are you still want to make this change?")) {
      onTaskChange(info.event.id,  info.event.start,  info.event.end );
    }
    else
    {
      info.revert();
    }
    
    
  },
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      },
        
    });

    

    calendar.render();
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      ini_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  });





$(document).on('click', 'td', function() {
  <?php if(\Auth::guard('admin')->user()->role_id==4){ ?>
      //$('#addTaskModal').modal('show');
      
  <?php } ?>

 });


   
function checKClickedDate(clicked_date){

    var task_list = '';
    var taskDate = '';
    var total = 0;

      if(new Date(clicked_date) >= new Date())
      {
        task_list +=  '<tr  class="col-12 text-right"><td colspan="7" style="margin:0; padding:0;" a><a class="btn btn-success" href="{{route('admin.work-order-management.list')}}"><p>{{__('general_sentence.create_task')}}</p></a></td></tr>'; 
      }

      task_list +=  '<tr><th><strong>{{__('calendar_module.task_title')}}</strong></th><th><strong>{{__('calendar_module.property_name')}}</strong></th><th><strong>{{__('calendar_module.service')}}</strong></th><th><strong>{{__('calendar_module.service_type')}}</strong></th><th><strong>{{__('calendar_module.task_date')}}</strong></th><th><strong>{{__('calendar_module.status')}}</strong></th><th><strong>{{__('calendar_module.action')}}</strong></th></tr>';
          <?php if($request->un_assigned==1 || $request->emergency_service==1 ) { foreach($workOrder as $work_order_data){

            $details_url = route('admin.work-order-management.show',$work_order_data->id);
          ?>

              taskDate = '<?php echo date("Y-m-d", strtotime(@$work_order_data->start_date)); ?>';

              if(clicked_date==taskDate){
                    total++;
                    task_list += '<tr><th><?php echo $work_order_data->task_title; ?></th><th><?php echo @$work_order_data->property->property_name;?> </th><th><?php echo @$work_order_data->service->service_name;?></th><th><?php echo @$work_order_data->contract_services->service_type;?></th><th><?php echo date("d/m/Y H:i a", strtotime(@$work_order_data->start_date)); ?></th><th><?php 

                    if(@$work_order_data->emergency_service=='Y') { echo '<div class="external-event ui-draggable ui-draggable-handle" style="position: relative; background-color: #ff6600 !important">'?>{{__("general_sentence.status_button.emergency")}}<?php '</div>';}

                    elseif(@$work_order_data->status==0) {echo '<div class="external-event bg-warning ui-draggable ui-draggable-handle" style="position: relative; background-color: #ffc107 !important">'?>{{__("general_sentence.status_button.pending")}}<?php '</div>'; }
                    
                    elseif(@$work_order_data->status==1) {echo '<div class="external-event btn-secondary ui-draggable ui-draggable-handle" style="position: relative; background-color: #5a6268 !important">'?>{{__("general_sentence.status_button.overdue")}}<?php '</div>';} 
                    
                    elseif(@$work_order_data->status==2) {echo '<div class="external-event bg-success ui-draggable ui-draggable-handle" style="position: relative; background-color: #3ea846 !important">'?>{{__("general_sentence.status_button.completed")}}<?php '</div>';} 
                    
                    elseif(@$work_order_data->status==4) {echo '<div class="external-event bg-danger ui-draggable ui-draggable-handle" style="position: relative; background-color: #dc3d45!important">'?>{{__("general_sentence.status_button.warning")}}<?php }?></div></th><th> <?php 
                    
                    if(@$work_order_data->status==2){?><a target="_blank" href="<?php echo @$details_url;?>"  title="{{__("general_sentence.status_button.rating_and_view")}}">&nbsp; &nbsp;{{__("general_sentence.status_button.rating_and_view")}}</a><?php } 

                    
                    else{?><a target="_blank" href="<?php echo @$details_url;?>"  title="{{__("general_sentence.status_button.view")}}">&nbsp; &nbsp; {{__("general_sentence.status_button.view")}}</a><?php }?></th></tr>';
                    } 

         <?php } } else { foreach($task_details_list as $task_data){
                  $details_url = route('admin.work-order-management.labourTaskDetails',@$task_data->id);
                  $complain_url = route('admin.complaints.create','workorder_id='.@$task_data->task->work_order_id);
          ?>
            taskDate = '<?php echo date("Y-m-d", strtotime($task_data->task_date)); ?>';
            if(clicked_date==taskDate){
                  total++;
                  task_list += '<tr><th><?php echo $task_data->task->task_title; ?></th><th><?php echo @$task_data->task->property->property_name;?> </th><th><?php echo @$task_data->task->service->service_name;?></th><th><?php echo @$task_data->task->contract_services->service_type;?></th><th><?php echo date("d/m/Y H:i a", strtotime(@$task_data->task_date)); ?></th><th><?php 

                    if(@$task_data->status==0) {echo  '<div class="external-event bg-warning ui-draggable ui-draggable-handle" style="position: relative; background-color: #ffc107 !important">' ?>{{__("general_sentence.status_button.pending")}}<?php '</div>'; }

                    elseif(@$task_data->status==1){ echo '<div class="external-event btn-secondary ui-draggable ui-draggable-handle" style="position: relative; background-color: #5a6268 !important">' ?>{{__("general_sentence.status_button.overdue")}}<?php '</div>'; }

                    elseif(@$task_data->status==2) { echo '<div class="external-event bg-success ui-draggable ui-draggable-handle" style="position: relative; background-color: #3ea846 !important">' ?>{{__("general_sentence.status_button.completed")}}<?php '</div>'; }

                    elseif(@$task_data->status==4) { echo '<div class="external-event bg-danger ui-draggable ui-draggable-handle" style="position: relative; background-color: #dc3d45!important">' ?> {{__("general_sentence.status_button.warning")}}<?php '</div>'; } ?></th><th><a target="_blank" href="<?php echo $complain_url;?>" title="{{__("general_sentence.status_button.complain")}}">{{__("general_sentence.status_button.complain")}}</a> &nbsp &nbsp <?php 

                    if(@$task_data->status==2){?><a target="_blank" href="<?php echo @$details_url;?>"  title="{{__("general_sentence.status_button.rating_and_view")}}">&nbsp; &nbsp; {{__("general_sentence.status_button.rating_and_view")}}</a><?php } 

                    else{?><a target="_blank" href="<?php echo @$details_url;?>"  title="{{__("general_sentence.status_button.view")}}"> &nbsp; &nbsp; {{__("general_sentence.status_button.view")}}</a><?php }?></th></tr>';
                  } 
         <?php  } } ?>
         if(total==0)
         {
            task_list += '<tr><th colspan="7"><span><strong style="color:red; text-align: justify;">{{__("general_sentence.no_data_found")}}</strong></span></th></tr>';
         }
                               


      $("#task_labour_list_management_table").html(task_list);
      $('#showTaskListModal').modal('show');
  }

function onTaskChange(task_details_id, start_date, end_date){

  let modified_start_date = JSON.stringify(start_date);
  modified_start_date = modified_start_date.slice(1,11);

  let modified_end_date = JSON.stringify(end_date)
  modified_end_date = modified_end_date.slice(1,11)
     $.ajax({
       
        url: "{{route('admin.calendar.updateTaskDetails')}}",
        type:'post',
        dataType: "json",
        data:{task_details_id:task_details_id,modified_start_date:modified_start_date,modified_end_date:modified_end_date,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status=='false'){
             location.reload();
            }
            else
            { 
              console.log(response.status);
              location.reload();
            }
        });
    }    


 setTimeout(function() {
        $('.alert-dismissable').fadeOut('fast');
    }, 5000);    


function getWorkOderList(){

 var property_id =  $('#property_id').val();
 var test = $('.search-choice-close').val();
 

    
   $.ajax({
     
      url: "{{route('admin.calendar.getPropertyWorkOrderLIst')}}",
      type:'post',
      dataType: "json",
      data:{property_id:property_id,_token:"{{ csrf_token() }}"}
      }).done(function(response) {
         
         console.log(response.status);
          if(response.status){
           
          var stringified = JSON.stringify(response.allWorkOrders);
          var workOrderData = JSON.parse(stringified);
          console.log(workOrderData);
           var workorder_list = '';
           $.each(workOrderData,function(index, workorder){
                  workorder_list += '<option value="'+workorder.id+'">'+ workorder.task_title +'</option>';
           });
           console.log(workorder_list);
              $("#work_order_id").html(workorder_list);
              $("#work_order_id").trigger("chosen:updated");
          }

        else
          {
              var workorder_list = '';
              $("#work_order_id").html(workorder_list);
              $("#work_order_id").trigger("chosen:updated");
              getTaskLIst();
          }
      });
 }
  


function getTaskLIst(){

 var work_order_id =  $('#work_order_id').val();


      $.ajax({
   
      url: "{{route('admin.calendar.getTaskLIst')}}",
      type:'post',
      dataType: "json",
      data:{work_order_id:work_order_id,_token:"{{ csrf_token() }}"}
      }).done(function(response) {
         
         
          if(response.status){       
           var stringified = JSON.stringify(response.allTasks);
           var taskdata = JSON.parse(stringified);

           var stringifiedService = JSON.stringify(response.allService);
           var serviceData = JSON.parse(stringifiedService);

           var stringifiedMaintanence = JSON.stringify(response.allContractServices);
           var maintanenceData = JSON.parse(stringifiedMaintanence);

           var task_list = '';
           var service_list = '';
           var maintenance_type_list = '';
           //$('#task_id').val('').multiselect('refresh');
           console.log(taskdata);
           $.each(taskdata,function(index, task_id){
                  task_list += '<option value="'+task_id.id+'">'+ task_id.task_title +'</option>';
           });

           $.each(serviceData,function(index, serviceVal){
                  service_list += '<option value="'+serviceVal.service.id+'">'+ serviceVal.service.service_name +'</option>';
           });

           $.each(maintanenceData,function(index, maintanenceVal){
                  maintenance_type_list += '<option value="'+maintanenceVal.service_id+'">'+ maintanenceVal.service_type +'</option>';
           });

              $("#task_id").html(task_list);
              $("#service_type").html(service_list);
              $("#maintenance_type").html(maintenance_type_list);

              $("#task_id").trigger("chosen:updated");
              $("#service_type").trigger("chosen:updated");
              $("#maintenance_type").trigger("chosen:updated");
              
          }

          else
          {
              var task_list = '';
              var service_list = '';
              var maintenance_type_list = '';
              $("#task_id").html(task_list);
              $("#service_type").html(service_list);
              $("#maintenance_type").html(maintenance_type_list);

              $("#task_id").trigger("chosen:updated");
              $("#service_type").trigger("chosen:updated");
              $("#maintenance_type").trigger("chosen:updated");
          }
          
      });
  
 
}



function getLabourList(){

 var task_id =  $('#task_id').val();

 $.ajax({
   
    url: "{{route('admin.calendar.getTaskLabour')}}",
    type:'post',
    dataType: "json",
    data:{task_id:task_id,_token:"{{ csrf_token() }}"}
    }).done(function(response) {
       
       console.log(response.status);
        if(response.status){
         
        var stringified = JSON.stringify(response.allLabourList);
        var labourData = JSON.parse(stringified);
        console.log(labourData);
         var labour_list = '';
         $.each(labourData,function(index, labour){
                labour_list += '<option value="'+labour.user_details.id+'">'+ labour.user_details.name +'</option>';
         });
         //console.log(workorder_list);
            $("#labour_id").html(labour_list);
            $("#labour_id").trigger("chosen:updated"); 
            
        }

        else
          {
              var labour_list = '';
              $("#labour_id").html(labour_list);
              $("#labour_id").trigger("chosen:updated");
          }
    });
}

$(document).ready(function(){
    $('[rel=tooltip]').tooltip({ trigger: "click" });
});
    

</script>




<script type="text/javascript" src="{{asset('js/admin/service_management/list.js')}}"></script>
<script type="text/javascript">
  $("button.btn-filter-drop").click(function(){
  $("#filter_calendar").toggleClass('show');
});
</script>
@endpush


