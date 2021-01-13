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
                             <label for="report_on">Report On <span class="error">*</span></label>
                              <select class="form-control " id="report_on" name="report_on" style="width: 100%;">
                                
                                <option value="work_order">Work Orders</option>
                                <option value="maintenance_schedule">Maintenance Schedule</option>
                              </select>
                          </div>
                          <div class="form-group required">
                             <label for="service_status">Service Status <span class="error">*</span></label>
                              <select class="form-control " id="service_status" name="service_status" style="width: 100%;">
                                <option value="all">All</option>
                                <option value="completed">Completed</option>
                                <option value="due">Due</option>
                               
                              </select>
                          </div>
                          <div class="form-group required">
                               <label for="contract_id">Contract </label>
                                <select class="form-control contract" id="contract_id" name="contract_id" style="width: 100%;">
                                  <option value="">Select Contract</option>
                                  <option value="all" selected>All Contract</option>
                                  @forelse($contracts as $contract)
                                  <option value="{{$contract->id}}">{{$contract->code}}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                            <div style="margin-top: -1rem;">OR</div>
                            <div class="form-group required">
                               <label for="property_id">Property </label>
                                <select class="form-control property" id="property_id" name="property_id" style="width: 100%;">
                                  <option value="">Select Property</option>
                                  <option value="all" selected>All Property</option>
                                  @forelse($properties as $property)
                                  <option value="{{$property->id}}">{{$property->code}}</option>
                                  @empty
                                  @endforelse
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
                            <div class="mb-3">
                              <label >Download As &nbsp;&nbsp;</label>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="excel" checked class="form-check-input" name="output_format">Excel
                                </label>
                              </div>
                              <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="pdf" class="form-check-input" name="output_format">PDF
                                </label>
                              </div>
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
$('.contract').select2({
    theme: 'bootstrap4',
    placeholder:'Select Contract',
    "language": {
        "noResults": function(){
            return "No Contract Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});
$('.property').select2({
    theme: 'bootstrap4',
    placeholder:'Select Property',
    "language": {
        "noResults": function(){
            return "No Property Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$('#from_date').datepicker({
    dateFormat:'dd/mm/yy'
});
$('#to_date').datepicker({
    dateFormat:'dd/mm/yy'
});




$('#property_id').on('change',function(){
    if(this.value){
        $('#contract_id').val('').trigger('change');;
    }
});
$('#contract_id').on('change',function(){
    
    if(this.value){
        $('#property_id').val('').trigger('change');;
    }
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
            toDateShouldGreatherFromDate:function(){
                return $('#to_date').val();
            }
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
        $.LoadingOverlay("hide");
        form.reset();

    }
});


</script>
@endpush
