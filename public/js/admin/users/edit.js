
$("#admin_user_edit_form").validate({
    rules: {
        first_name:{
            required: true,
            minlength: 2,
            maxlength: 100,  
        },
        last_name:{
            required: true,
            minlength: 2,
            maxlength: 100,  
        },
        email: {
            required: true,
            email: true,
            maxlength: 100,
            remote: {
              url: $('#ajax_check_user_email_unique').val(),
              type: "post",
                data: {
                  email: function() {
                    return $("#email" ).val();
                  },
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            }
        },
        password:{
            minlength: 6,
            maxlength: 100,  
        },
        phone:{
            required: true,
            minlength: 8,
            maxlength: 20,
            number:true  
        },
        secondary_contact_number:{
            minlength: 8,
            maxlength: 20,
            number:true  
        },
        role_id:{
            required:true
        }
    },
    messages: {
        email: {
            remote:function(){
                return current_locale=='ar'?'البريد الالكتروني موجود بالفعل. حاول باستخدام بريد إلكتروني مختلف':'Email already exists. Try with a different email';
            },
        },
 
        role_id:{
            required:function(){
                return current_locale=='ar'?'حدد مجموعة':'Select a group';
            },
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


$('#role_id').select2({
  theme: 'bootstrap4',
  placeholder:translations.user_manage_module.placeholders.group,
  language: current_locale,
});