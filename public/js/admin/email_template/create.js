$("#admin_email_add_form").validate({
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
            ckrequired: true,
            minlength: 3,
        },

    },
    messages: {
        template_name: {
            required:  "Template name is required",
            minlength: "Template name should have 3 characters",
            maxlength: "Template name should not be more then 255 characters",
        },
        slug: {
            required: "Slug is required",
        },
        variable_name: {
            required:  "Variables Name is required",
            minlength: "Variables Name should have 3 characters",
            maxlength: "Variables Name should not be more then 255 characters",
        },
        content: {
            ckrequired:  "Description is required",
            minlength: "Description should have 3 characters",
        }
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




