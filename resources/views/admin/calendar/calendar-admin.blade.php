@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Calendar Management</h1>
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
              <div class="row justify-content-center">
                <div class="col-md-10 col-sm-12">
                  <?php //dd($work_order_list);?>
                    <div class="row">
                      <form  method="post" id="filter_calendar" action="{{route('admin.calendar.calendardata')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                          <input type="hidden" name="search" id="search" value="Search">
                          <div class="col-md-4 form-group" id="status-filter-container">
                              <select class="form-control status-filter"  name="property_id" id="property_id" onchange="getContractLIst(this.value)">
                                  
                                  @forelse($sqlProperty as $propertyData)
                                     <option value="{{$propertyData->id}}" @if($propertyData->id==@$request->property_id) selected @endif >{{$propertyData->property_name}} ({{$propertyData->code}})</option>
                                  @empty
                                  <option value="">No Contract Found</option>
                                  @endforelse
                              </select>
                          </div>
                          <div class="col-md-4 form-group" id="status-filter-container">
                              <select class="form-control status-filter"  name="contract_id" id="contract_id" onchange="getWorkOrderLIst(this.value)">
                                  
                                  @forelse($sqlContract as $contractData)
                                     <option value="{{$contractData->id}}" @if($contractData->id==@$request->contract_id) selected @endif >{{$contractData->title}} ({{$contractData->code}})</option>
                                  @empty
                                  <option value="">No Contract Found</option>
                                  @endforelse
                              </select>
                          </div>
                          <div class="col-md-4 form-group" id="status-filter-container">
                            <select class="form-control status-filter"  name="work_order_id" id="work_order_id">
                                    <option value="">Filter by Work Order</option>
                                    @forelse($work_order_list as $work_order_data)
                                       <option value="{{$work_order_data->id}}" @if($work_order_data->id==@$request->work_order_id) selected @endif >{{$work_order_data->task_title}}</option>
                                    @empty
                                    <option value="">No Work Order Found</option>
                                    @endforelse
                             </select>
                          </div>   
                        </div>
                        <div class="row">
                          <div class="col-md-4 form-group" id="status-filter-container">
                              <select class="form-control status-filter"  name="contract_status" id="contract_status">
                                  <option value="">Filter by Status</option>
                                     <option value="2" @if($request->contract_status==2) selected @endif>Completed</option>
                                     <option value="3" @if($request->contract_status==3) selected @endif>Pending </option>
                                     <option value="1" @if($request->contract_status==1) selected @endif>Overdue</option>
                             </select>
                          </div>
                          <div class="col-md-4 form-group" id="status-filter-container">
                              <select class="form-control service-type-filter"  name="contract_service" id="contract_service">
                                 <option value="">Filter by Service</option>
                                 @forelse($serviceList as $serviceData)
                                     <option value="{{$serviceData->id}}" @if($serviceData->id==@$request->contract_service) selected @endif >{{$serviceData->service_name}}</option>
                                  @empty
                                  <option value="">No Service Found</option>
                                  @endforelse
                             </select>
                          </div>
                          <div class="col-md-4" id="status-filter-container">
                             <button type="submit" class="btn btn-success disable-button">Search</button> 
                          </div>
                        </div> 
                      </form>
                    </div>
                </div>
                
                <section class="content">
                  <div class="container-fluid">
                    <div class="row">
                      
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
                      <div class="col-md-3">
                        <div class="sticky-top mb-3">
                          <div class="card" style="width: 125px">
                            <div class="card-header">
                              <h4 class="card-title">Color Details</h4>
                            </div>
                            <div class="card-body">
                              <!-- the events -->
                              <div id="external-events" style="width: 88px">
                                <div class="external-event bg-success">Completed</div>
                                <div class="external-event bg-warning">Pending</div>
                                <div class="external-event bg-danger">Overdue</div>
                              </div>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <!-- /.card -->
                         
                        </div>
                      </div>
                      <!-- /.col -->
                    </div>
                    <!-- /.row -->
                  </div><!-- /.container-fluid -->
                </section>


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
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      'themeSystem': 'bootstrap',
      //Random default events
      events    : [
       
       <?php foreach($work_order_list as $work_order_data){ 
               if(count($work_order_data->contract_service_dates)>0){

                    foreach ($work_order_data->contract_service_dates as $maintainWorkOrder) {
                     

                    $user = $work_order_data->userDetails->name;
                    if($work_order_data->status==1)
                    {
                      $color = '#dc3545';
                    }
                    else if($work_order_data->status==0)
                    {
                      $color = '#ffc107';
                    }
                    else
                    {
                      $color = '#28a745';
                    }

                ?>

                {
                  title          : '<?=$work_order_data->task_title?>(<?=$user?>)',
                  start          : '<?=$maintainWorkOrder->date?>',
                  end            : '<?=$maintainWorkOrder->date?>',
                  backgroundColor: '<?=$color?>', //red
                  borderColor    : '<?=$color?>', //red
                  groupId            : '<?=$work_order_data->id?>',
                  id             : '<?=$maintainWorkOrder->id?>',
                  allDay         : false,
                  
                  description: 'Task Title : <?=$work_order_data->task_title?><br>Property Name : <?=$work_order_data->property->property_name?><br>Service : <?=$work_order_data->service->service_name?><br>Service Type : <?=$work_order_data->contract_services->service_type?><br>Country : <?=$work_order_data->property->country->name?><br>State : <?=$work_order_data->property->state->name?><br>City : <?=$work_order_data->property->city->name?><br>Task Date : <?=$maintainWorkOrder->date?>'
                },
        <?php } } else{ 

                  $user = $work_order_data->userDetails->name;
                    if($work_order_data->status==1)
                    {
                      $color = '#dc3545';
                    }
                    else if($work_order_data->status==0)
                    {
                      $color = '#ffc107';
                    }
                    else
                    {
                      $color = '#28a745';
                    }
        ?>  
                    {
                      title          : '<?=$work_order_data->task_title?>(<?=$user?>)',
                      start          : '<?=$work_order_data->start_date?>',
                      end            : '<?=$work_order_data->end_date?>',
                      backgroundColor: '<?=$color?>', //red
                      borderColor    : '<?=$color?>', //red
                      groupId            : '<?=$work_order_data->id?>',
                      id             : '',
                      allDay         : false,
                      
                      description: 'Task Title : <?=$work_order_data->task_title?><br>Property Name : <?=$work_order_data->property->property_name?><br>Service : <?=$work_order_data->service->service_name?><br>Service Type : <?=$work_order_data->contract_services->service_type?><br>Country : <?=$work_order_data->property->country->name?><br>State : <?=$work_order_data->property->state->name?><br>City : <?=$work_order_data->property->city->name?><br>Task Start Date : <?=$work_order_data->start_date?>'
                    },


        <?php }  }?>
      ],
      timeFormat: 'H(:mm)', // uppercase H for 24-hour clock、
      displayEventTime: false,
      eventRender: function(info) {
        var tooltip = new Tooltip(info.el, {
          title: info.event.extendedProps.description,
          placement: 'top',
          trigger: 'hover',
          html: true,
          container: 'body'
        });
      },

      editable  : true,
      droppable : false, // this allows things to be dropped onto the calendar !!!
        
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




// $(document).on('click', 'td.fc-today,td.fc-future', function() {
//   <?php if(\Auth::guard('admin')->user()->role_id==4){ ?>
//       $('#addTaskModal').modal('show');
//   <?php } ?>

//  });



//Date range picker


function getContractLIst(property_id){
 $.ajax({
   
    url: "{{route('admin.calendar.getContractLIst')}}",
    type:'post',
    dataType: "json",
    data:{property_id:property_id,_token:"{{ csrf_token() }}"}
    }).done(function(response) {
       
       console.log(response.status);
        if(response.status){
         console.log(response.allContracts);
         var stringified = JSON.stringify(response.allContracts);
        var contractdata = JSON.parse(stringified);
         var contract_list = '<option value=""> Select Contract</option>';
         $.each(contractdata,function(index, contract_id){
                contract_list += '<option value="'+contract_id.id+'">'+ contract_id.title +'('+contract_id.code+')</option>';
         });
            $("#contract_id").html(contract_list);
        }
    });
}


function getWorkOrderLIst(contract_id){
 $.ajax({
   
    url: "{{route('admin.calendar.getWorkOrderLIst')}}",
    type:'post',
    dataType: "json",
    data:{contract_id:contract_id,_token:"{{ csrf_token() }}"}
    }).done(function(response) {
       
       console.log(response.status);
        if(response.status){
         console.log(response.allWorkOrders);
         var stringified = JSON.stringify(response.allWorkOrders);
        var workOrderData = JSON.parse(stringified);
         var work_order_list = '<option value=""> Select Contract</option>';
         $.each(workOrderData,function(index, work_order_id){
                work_order_list += '<option value="'+work_order_id.id+'">'+ work_order_id.task_title +'</option>';
         });
            $("#work_order_id").html(work_order_list);
        }
    });
}

setTimeout(function() {
        $('.alert-dismissable').fadeOut('fast');
    }, 5000); 
    
</script>
@if(Session::has('welcome_msg'))        
<script>
$(function() {
$('#addTaskModal').modal('show');
});
</script>
@endif

@endpush


