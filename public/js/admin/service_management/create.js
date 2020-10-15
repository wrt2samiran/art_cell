    $("#admin_labour_task_add_form").validate({
        rules: {

            
            job_title: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            
            service_id: {
                required: true,
            },
            property_id: {
                required: true,
            },
            country_id: {
                required: true,
            },
            state_id: {
                required: true,
            },           
            city_id: {
                required: true,
            },
            user_id: {
                required: true,
            },


        },
        messages: {
            job_title: {
                required:  "Job title is required",
                minlength: "Job title should have 3 characters",
                maxlength: "Job title should not be more then 50 characters"
            },
            service_id: {
                required:  "Please select service",
            },
            property_id: {
                required:  "Please select property",
            },
            country_id: {
                required:  "Please select country",
            },
            state_id: {
                required:  "Please select state",
            },
            city_id: {
                required:  "Please select city",
            },
            user_id: {
                required:  "Please select user",
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

    
    