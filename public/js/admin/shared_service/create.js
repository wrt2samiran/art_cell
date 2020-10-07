    $("#admin_shared_service_add_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
           
            number_of_days: {
                required: true,
            },
            
            price: {
                required: true,
            },
           
            extra_price_per_day: {
                required: true,
            },

            currency: {
                required: true,
            },
        },
        messages: {
            name: {
                required:  "Shared Service name is required",
                minlength: "Shared Service name should have 3 characters",
                maxlength: "Shared Service name should not be more then 255 characters"
            },
            number_of_days: {
                required:  "Number of Days is required",
            },
            price: {
                required:  "Price is required",
            },

            extra_price_per_day: {
                required:  "Extra Price/day is required",
            },
            currency: {
                required:  "Currency is required",
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
