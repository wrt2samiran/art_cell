    $("#admin_change_password_form").validate({
        rules: {
            current_password: {
                required: true,
            },
            new_password: {
                required: true,
                valid_password: true,
                maxlength:100   
            },
            confirm_password:{
                required: true,
                equalTo:"#new_password"
            },
        },
        messages: {
            current_password: {
                required:  "Enter current password", 
            },
            new_password: {
                required:  "Enter new password",
                maxlength:  "Password should not be more than 100 characters",
                valid_password: "Password must contain at least eight characters, including uppercase, lowercase letters, numbers and atleast one special character" 
            },
            confirm_password:{
                required: "Confirm your new password",
                equalTo:"Confirm password should match your new password"
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