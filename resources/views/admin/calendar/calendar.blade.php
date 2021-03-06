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
                  <div class="alert-success alert-dismissable" style="line-height:300%">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('success-message') }}
                      {{ Session::forget('success-message') }}
                  </div>
              @endif
              @if(Session::has('error'))
                  <div class="alert-danger alert-dismissable" style="line-height:300%">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ Session::get('error') }}
                      {{ Session::forget('error') }}
                  </div>
              @endif
              @if(@$error)
                  <div class="alert-danger alert-dismissable" style="line-height:300%">
                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                      {{ @$error}}
                  </div>
              @endif
            </div>
            <div class="container-fluid">             
                <div class="filter-area ">
                  <?php //dd($sqlContract);?>
                    <div class="row">
                      <div class="col-lg-12">
                        <button class="btn-filter-drop">{{__('calendar_module.filter_labels.filter')}} <i class="fa fa-filter"></i></button> 
                        <form  method="post" id="filter_calendar" action="{{route('admin.calendar.calendardata')}}" method="post" enctype="multipart/form-data" <?php if($request->has('search')){?> class='show' <?php } ?>>
                          @csrf
                            <div class="row">
                            <input type="hidden" name="search" id="search" value="Search">

                            <div class="col-md-4 form-group" id="status-filter-container">

                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_property')}}" multiple class="chosen-select" tabindex="8" name="property_id[]" id="property_id"  multiple="multiple" onchange="getContractList()">
                                      @foreach(@$sqlContract as $property_key=> $property_data)
                                         <option value="{{$property_data->property->id}}" @if(is_array($request->property_id))@if(in_array($property_data->property->id, $request->property_id)) selected @endif @elseif($property_data->property->id == $request->property_id) selected  @endif >{{$property_data->property->property_name}}</option>
                                    
                                      @endforeach
                                </select>
                                <input type="button" id="property_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>

                            <div class="col-md-4 form-group" id="status-filter-container">

                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_contract')}}" multiple class="chosen-select" tabindex="8" name="contract_list[]" id="contract_list"  multiple="multiple" onchange="getWorkOderList()">
                                      @foreach(@$sqlContract as $contract_key=> $contract_data)
                                         <option value="{{$contract_data->id}}" @if(is_array($request->contract_list))@if(in_array($contract_data->id, $request->contract_list)) selected @endif @elseif($contract_data->id == $request->contract_list) selected  @endif >{{$contract_data->title}}</option>
                                    
                                      @endforeach
                                </select>
                                <input type="button" id="contract_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>
                            
                            <div class="col-md-4 form-group" id="status-filter-container">

                               <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_work_order')}}"  class="chosen-select" tabindex="8" name="work_order_id[]" id="work_order_id"  multiple="multiple" onchange="getServiceProviderLIst()">
                                  <option value=""> </option>
                                    
                                      @forelse(@$allPropertyRelatedWorkOrders as $work_order_key=> $work_order_data)
                                         <option value="{{$work_order_data->id}}" @if(is_array($request->work_order_id)) @if(in_array($work_order_data->id, $request->work_order_id)) selected @endif @endif>{{@$work_order_data->task_title}}</option>
                                      @empty
                                      <option value="">No Work Order Found</option>
                                      @endforelse
                                     
                                </select>
                                <input type="button" id="work_order_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>
                            <?php //dd($allWorkOrdersRelatedServices);?>
                            <div class="col-md-4 form-group" id="status-filter-container">
                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_service_provider')}}"  class="chosen-select" tabindex="8"  name="service_provider_id[]" id="service_provider_id" multiple >
                                  
                                    @forelse(@$allWorkOrdersRelatedServiceProvider as $service_provider_key=> $servce_provider_data)
                                       <option value="{{$servce_provider_data->userDetails->id}}" @if(is_array($request->service_provider_id)) @if(in_array($servce_provider_data->userDetails->id, $request->service_provider_id)) selected @endif @endif>{{@$servce_provider_data->userDetails->name}}</option>
                                    @empty
                                    <option value="">No Service Provider Found</option>
                                    @endforelse
                                  
                               </select>

                               <input type="button" id="service_provider_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>
                            <div class="col-md-4 form-group" id="status-filter-container">
                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_maintanence_type')}}"   class="chosen-select" tabindex="8" name="maintenance_type[]" id="maintenance_type" multiple >
                                    @forelse(@$allWorkOrdersRelatedContractServices as $contract_service_list_key=> $contract_service_list_data)
                                       <option value="{{$contract_service_list_data->contract_services->service_id}}" @if(is_array($request->maintenance_type)) @if(in_array($contract_service_list_data->contract_services->service_id, $request->maintenance_type)) selected @endif @endif>{{@$contract_service_list_data->contract_services->service_type}}</option>
                                    @empty
                                    <option value="">No Maintenance Type Found</option>
                                    @endforelse
                                   
                               </select>
                               <input type="button" id="maintenance_all" value="{{__('general_sentence.button_and_links.select_all')}}">
                            </div>
                            <div class="col-md-4 form-group" id="status-filter-container">
                                <select data-placeholder="{{__('calendar_module.filter_labels.filter_with_service')}}"  class="chosen-select" tabindex="8"  name="service_type[]" id="service_type" multiple >
                                    @forelse(@$allWorkOrdersRelatedServices as $service_type_key=> $service_type_list_data)
                                       <option value="{{$service_type_list_data->service->id}}" @if(is_array($request->maintenance_type)) @if(in_array($service_type_list_data->service->id, $request->maintenance_type)) selected @endif @endif>{{@$service_type_list_data->service->service_name}}</option>
                                    @empty
                                    <option value="">No Maintenance Type Found</option>
                                    @endforelse
                                  
                               </select>
                               <input type="button" id="service_all" value="{{__('general_sentence.button_and_links.select_all')}}">
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
                
                <section class="content">
                  <div class="">
                    <div class="row">
                      <div class="col-md-9">
                        <div class="sticky-top mb-3">
                          <div class="card">
                            <div class="card-header">
                              <h4 class="card-title">{{__('calendar_module.filter_labels.color_details')}}</h4>
                            </div>
                            <div class="card-body">
                              <!-- the events -->
                              <div id="external-events" style="width: 88px">
                                <!-- <div class="external-event bg-success">{{__('general_sentence.status_button.completed')}}</div>
                                <div class="external-event bg-warning">{{__('general_sentence.status_button.pending')}}</div>
                                <div class="external-event btn-secondary">{{__('general_sentence.status_button.overdue')}}</div>
                                <div class="external-event bg-danger">{{__('general_sentence.status_button.warning')}}</div>
                                <div class="external-event" style="background: #ff6600">{{__('general_sentence.status_button.emergency')}}</div> -->
                                @foreach($status_list as $status_data)
                                  <div class="external-event bg" style="background-color: {{@$status_data->color_code}}">{{@$status_data->status_name}}</div>
                                @endforeach
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

                

                <div class="modal fade" id="showWorkOrderListModal" role="dialog" style="padding-left: 50px !important;">
                  <div class="modal-dialog">
                    <div class="modal-content" style=" width: 1100px; margin: auto;">
                      <div class="modal-header">
                        <h4 class="modal-title day-work-list"></h4>
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

<?php //dd($work_order_list);?>
<?php $list = json_encode($work_order_list);

$filtered = array();
foreach($work_order_list as $value) {
    $filtered[] = $value;
}
$list = json_encode($filtered);
?>

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
      //alert('test');
       if($("#property_all").hasClass('property_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#property_all").removeClass('property_selected');
            $("#property_id").trigger("chosen:updated");
            getContractList();
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#property_all").addClass('property_selected');
            $("#property_id").trigger("chosen:updated");
            getContractList();
         }
  });

  $("#contract_all").click(function(){
      //alert('test');
       if($("#contract_all").hasClass('contract_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#contract_all").removeClass('contract_selected');
            $("#contract_list").trigger("chosen:updated");
            getWorkOderList();
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#contract_all").addClass('contract_selected');
            $("#contract_list").trigger("chosen:updated");
            getWorkOderList();
         }
  });

  $("#work_order_all").click(function(){
      //alert('test');
       if($("#work_order_all").hasClass('work_order_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#work_order_all").removeClass('work_order_selected');
            $("#work_order_id").trigger("chosen:updated");
            getServiceProviderLIst();
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#work_order_all").addClass('work_order_selected');
            $("#work_order_id").trigger("chosen:updated");
            getServiceProviderLIst();
         }
  });


  $("#service_provider_all").click(function(){
      //alert('test');
       if($("#service_provider_all").hasClass('service_provider_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#service_provider_all").removeClass('service_provider_selected');
            $("#service_provider_id").trigger("chosen:updated");
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#service_provider_all").addClass('service_provider_selected');
            $("#service_provider_id").trigger("chosen:updated");
         }
  });

  $("#maintenance_all").click(function(){
      //alert('test');
       if($("#maintenance_all").hasClass('maintenance_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#maintenance_all").removeClass('maintenance_selected');
            $("#maintenance_type").trigger("chosen:updated");
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#maintenance_all").addClass('maintenance_selected');
            $("#maintenance_type").trigger("chosen:updated");
         }
  });

  $("#service_all").click(function(){
      //alert('test');
       if($("#service_all").hasClass('service_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#service_all").removeClass('service_selected');
            $("#service_type").trigger("chosen:updated");
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#service_all").addClass('service_selected');
            $("#service_type").trigger("chosen:updated");
         }
  });

  $("#status_all").click(function(){
      //alert('test');
       if($("#status_all").hasClass('status_selected') ){
            $(this).parent().find('option').prop("selected", "");
            $("#status_all").removeClass('status_selected');
            $("#status").trigger("chosen:updated");
            
        }else{
            $(this).parent().find('option').prop("selected","selected");
            $("#status_all").addClass('status_selected');
            $("#status").trigger("chosen:updated");
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
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function(eventEl) {
        console.log(eventEl);
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
        };
      }
    });
     const colors = <?=$list?>;

  function iterate(item, index) {
    console.log(`${item} has index ${index}`);
  }

  colors.forEach(iterate);



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
       
       <?php foreach($work_order_list as $work_order_data){ 
               

                  $user = $work_order_data->userDetails->name;
                   
                    if($work_order_data->emergency_service=='Y')
                    {
                        $color = '#ff6600';
                    }
                    else
                    {

                        $color =$work_order_data->work_order_status->color_code;
                        
                    }
                    

        ?>  
                    {
                      title          : '<?=$work_order_data->task_title?>(<?=$user?>)',
                      start          : '<?=$work_order_data->start_date?>',
                      end            : '<?=$work_order_data->end_date?>',
                      backgroundColor: '<?=$color?>', //red
                      borderColor    : '<?=$color?>', //red
                      groupId        : '<?=$work_order_data->id?>',
                      id             : '',
                      allDay         : false,
                      
                      description: '{__("calendar_module.task_title")}} : <?=@$work_order_data->task_title?><br>{{__("calendar_module.propety_name")}} : <?=@$work_order_data->property->property_name?><br>{{__("calendar_module.service")}} : <?=@$work_order_data->service->service_name?><br>{{__("calendar_module.service_type")}} : <?=@$work_order_data->contract_services->service_type?><br>{{__("general_sentence.breadcrumbs.country")}} : <?=@$work_order_data->property->country->name?><br>{{__("general_sentence.breadcrumbs.state")}} : <?=@$work_order_data->property->state->name?><br>{{__("general_sentence.breadcrumbs.city")}} : <?=@$work_order_data->property->city->name?><br>{{__("calendar_module.task_start_date")}} : <?=@$work_order_data->start_date?>'
                    },


        <?php } ?>
      ],
      timeFormat: 'H(:mm)', // uppercase H for 24-hour clock、
      displayEventTime: false,
      eventRender: function(info) {
        var tooltip = new Tooltip(info.el, {
          title: info.event.extendedProps.description,
          placement: 'top',
          trigger: 'cick',
          html: true,
          container: 'body'
        });
      },

      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      eventDrop: function(info) {
    

   
    if (confirm("This will change the date which was actually entered while creqated. Are you still want to make this change?")) {
      onTaskChange(info.event.groupId,  info.event.start,  info.event.end, info.event.id );
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
      }    
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






function checKClickedDate(clicked_date){

    var workorder_list = '';
    var taskDate = '';
    var total = 0;

      if(new Date(clicked_date) >= new Date())
      {
        workorder_list +=  '<tr  class="col-12 text-right"><td colspan="9" style="margin:0; padding:0;" a><a class="btn btn-success" href="{{route('admin.work-order-management.list')}}"><p>{{__('general_sentence.create_task')}}</p></a></td></tr>'; 
      }
      workorder_list +=  '<tr><th><strong>{{__('calendar_module.property_name')}}</strong></th><th><strong>{{__('calendar_module.contract')}}</strong></th><th><strong>{{__('calendar_module.work_order_title')}}</strong></th><th><strong>{{__('calendar_module.service_provider')}}</strong></th><th><strong>{{__('calendar_module.service')}}</strong></th><th><strong>{{__('calendar_module.maintenance_type')}}</strong></th><th><strong>{{__('calendar_module.date')}}</strong></th><th><strong>{{__('calendar_module.status')}}</strong></th><th><strong>{{__('calendar_module.action')}}</strong></th></tr>';
          <?php foreach($work_order_list as $work_order_data){
                  $details_url = route('admin.work-order-management.show',@$work_order_data->id);
                  $complain_url = route('admin.complaints.create','workorder_id='.@$work_order_data->id);
          ?>
            workDate = '<?php echo date("Y-m-d", strtotime($work_order_data->start_date)); ?>';
            if(clicked_date==workDate){
                  total++;
                  workorder_list += '<tr><th><?php echo @$work_order_data->property->property_name;?> </th><th><?php echo @$work_order_data->contract->title. ' ('.@$work_order_data->contract->code.')'; ?></th><th><?php echo @$work_order_data->task_title; ?></th><th><?php echo @$work_order_data->service_provider->name; ?></th><th><?php echo @$work_order_data->service->service_name;?></th><th><?php echo @$work_order_data->contract_services->service_type;?></th><th><?php echo date("d/m/Y H:i a", strtotime(@$work_order_data->start_date)); ?></th><th><?php 

                  if(@$work_order_data->emergency_service=='Y') { echo '<div class="external-event ui-draggable ui-draggable-handle" style="position: relative; background-color: #ff6600 !important">'?>{{__("general_sentence.status_button.emergency")}}<?php '</div>';}

                  elseif(@$work_order_data->status==0) { echo '<div class="external-event bg-warning ui-draggable ui-draggable-handle" style="position: relative; background-color: #ffc107 !important">'?>{{__("general_sentence.status_button.pending")}}<?php '</div>';}  

                  elseif(@$work_order_data->status==1) {echo '<div class="external-event btn-secondary ui-draggable ui-draggable-handle" style="position: relative; background-color: #5a6268 !important">'?>{{__("general_sentence.status_button.overdue")}}<?php '</div>'; }

                  elseif(@$work_order_data->status==2) {echo '<div class="external-event bg-success ui-draggable ui-draggable-handle" style="position: relative; background-color: #3ea846 !important">'?>{{__("general_sentence.status_button.completed")}}<?php '</div>'; }

                  elseif(@$work_order_data->status==4){ echo '<div class="external-event bg-danger ui-draggable ui-draggable-handle" style="position: relative; background-color: #dc3d45!important">&nbsp; &nbsp; {{__("general_sentence.status_button.warning")}}</div>'  ?></th><th><a target="_blank" href="<?php echo $complain_url;?>" title="{{__("general_sentence.status_button.complain")}}">{{__("general_sentence.status_button.complain")}}</a> &nbsp &nbsp <?php }?></th><th><?php 

                  if(@$work_order_data->status==2){?><a target="_blank" href="<?php echo @$details_url;?>"  title="{{__("general_sentence.status_button.rating_and_view")}}">&nbsp; &nbsp; {{__("general_sentence.status_button.rating_and_view")}}</a><?php } 

                  else{?><a target="_blank" href="<?php echo @$details_url;?>"  title="{{__("general_sentence.status_button.view")}}"> &nbsp; &nbsp; {{__("general_sentence.status_button.view")}}</a><?php } ?></th></tr>'
                  }
         <?php  } ?>

             
         if(total==0)
         {
            workorder_list += '<tr><th colspan="9"><span><strong style="color:red; text-align: justify;">{{__("general_sentence.no_data_found")}}</strong></span></th></tr>';
         }
        //var coverted_date = new Date(clicked_date).format("m/d/Y");  

        var coverted_date = moment(clicked_date, 'YYYY/MM/DD'); 

        <?php if($request->un_assigned=='' || $request->emergency_service=='')
        {?>
          var day_title = '<strong>{{__('calendar_module.work_order_list_for')}}  ' +moment(coverted_date).format("DD/MM/YYYY")+'</strong>'; 
       <?php  }
        else
        {?>
          var day_title = '<strong><span style="color:Red">{{__('calendar_module.filter_labels.un_assigned')}}</span> {{__('calendar_module.work_order_list_for')}} ' +moment(coverted_date).format("DD/MM/YYYY")+'</strong>';
        <?php }?>
        $(".day-work-list").html(day_title);
        $("#task_labour_list_management_table").html(workorder_list);
        $('#showWorkOrderListModal').modal('show');
  }

function onServiceChange(service_id){
  
     $.ajax({
       
        url: "{{route('admin.calendar.getData')}}",
        type:'post',
        dataType: "json",
        data:{service_id:service_id,_token:"{{ csrf_token() }}"}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.sqlProperty);
             console.log(response.sqlCity);
             var stringifiedProperty = JSON.stringify(response.sqlProperty);
             var propertyData = JSON.parse(stringifiedProperty);
                 var property_list= '<option value="'+propertyData.property.id+'">'+ propertyData.property.property_name +'</option>';
                $("#property_id").html(property_list);

             var stringifiedCity = JSON.stringify(response.sqlCity);
             var cityData = JSON.parse(stringifiedCity);
              var city_list = '<option value="'+cityData.id+'">'+ cityData.name +'</option>';
                $("#city_id").html(city_list);

             var stringifiedState = JSON.stringify(response.sqlState);
             var stateData = JSON.parse(stringifiedState);
              var state_list = '<option value="'+stateData.id+'">'+ stateData.name +'</option>';
                $("#state_id").html(state_list);
                
             var stringifiedCountry = JSON.stringify(response.sqlCountry);
             var countryData = JSON.parse(stringifiedCountry);
              var country_list = '<option value="'+countryData.id+'">'+ countryData.name +'</option>';
                $("#country_id").html(country_list);      

              // *******Changing calendar start date and end date as per the service, alloted by the sub-admin********//

              $('#date_range').daterangepicker({
                 minDate: new Date(propertyData.service_start_date),
                 maxDate: new Date(propertyData.service_end_date),
                 startDate: new Date(propertyData.service_start_date),
                 endDate: new Date(propertyData.service_end_date),
              })

              // *******Changing calendar start date and end date as per the service, alloted by the sub-admin********//
            }
        });
    }

   


function onTaskChange(work_order_id, start_date, end_date, contract_service_date_id){

//alert(work_order_id);
  let modified_start_date = JSON.stringify(start_date);
  modified_start_date = modified_start_date.slice(1,11);

  console.log(modified_start_date);

  let modified_end_date = JSON.stringify(end_date)
  modified_end_date = modified_end_date.slice(1,11)

     $.ajax({
       
        url: "{{route('admin.calendar.updateTask')}}",
        type:'post',
        dataType: "json",
        data:{work_order_id:work_order_id,modified_start_date:modified_start_date,modified_end_date:modified_end_date,contract_service_date_id:contract_service_date_id,_token:"{{ csrf_token() }}"}
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

//Date range picker

function getContractList(){

 var property_id =  $('#property_id').val();
 var test = $('.search-choice-close').val();

   $.ajax({
     
      url: "{{route('admin.calendar.getPropertyContractList')}}",
      type:'post',
      dataType: "json",
      data:{property_id:property_id,_token:"{{ csrf_token() }}"}
      }).done(function(response) {
         
         console.log(response.status);
          if(response.status){
           
          var stringified = JSON.stringify(response.allProperties);
          var contractData = JSON.parse(stringified);
          console.log(contractData);
           var contract_list = '';
           $.each(contractData,function(index, contractValue){
                  contract_list += '<option value="'+contractValue.id+'">'+ contractValue.title +'</option>';
           });
           console.log(contract_list);
              $("#contract_list").html(contract_list);
              $("#contract_list").trigger("chosen:updated");
          }

        else
          {
              var workorder_list = '';
              $("#contract_list").html(workorder_list);
              $("#contract_list").trigger("chosen:updated");
              getTaskLIst();
          }
      });
}
    
function getWorkOderList(){

 var contract_list =  $('#contract_list').val();
    
   $.ajax({
     
      url: "{{route('admin.calendar.getContractWorkOrderLIst')}}",
      type:'post',
      dataType: "json",
      data:{contract_list:contract_list,_token:"{{ csrf_token() }}"}
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


function getServiceProviderLIst(){

 var work_order_id =  $('#work_order_id').val();


      $.ajax({
   
      url: "{{route('admin.calendar.getServiceProviderList')}}",
      type:'post',
      dataType: "json",
      data:{work_order_id:work_order_id,_token:"{{ csrf_token() }}"}
      }).done(function(response) {
         
         
          if(response.status){       
           var stringified = JSON.stringify(response.allServiceProvider);
           var serviceProviderdata = JSON.parse(stringified);

           var stringifiedService = JSON.stringify(response.allService);
           var serviceData = JSON.parse(stringifiedService);

           var stringifiedMaintanence = JSON.stringify(response.allContractServices);
           var maintanenceData = JSON.parse(stringifiedMaintanence);

           var service_provider_list = '';
           var service_list = '';
           var maintenance_type_list = '';
           //$('#task_id').val('').multiselect('refresh');
           console.log(serviceProviderdata);
           $.each(serviceProviderdata,function(index, serviceProviderValue){
                  service_provider_list += '<option value="'+serviceProviderValue.user_details.id+'">'+ serviceProviderValue.user_details.name +'</option>';
           });

           $.each(serviceData,function(index, serviceVal){
                  service_list += '<option value="'+serviceVal.service.id+'">'+ serviceVal.service.service_name +'</option>';
           });

           $.each(maintanenceData,function(index, maintanenceVal){
                  maintenance_type_list += '<option value="'+maintanenceVal.contract_services.id+'">'+ maintanenceVal.contract_services.service_type +'</option>';
           });

              $("#service_provider_id").html(service_provider_list);
              $("#service_type").html(service_list);
              $("#maintenance_type").html(maintenance_type_list);

              $("#service_provider_id").trigger("chosen:updated");
              $("#service_type").trigger("chosen:updated");
              $("#maintenance_type").trigger("chosen:updated");
              
          }

          else
          {
              var service_provider_list = '';
              var service_list = '';
              var maintenance_type_list = '';
              $("#service_provider_id").html(service_provider_list);
              $("#service_type").html(service_list);
              $("#maintenance_type").html(maintenance_type_list);

              $("#task_id").trigger("chosen:updated");
              $("#service_type").trigger("chosen:updated");
              $("#maintenance_type").trigger("chosen:updated");
          }
          
      });
  
 
}

$(document).ready(function(){
    $('[rel=tooltip]').tooltip({ trigger: "click" });
});

</script>
@if(Session::has('welcome_msg'))        
<script>
$(function() {
$('#addTaskModal').modal('show');
});
</script>
@endif

<script type="text/javascript" src="{{asset('js/admin/service_management/list.js')}}"></script>
<script type="text/javascript">
  $("button.btn-filter-drop").click(function(){
  $("#filter_calendar").toggleClass('show');
});
</script>
@endpush


