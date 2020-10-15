
$("#admin_edit_profile_form").validate({
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

        phone: {
            required:  "Phone/Contact number is required",
            minlength: "Phone/Contact number should have minimum 8 characters",
            maxlength: "Phone/Contact number should not be more then 20 characters",
            number:"Only number allowed"
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





$('#profile_image').on('change',function(){
    
    var files = document.getElementById("profile_image").files;
    var file_size_error=false;
    var file_type_error=false;

    var file_size_in_kb=(files[0].size/1024);
    var file_type= files[0].type;

    if(file_size_in_kb>1024){
       file_size_error=true; 
    }

    var supported_types=['image/jpeg','image/png','image/jpg'];

    if(!supported_types.includes(file_type)){
        file_type_error=true;
    }

 
    if(file_size_error==true || file_type_error==true){
        reset($('#profile_image'));

        var error_message='';

        if(file_size_error==true && file_type_error==true){
            error_message="Please upload only jpg/jpeg/png image of max size 1mb";
        }else if(file_size_error==true && file_type_error==false){
            error_message="File size should not be more than 1 mb";
        }else{
            error_message="Please upload only jpg/jpeg/png image";
        }

        swal(error_message);

    }


});


/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}
