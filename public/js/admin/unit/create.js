
$("#unit_create_form").validate({
   ignore: "",
    rules: {
        en_unit_name:{
            required: true,
            minlength: 2,
            maxlength: 100, 
            remote: {
              url: $('#ajax_check_unit_name_unique_url').val(),
              type: "post",
                data: {
                  unit_name: function() {
                    return $("#en_unit_name" ).val();
                  },
                  'locale':'en',
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            } 
        },
        ar_unit_name:{
            required: true,
            minlength: 2,
            maxlength: 100,  
            remote: {
              url: $('#ajax_check_unit_name_unique_url').val(),
              type: "post",
                data: {
                  unit_name: function() {
                    return $("#ar_unit_name" ).val();
                  },
                  'locale':'ar',
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            } 
        }
    },
    messages: {
        en_unit_name: {
            remote:"Unit name alredy exist. Enter different name",
        },
        ar_unit_name: {
            remote:"Unit name alredy exist. Enter different name",
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



