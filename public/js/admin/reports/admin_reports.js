
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

$('#upcoming_weekly_maintenance_sp_or_labour_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select Service Provider/Labour',
    "language": {
        "noResults": function(){
            return "No Service Provider/Labour Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$('#upcoming_weekly_maintenance_service_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select Service',
    "language": {
        "noResults": function(){
            return "No Service Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});


$('.from_date').datepicker({
    dateFormat:'dd/mm/yy'
});
$('.to_date').datepicker({
    dateFormat:'dd/mm/yy'
});


$('#schedule_compliance_property_id').on('change',function(){
    if(this.value){
        $('#schedule_compliance_contract_id').val('').trigger('change');;
    }
});
$('#schedule_compliance_contract_id').on('change',function(){
    
    if(this.value){
        $('#schedule_compliance_property_id').val('').trigger('change');;
    }
});

$('#schedule_compliance_from_date').datepicker({
    dateFormat:'dd/mm/yy',
    maxDate: 0,
});
$('#schedule_compliance_to_date').datepicker({
    dateFormat:'dd/mm/yy',
    maxDate: 0,
});


$("#schedule_compliance_report_form").validate({
    ignore:[],
    rules: {
        property_id:{
            required: function(){
                
                return $('#schedule_compliance_contract_id').val()=='';
            }
        },
        contract_id:{
            required: function(){
            return $('#schedule_compliance_property_id').val()=='';
            } 
        },
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#schedule_compliance_from_date').val();
            }
        },
    },
    messages: {
        property_id: {
            required:  "Select Contract Or Property",
        },
        contract_id:{
            required:  "Select Contract Or Property",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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



$('#maintenance_backlog_from_date').datepicker({
    dateFormat:'dd/mm/yy',
    maxDate: 0,
});
$('#maintenance_backlog_to_date').datepicker({
    dateFormat:'dd/mm/yy',
    maxDate: 0,
});
$('#maintenance_backlog_sp_or_labour_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select Service Provider/Labour',
    "language": {
        "noResults": function(){
            return "No Service Provider/Labour Found";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$("#maintenance_backlog_report_form").validate({
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
                return $('#maintenance_backlog_from_date').val();
            }
        },
    },
    messages: {

        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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







$("#work_order_report_form").validate({
    ignore:[],
    rules: {
        work_order_status:{
            required: true
        },
        from_date:{
            required: true, 
            maxlength: 10,
        },
        to_date:{
            required: true,
            maxlength: 10, 
            toDateShouldGreatherFromDate:function(){
                return $('#work_order_from_date').val();
            }
        },
    },
    messages: {
        work_order_status:{
            required:  "Select Status",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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






$("#work_order_completed_per_month_report_form").validate({
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
                return $('#work_order_completed_per_month_from_date').val();
            }
        },
    },
    messages: {
        work_order_status:{
            required:  "Select Status",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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





$("#work_order_requested_vs_completed_report_form").validate({
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
                return $('#work_order_requested_vs_completed_from_date').val();
            }
        },
    },
    messages: {
        work_order_status:{
            required:  "Select Status",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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


$('#upcoming_schedule_maintenance_from_date').datepicker({
    dateFormat:'dd/mm/yy',
    minDate: 0,
});

$("#upcoming_schedule_maintenance_report_form").validate({
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
                return $('#upcoming_schedule_maintenance_from_date').val();
            }
        },
    },
    messages: {
        work_order_status:{
            required:  "Select Status",
        },
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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



$('#upcoming_weekly_maintenance_from_date').datepicker({
    dateFormat:'dd/mm/yy',
    minDate: 0,
});


$("#upcoming_weekly_maintenance_report_form").validate({
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
                return $('#upcoming_weekly_maintenance_from_date').val();
            }
        },
    },
    messages: {
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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



$("#contract_status_report_form").validate({
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
                return $('#contract_status_from_date').val();
            }
        },
    },
    messages: {
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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

$("#payment_report_form").validate({
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
                return $('#payment_report_from_date').val();
            }
        },
    },
    messages: {
        from_date:{
            required:  "Enter from date",
        },
        to_date:{
            required:  "Enter to date",
            toDateShouldGreatherFromDate:'To date should be greater than from date'
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