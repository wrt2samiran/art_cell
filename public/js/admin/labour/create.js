
$("#labour_create_form").validate({
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
            required: true,
            minlength: 6,
            maxlength: 100,  
        },
        phone:{
            required: true,
            minlength: 8,
            maxlength: 20,
            number:true  
        },

        country_id:{
            required: true,
        },

        state_id:{
            required: true,
        },

        city_id:{
            required: true,
        },
        skills:{
            required: true,
        },
        
    },
    messages: {
        first_name: {
            required:  "First name is required",
            minlength: "First name should have 2 characters",
            maxlength: "First name should not be more then 100 characters",
        },
        last_name: {
            required:  "Last name is required",
            minlength: "Last name should have 2 characters",
            maxlength: "Last name should not be more then 100 characters",
        },
        email: {
            required:  "Email is required",
            email: "Please enter valid email address",
            maxlength: "Email not be more then 100 characters",
            remote:"Email alredy exist. Try with different email",
        },
        password: {
            required:  "Password is required",
            minlength: "Password should have 6 characters",
            maxlength: "Password should not be more then 100 characters",
        },
        phone: {
            required:  "Phone/Contact number is required",
            minlength: "Phone/Contact number should have minimum 8 characters",
            maxlength: "Phone/Contact number should not be more then 20 characters",
            number:"Only number allowed"
        },
        country_id:{
            required: "Please select Country",
        },

        state_id:{
            required: "Please select State",
        },

        city_id:{
            required: "Please select City",
        },
        skills: {
            required:  "Please select Labour",
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
    submitHandler: function(form) {
        $.LoadingOverlay("show");
        form.submit();
    }
});




