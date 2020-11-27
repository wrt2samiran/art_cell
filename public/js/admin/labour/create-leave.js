
$("#labour_leave_create_form").validate({
    rules: {
        labour_id:{
            required: true, 
        },
        date_range:{
            required: true,
    },
    messages: {
        labour_id: {
            required:  "First name is required",
        },
        date_range: {
            required:  "Last name is required",
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



