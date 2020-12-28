
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
    }
});




$('#planned_maintenance_property_id').on('change',function(){
    if(this.value){
        $('#planned_maintenance_contract_id').val('').trigger('change');;
    }
});
$('#planned_maintenance_id').on('change',function(){
    
    if(this.value){
        $('#planned_maintenance_property_id').val('').trigger('change');;
    }
});

$("#planned_maintenance_report_form").validate({
    ignore:[],
    rules: {
        property_id:{
            required: function(){
                
                return $('#planned_maintenance_contract_id').val()=='';
            }
        },
        contract_id:{
            required: function(){
            return $('#planned_maintenance_property_id').val()=='';
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
                return $('#planned_maintenance_from_date').val();
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
    }
});