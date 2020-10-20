    $("#admin_country_edit_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            country_id: {
                required: true,
            },
            state_id: {
                required: true,
            },
            ar_name: {
                required: true,
            },           
           
        },
        messages: {
            name: {
                required:  "City name is required",
                minlength: "City name should have 3 characters",
                maxlength: "City name should not be more then 50 characters"
            },
            country_id: {
                required:  "Country name is required",
            },
            state_id: {
                required:  "State name is required",
            },
            ar_name: {
                required:  "City arabic name is required",
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

    
