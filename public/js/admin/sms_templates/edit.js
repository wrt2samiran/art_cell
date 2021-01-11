$("#admin_sms_template_edit_form").validate({
    rules: {
        content: {
            required: true,
            minlength: 2,
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
        form.submit();
    }

});
