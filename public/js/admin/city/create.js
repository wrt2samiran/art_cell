    $("#admin_state_add_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            country_id: {
                required: true,
            },           
           
        },
        messages: {
            name: {
                required:  "State name is required",
                minlength: "State name should have 3 characters",
                maxlength: "State name should not be more then 50 characters"
            },
            country_id: {
                required:  "State code is required",
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

    
    