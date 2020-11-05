$("#admin_message_add_form").validate({
    rules: {
        name: {
            required: true,
            minlength: 3,
            maxlength: 50,
        },
        contract_id: {
            required: true,
        },
        user_id: {
            required: true,
        },
        description: {
            required: true,
            minlength: 3,
        },

    },
    messages: {
        name: {
            required:  "Message Title is required",
            minlength: "Message Title should have 3 characters",
            maxlength: "Message Title should not be more then 50 characters",
        },
        contract_id: {
            required:"Contract is required" ,
        },
        user_id: {
            required:"User is required" ,
        },
        description: {
            required:  "Description is required",
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




