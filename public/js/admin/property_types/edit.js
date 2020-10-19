


$("#admin_property_type_edit_form").validate({
    ignore: "",
    rules: {
        en_type_name: {
            required: true,
            minlength: 3,
            maxlength: 50,
            remote: {
              url: $('#ajax_check_type_name_unique_url').val(),
              type: "post",
                data: {
                  type_name: function() {
                    return $("#en_type_name" ).val();
                  },
                  'locale':'en',
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            }
        },
        en_description: {
            required: true,
            minlength: 3,
            maxlength: 255,
        },
        ar_type_name: {
            required: true,
            minlength: 3,
            maxlength: 50,
            remote: {
              url: $('#ajax_check_type_name_unique_url').val(),
              type: "post",
                data: {
                  type_name: function() {
                    return $("#ar_type_name" ).val();
                  },
                  'locale':'ar',
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            }
        },
        ar_description: {
            required: true,
            minlength: 3,
            maxlength: 255,
        },
    },
    messages: {
        en_type_name: {
            required:  "Type name is required",
            minlength: "Type name should have 3 characters",
            maxlength: "Type name should not be more then 50 characters",
            remote:"Type name alredy exist. Enter different name",
        },
        en_description: {
            required:  "Description is required",
            minlength: "Description should have 3 characters",
            maxlength: "Description should not more then 255 characters"
        },
        ar_type_name: {
            required:  "Type name is required",
            minlength: "Type name should have 3 characters",
            maxlength: "Type name should not be more then 50 characters",
            remote:"Type name alredy exist. Enter different name",
        },
        ar_description: {
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
        $.LoadingOverlay("show");
        form.submit();
    }
});




