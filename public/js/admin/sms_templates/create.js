$("#admin_sms_template_create_form").validate({
    rules: {
        template_name: {
            required: true,
            minlength: 3,
            maxlength: 255,
        },
        slug: {
            required: true,
        },
        variable_name: {
            required: true,
            minlength: 3,
            maxlength: 255,
        },
        content: {
            required: true,
            minlength: 3,
        },
    },
    messages: {

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
