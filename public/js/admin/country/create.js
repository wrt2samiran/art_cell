    $("#admin_country_add_form").validate({
        rules: {
            en_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
           ar_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },

            country_code: {
                required: true,
                minlength: 2,
                maxlength: 8,
            },
            
            dial_code: {
                required: true,
                minlength: 2,
                maxlength: 5,
            },
           
        },
        messages: {
            en_name: {
                required:  "Country name is required",
                minlength: "Country name should have 3 characters",
                maxlength: "Country name should not be more then 50 characters"
            },
            ar_name: {
                required:  "Country name is required",
                minlength: "Country name should have 3 characters",
                maxlength: "Country name should not be more then 50 characters"
            },
            country_code: {
                required:  "Country code is required",
                minlength: "Country code should have 2 digits",
                maxlength: " Country code should not more then 8 digits"
            },
            dial_code: {
                required:  "Country dial code is required",
                minlength: "Country dial code should have 2 digits",
                maxlength: " Country dial code should not more then 5 digits"
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

    