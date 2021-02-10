@extends('admin.dashboard.dashboard-layout')

@section('dashboard-content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>
                  {{'0'}}
                </h3>
                <p><strong>{{__('dashboard.number_of_customer')}}</strong></p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="{{route('admin.users.list')}}" class="small-box-footer">{{__('dashboard.more_info')}}<i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>
                  {{'0'}}
                </h3>

                <p><strong>{{__('dashboard.number_of_service_providers')}}</strong> </p>
              </div>
              <div class="icon">
                <i class="fa fa-user"></i>
              </div>
              <a href="{{route('admin.users.list')}}" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>
                  {{'0'}}
                </h3>
                <p><strong>{{__('dashboard.number_of_contracts')}} </strong></p>
              </div>
              <div class="icon">
                <i class="fas fa-handshake"></i>
              </div>
              <a href="" class="small-box-footer">{{__('dashboard.more_info')}} <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
       
      </div><!-- /.container-fluid -->
    </section>


  <!-- /.content-wrapper -->

@endsection

@push('custom-scripts')
<script type="text/javascript">
  
  /*complaint filter */

  $('#complaint_status').on('change',function(){
   filter_complaint();
  });
  $('#complaint_contract').on('change',function(){
   filter_complaint();
  });
  
  function filter_complaint(){

    $.LoadingOverlay("show");
    $.ajax({
      url: '{{route("admin.dashboard")}}',
      type: "POST",
      data:{
        complaint_filter: true,
        complaint_status_id: $('#complaint_status').val(),
        complaint_contract_id: $('#complaint_contract').val(),
        "_token": $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        
        $.LoadingOverlay("hide");

        $('#complaints_data').html(data.html);
        
      },
      error: function(jqXHR, textStatus, errorThrown) {
         $.LoadingOverlay("hide");
         var response=jqXHR.responseJSON;
         var status=jqXHR.status;
         if(status=='404'){
          toastr.error('Invalid URL', 'Error', {timeOut: 5000});
         }else{
           toastr.error('Internal server error.', 'Error', {timeOut: 5000});
         }
      }
    });


  }


 $('#complaint_contract').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Contract',
    "language": {
       "noResults": function(){
           return "No Contract";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
$('#complaint_status').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Status',
    "language": {
       "noResults": function(){
           return "No Status";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

  /*****************/

  /*Work Order filter */

  $('#work_order_contract').on('change',function(){
   filter_work_order();
  });
  $('#work_order_service').on('change',function(){
   filter_work_order();
  });
  function filter_work_order(){

    $.LoadingOverlay("show");
    $.ajax({
      url: '{{route("admin.dashboard")}}',
      type: "POST",
      data:{
        work_order_filter: true,
        work_order_contract_id: $('#work_order_contract').val(),
        work_order_service_id: $('#work_order_service').val(),
        "_token": $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        
        $.LoadingOverlay("hide");

        $('#work_order_data').html(data.html);
        
      },
      error: function(jqXHR, textStatus, errorThrown) {
         $.LoadingOverlay("hide");
         var response=jqXHR.responseJSON;
         var status=jqXHR.status;
         if(status=='404'){
          toastr.error('Invalid URL', 'Error', {timeOut: 5000});
         }else{
           toastr.error('Internal server error.', 'Error', {timeOut: 5000});
         }
      }
    });


  }


 $('#work_order_contract').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Contract',
    "language": {
       "noResults": function(){
           return "No Contract";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
$('#work_order_service').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Service',
    "language": {
       "noResults": function(){
           return "No Service";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
  /*****************/


  /*Task filter */

  $('#task_contract').on('change',function(){
   filter_task();
  });
  $('#task_work_order').on('change',function(){
   filter_task();
  });
  $('#task_property').on('change',function(){
   filter_task();
  });


  function filter_task(){

    $.LoadingOverlay("show");
    $.ajax({
      url: '{{route("admin.dashboard")}}',
      type: "POST",
      data:{
        task_filter: true,
        contract_id: $('#task_contract').val(),
        work_order_id: $('#task_work_order').val(),
        property_id: $('#task_property').val(),
        "_token": $('meta[name="csrf-token"]').attr('content')
      },
      success: function (data) {
        
        $.LoadingOverlay("hide");

        $('#task_data').html(data.html);
        
      },
      error: function(jqXHR, textStatus, errorThrown) {
         $.LoadingOverlay("hide");
         var response=jqXHR.responseJSON;
         var status=jqXHR.status;
         if(status=='404'){
          toastr.error('Invalid URL', 'Error', {timeOut: 5000});
         }else{
           toastr.error('Internal server error.', 'Error', {timeOut: 5000});
         }
      }
    });


  }


 $('#task_contract').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Contract',
    "language": {
       "noResults": function(){
           return "No Contract";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
$('#task_work_order').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Work Order',
    "language": {
       "noResults": function(){
           return "No Work Order";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});
$('#task_property').select2({
    theme: 'bootstrap4',
    placeholder:'Filter By Property',
    "language": {
       "noResults": function(){
           return "No Property";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


</script>
@endpush