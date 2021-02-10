    $("#admin_brand_edit_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
        },
        messages: {
            name: {
                required:  "Country name is required",
                minlength: "Country name should have 3 characters",
                maxlength: "Country name should not be more then 50 characters"
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

    