    $("#admin_spare_parts_add_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            manufacturer: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            unit_master_id: {
                required: true,
            },
            
            price: {
                required: true,
            },
            quantity_available:{
                required: true,
                number:true
            }
            // currency: {
            //     required: true,
            // },
        },
        messages: {
            name: {
                required:  "Spare Parts name is required",
                minlength: "Spare Parts name should have 3 characters",
                maxlength: "Spare Parts name should not be more then 255 characters"
            },
            manufacturer: {
                required:  "Spare Parts name is required",
                minlength: "Spare Parts name should have 3 characters",
                maxlength: "Spare Parts name should not be more then 255 characters"
            },
            unit_master_id: {
                required:  "Unit is required",
            },
            price: {
                required:  "Price is required",
            },
            quantity_available: {
                required:  "Enter available quantity",
            },
            // currency: {
            //     required:  "Currency is required",
            // },

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
