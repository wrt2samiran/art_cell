    $("#admin_roles_create_form").validate({
        rules: {
            role_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            role_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
           
        },
        messages: {
            role_name: {
                required:  "Role name is required",
                minlength: "Role name should have 3 characters",
                maxlength: "Role name should not be more then 50 characters"
            },
            role_description: {
                required:  "Role description is required",
                minlength: "Role description should have 3 characters",
                maxlength: " Role description should not more then 255 characters"
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
            form.submit();
        }
    });