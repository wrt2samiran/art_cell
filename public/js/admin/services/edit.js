$("#admin_service_edit_form").validate({
    rules: {
        service_name: {
            required: true,
            minlength: 3,
            maxlength: 50,
            remote: {
              url: $('#ajax_check_service_name_unique_url').val(),
              type: "post",
                data: {
                  service_name: function() {
                    return $("#service_name" ).val();
                  },
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            }
        },
        description: {
            required: true,
            minlength: 3,
            maxlength: 255,
        },

    },
    messages: {
        service_name: {
            required:  "Service name is required",
            minlength: "Service name should have 3 characters",
            maxlength: "Service name should not be more then 50 characters",
            remote:"Service name alredy exist. Enter different name",
        },
        description: {
            required:  "Description is required",
            minlength: "Description should have 3 characters",
            maxlength: "Description should not more then 255 characters"
        }
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