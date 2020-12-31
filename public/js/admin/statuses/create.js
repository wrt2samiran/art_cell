$("#admin_status_create_form").validate({
    ignore: "",
    rules: {
        en_status_name: {
            required: true,
            maxlength: 50,
            remote: {
              url: $('#ajax_check_status_name_unique_url').val(),
              type: "post",
                data: {
                  status_name: function() {
                    return $("#en_status_name" ).val();
                  },
                  status_for: function() {
                    return $("#status_for" ).val();
                  },
                  'locale':'en',
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            }
        },
        status_for: {
            required: true,
        },
        ar_status_name: {
            required: true,
            maxlength: 50,
            remote: {
              url: $('#ajax_check_status_name_unique_url').val(),
              type: "post",
                data: {
                  status_name: function() {
                    return $("#ar_status_name" ).val();
                  },
                  'locale':'ar',
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            }
        },
        color_code: {
            required: true,
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
        $.LoadingOverlay("show");
        form.submit();
    }
});


$('#status_for').select2({
  theme: 'bootstrap4',
  placeholder:'Status For'
});