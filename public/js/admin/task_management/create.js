$("#admin_task_add_form").validate({
        rules: {

            
            
            contract_id: {
                required: true,
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
            labour_id: {
                required: true,
            },
            date_range: {
                required: true,
            },
            task_title: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            



        },
        messages: {
            
            contract_id: {
                required:  "Please select contract",
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
            labour_id: {
                required:  "Please select user",
            },
            date_range: {
                required:  "Please select date range",
            },
            task_title: {
                required:  "Task title is required",
                minlength: "Task title should have 3 characters",
                maxlength: "Task title should not be more then 50 characters"
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


$("#admin_labour_assign_form").validate({
        rules: {

            task_id: {
                required: true,
            },
            
            service_id: {
                required: true,
            },
            
            user_id: {
                required: true,
            },
            date_range: {
                required: true,
            },
            task_description: {
                required: true,
                minlength: 10,
                maxlength: 5000,
            },
            



        },
        messages: {
            
            task_id: {
                required:  "Please select task",
            },
            service_id: {
                required:  "Please select service",
            },
            
            user_id: {
                required:  "Please select user",
            },
            date_range: {
                required:  "Please select date range",
            },
            task_description: {
                required:  "Task description is required",
                minlength: "Task description should have 10 characters",
                maxlength: "Task description should not be more then 5000 characters"
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


    

    
    