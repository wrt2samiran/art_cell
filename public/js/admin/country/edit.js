    $("#admin_country_edit_form").validate({
        rules: {
            name: {
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
            name: {
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
        invalidHandler: function() {
            setTimeout(function() {
                $('.nav-tabs a small.text-danger').remove();
                var validatePane = $('.tab-content.tab-validate .tab-pane:has(.is-invalid)').each(function() {
                    var id = $(this).attr('id');
                    $('.nav-tabs').find('a[href^="#' + id + '"]').append(' <small class="text-danger">**</small>');
    
                });
            });            
        },
        submitHandler: function(form) {
            form.submit();
        }

    });

    