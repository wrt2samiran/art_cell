$("#admin_property_type_edit_form").validate({
    rules: {
        type_name: {
            required: true,
            minlength: 3,
            maxlength: 50,
            remote: {
              url: $('#ajax_check_type_name_unique_url').val(),
              type: "post",
                data: {
                  type_name: function() {
                    return $("#type_name" ).val();
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
        type_name: {
            required:  "Type name is required",
            minlength: "Type name should have 3 characters",
            maxlength: "Type name should not be more then 50 characters",
            remote:"Type name alredy exist. Enter different name",
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




