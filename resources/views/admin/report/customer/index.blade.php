@extends('admin.layouts.after-login-layout')
@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Report Management</h1>
          </div>
          <div class="col-sm-6">

          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <!-- SELECT2 EXAMPLE -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Generate Report</h3>
              </div>
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
                      <form method="post" id="report_form">
                        @csrf
                        <div>
                          <div class="form-group required">
                             <label for="service_status">Service Status <span class="error">*</span></label>
                              <select class="form-control " id="service_status" name="service_status" style="width: 100%;">
                                <option value="all">All</option>
                                <option value="completed">Completed Services</option>
                                <option value="due">Due Services</option>
                              </select>
                          </div>
                          <div class="form-group required">
                             <label for="report_mode">Select report mode <span class="error">*</span></label>
                              <select class="form-control " id="report_mode" name="report_mode" style="width: 100%;">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                              </select>
                          </div>
                          <div class=" form-group required">
                           <label for="from_date">Date (From) <span class="error">*</span></label>
                           <input type="text" readonly="readonly" autocomplete="off" id="from_date" class="form-control" name="from_date">
                          </div>
              
                          <div class="form-group required">
                             <label for="to_date">Date (To) <span class="error">*</span></label>
                             <input type="text"  readonly="readonly" autocomplete="off" class="form-control" id="to_date" name="to_date">
                          </div>

                        </div>
                        <div>
                           
                          <button type="submit" class="btn btn-success">Generate Report</button> 
                        </div>
                      </form>
                    </div>
                  </div>
              </div>
            </div>
          </div>
      </div>
    </section>
    
</div>
@endsection 
@push('custom-scripts')
<script type="text/javascript">


$('#from_date').datepicker({
    dateFormat:'dd/mm/yy'
});
$('#to_date').datepicker({
    dateFormat:'dd/mm/yy'
});


$("#report_form").validate({
    ignore:[],
    rules: {
        
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10,
            toDateShouldBeGreatherThanFromDate:true 
        },
    },
    messages: {

        from_date:{
            required:  "Enter from date in dd/mm/yyy format",
        },
        to_date:{
            required:  "Enter to date in dd/mm/yyy format",
            toDateShouldBeGreatherThanFromDate : "TO date should be greater than from date"
        },

    },
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        error.insertAfter(element);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');    
    },
    submitHandler: function(form) {
        $.LoadingOverlay("show");
        form.submit();
    }
});


</script>
@endpush
