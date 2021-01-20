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
                  <li class="breadcrumb-item active">{{__('work_order_module.work_order')}}</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between" >
                                    <div><span>{{__('work_order_module.work_order_list')}}</span></div>
                                    @if(auth()->guard('admin')->user()->hasAllPermission(['work-order-create']))
                                      <div>
                                        <a class="btn btn-success" href="{{route('admin.work-order-management.workOrderCreate')}}">
                                         {{__('work_order_module.create_work_order')}}
                                        </a>
                                      </div>
                                    @endif
                                </div>
                            </div>

                            <!-- /.card-header -->
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

                                <div class="filter-area ">
                                    <div class="row">
                                        <div class="col-md-4" id="status-filter-container1">
                                            <select id='status' name="status" class="form-control status-filter" style="width: 200px">
                                                  <option value="">--{{__('work_order_module.filter_labels.filter_by_status')}}--</option>
                                                  <option value="0">Pending</option>
                                                  <option value="1">Overdue</option>
                                                  <option value="2">Completed</option>
                                                  <option value="emergency">Emergency</option>
                                                  
                                              </select>

                                           
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input class="form-control" type="text" name="contract_duration" id="contract_duration" placeholder="{{__('work_order_module.filter_labels.filter_by_date')}}">

                                                    <input type="hidden" name="daterange" id="daterange" placeholder="Search By Date">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                
                                <table class="table table-bordered" id="work_order_management_table">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.contract_id')}}</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.title')}}</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.type')}}</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.property_name')}}</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.service')}}</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.address')}}</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.service_start_date')}}</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.assigned')}}</th>
                                            <th>{{__('work_order_module.work_order_task_column_name.completed')}}</th>
                                            <th>{{__('work_order_module.status')}}</th>
                                            
                                            <th>{{__('work_order_module.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <input type="hidden" id="work_order_data_url" value="{{route('admin.work-order-management.list')}}">
                            </div>
                        </div>
                    </div>
                </div>

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
                                
                                <form  id="service_provider_reschedule" action="{{route('admin.work-order-management.poPmTaskReviewRating')}}" method="post" enctype="multipart/form-data">
                                  @csrf
                                    <input type="hidden" name="work_order_id" id="work_order_id">
                                    <div>  
                                      <div class="form-group">
                                        <div class="form-group required">
                                          <label for="task_description">Review</label>
                                          <textarea class="form-control" name="work_order_review" id="work_order_review">{{old('work_order_review')}}</textarea>
                                           @if($errors->has('work_order_review'))
                                              <span class="text-danger">{{$errors->first('work_order_review')}}</span>
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
                                                    <li class='star'  title='@if(array_key_exists($star, $starTitleArray)){{ $starTitleArray[$star] }} @endif' data-value='{{$star}}'>
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
  
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('custom-scripts')

<script type="text/javascript">
    function reviewRating(work_order_id)
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
            $('#work_order_id').val(work_order_id);
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

</script>
<script type="text/javascript" src="{{asset('js/admin/work_order_management/list.js')}}"></script>
@endpush


