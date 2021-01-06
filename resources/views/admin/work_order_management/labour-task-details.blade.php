@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Task Details</h1>
                <?php //dd($task_data);?>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                  <li class="breadcrumb-item "><a href="{{route('admin.work-order-management.labourTaskList', $task_data->task->work_order_id)}}">Task List</a></li>
                  <li class="breadcrumb-item active">Task Labour List</li>
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
                    <td >Task Title</td>
                    <td >{{$task_data->task->task_title}}</td>
                  </tr>
                 
                  <tr>
                    <td >Work Order Title</td>
                    <td >{{$task_data->task->work_order->task_title}}</td>
                  </tr>
                  <tr>
                    <td >Property Name</td>
                    <td >{{$task_data->task->property->property_name}}</td>
                  </tr>
                  <tr>
                    <td >Service</td>
                    <td >{{$task_data->service->service_name}}</td>
                  </tr>
                  <tr>
                    <td >Task Finish Date And Time (Assigned)</td>
                    <td >{{ Carbon\Carbon::parse($task_data->task_date)->format('d/m/Y H:i a') }}</td>
                  </tr>
                  <tr>
                    <td >Slot</td>
                    <td >

                      @if(@$task_data->work_order_slot->daily_slot == 1)
                          First Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 2)
                          Second Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 3)
                          Third Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 4)
                          Fourth Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 5)
                          Fifth Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 6)
                          Sixth Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 7)
                          Seventh Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 8)
                          Eighth Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 9)
                          Nineth Slot
                      @elseif(@$task_data->work_order_slot->daily_slot == 10)
                          Tenth Slot  
                      @else
                          No Slot                  
                      @endif

                    </td>
                  </tr>
                  <tr>
                    <td >Task Mode</td>
                    <td >@if($task_data->reschedule_task_details_id>0)Rescheduled @else Normal @endif
                  </tr>
                  <tr>
                    <td >Country</td>
                    <td >{{$task_data->task->property->country->name}}</td>
                  </tr>
                  <tr>
                    <td >State</td>
                    <td >{{$task_data->task->property->state->name}}</td>
                  </tr>
                  <tr>
                    <td >City</td>
                    <td >{{$task_data->task->property->city->name}}</td>
                  </tr>
                  
                  <tr>
                    <td>Task Details</td>
                    <td >{!! $task_data->task->task_desc!!}</td>
                  </tr>

                  <tr>
                    <td>Task Feedback</td>
                    <td >{!! $task_data->user_feedback!!}</td>
                  </tr>

                  <tr>
                    <td>Feedback Files</td>
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
                    <td >Feedback Date And Time</td>
                    <td >{{ Carbon\Carbon::parse($task_data->task_finish_date_time)->format('d/m/Y H:i a') }}</td>
                  </tr>

                  <tr>
                    <td>Feedback on Time</td>
                    <td >@if($task_data->late_feedback=="N") No @else Yes @endif</td>
                  </tr>
                  
                 
                  <tr>
                    <td>Status</td>
                    <td>
                      @php
                        if($task_data->status=='0'){
                            $button = 'warning';
                            $status = 'Pending';
                          }
                          
                        else if($task_data->status=='1'){
                            $button = 'secondary';
                            $status = 'Over Due';

                          }
                          
                        else if($task_data->status=='2'){

                            $button = 'success';
                            $status = 'Completed';
                          }
                        else if($task_data->status=='3'){

                            $button = 'info';
                            $status = 'Requested for Reschedule';
                          }
                        else if($task_data->status=='4'){

                            $button = 'danger';
                            $status = 'Completed with Warning';
                          }  
                        @endphp  
                        
                      <button role="button" class="btn btn-{{$button}}">{{$status}}</button>
                      
                    </td>
                  </tr>
                  @if($task_data->status=='2' || $task_data->status=='4')
                    <tr>
                      <td>Rating</td>
                      <td>
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
                            
                            <div class='success-box' style="display: none;">
                              <div class='clearfix'></div>
                                <img alt='tick image' width='32' src='data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MjYuNjY3IDQyNi42NjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQyNi42NjcgNDI2LjY2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxwYXRoIHN0eWxlPSJmaWxsOiM2QUMyNTk7IiBkPSJNMjEzLjMzMywwQzk1LjUxOCwwLDAsOTUuNTE0LDAsMjEzLjMzM3M5NS41MTgsMjEzLjMzMywyMTMuMzMzLDIxMy4zMzMgIGMxMTcuODI4LDAsMjEzLjMzMy05NS41MTQsMjEzLjMzMy0yMTMuMzMzUzMzMS4xNTcsMCwyMTMuMzMzLDB6IE0xNzQuMTk5LDMyMi45MThsLTkzLjkzNS05My45MzFsMzEuMzA5LTMxLjMwOWw2Mi42MjYsNjIuNjIyICBsMTQwLjg5NC0xNDAuODk4bDMxLjMwOSwzMS4zMDlMMTc0LjE5OSwzMjIuOTE4eiIvPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K'/>
                              <div class='text-message'></div>
                              <div class='clearfix'></div>
                            </div>
                          </section>
                      </td>
                    </tr>
                  @endif
                 </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="2"><a class="btn btn-primary" href="{{route('admin.work-order-management.labourTaskList', $task_data->task->work_order_id)}}"><i class="fas fa-backward"></i>&nbsp;Back</a></td>
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
    $(document).ready(function(){
    
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
        var task_details_id = document.getElementById('task_details_id').value;
          $.ajax({
          url: "{{route('admin.work-order-management.labourTaskRating')}}",
          type:'post',
          dataType: "json",
          data:{task_details_id:task_details_id,ratingValue:ratingValue,_token:"{{ csrf_token() }}"}
          }).done(function(response) {
             
             console.log(response.status);
              if(response.status=='false'){
               location.reload();
              }
              else
              { 
                var msg = "";
                if (ratingValue > 1) {
                    msg = "Thanks! You rated this Labour effort with " + ratingValue + " star.";
                }
                else {
                    msg = "hanks! You rated this Labour effort with " + ratingValue + " stars.";
                }

                responseMessage(msg);
              }
          });
      }
    }); 
  } 
});


function responseMessage(msg) {
  
  $('#stars li').unbind('mouseover');
  $("#stars li").off('click'); 
  $('.success-box').fadeIn(200).show();  
  $('.success-box div.text-message').html("<span>" + msg + "</span>");
  setTimeout(function() { 
       $('.success-box').fadeOut(); 
   }, 5000);
}
</script>

<script type="text/javascript" src="{{asset('js/admin/work_order_management/task-labour-list.js')}}"></script>
@endpush


