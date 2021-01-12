@extends('admin.layouts.after-login-layout')


@section('unique-content')

    <div class="content-wrapper" style="min-height: 1200.88px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>{{__('emergency_service_manage_module.module_title')}}</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{__('general_sentence.breadcrumbs.dashboard')}}</a></li>
                  <li class="breadcrumb-item active">{{__('general_sentence.breadcrumbs.emergency_service')}}</li>
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
                                    <div><span>{{__('emergency_service_manage_module.emergency_service_list')}}</span></div>
                                    @if(auth()->guard('admin')->user()->hasAllPermission(['work-order-create']))
                                      <div>
                                        <a class="btn btn-success" href="{{route('admin.emergency-service-management.emergencyServiceCreate')}}">
                                         {{__('emergency_service_manage_module.create_emergency_service')}} 
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
                                                  <option value="">--Filter By Status--</option>
                                                  <option value="0">Pending</option>
                                                  <option value="1">Overdue</option>
                                                  <option value="2">Completed</option>
                                                  
                                              </select>

                                           
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input class="form-control" type="text" name="contract_duration" id="contract_duration" placeholder="Search By Date">

                                                    <input type="hidden" name="daterange" id="daterange" placeholder="Search By Date">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                
                                <table class="table table-bordered" id="work_order_management_table">
                                    <thead>
                                        <tr>
                                            <th>{{__('emergency_service_manage_module.labels.id')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.contract_id')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.title')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.type')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.property_name')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.service')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.address')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.service_start_date')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.assigned')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.completed')}}</th>
                                            <th>{{__('emergency_service_manage_module.labels.status')}}</th>
                                            
                                            <th>{{__('emergency_service_manage_module.labels.action')}}</th>
                                        </tr>
                                    </thead>
                                </table>
                                <input type="hidden" id="work_order_data_url" value="{{route('admin.emergency-service-management.list')}}">
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
<script type="text/javascript" src="{{asset('js/admin/emergency_service_management/list.js')}}"></script>
@endpush


