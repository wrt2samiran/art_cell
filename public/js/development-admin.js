
//Integer and decimal
$.validator.addMethod("valid_number", function(value, element) {
    if (/^[0-9]\d*(\.\d+)?$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

//minimum 8 digit,small+capital letter,number,special character
$.validator.addMethod("valid_password", function(value, element) {
    if (/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/.test(value)) {
        return true;
    } else {
        return false;
    }
});

//email validation
$.validator.addMethod("valid_email", function(value, element) {
    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value)) {
        return true;
    } else {
        return false;
    }
});
//unique slug validation
$.validator.addMethod("enique_slug", function(value, element) {
    if (/^[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*$/.test(value)) {
        return true;
    } else {
        return false;
    }
});


//this function will call when language will change from header language dropdown
function onLanguageChange(lang){
  window.location.href=baseUrl+'/language/'+lang;
}


//============= Add User==========================    


    $("#Create_User").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            email: {
                required:    true,
                valid_email: true
            },
            phone:{
                required: true,
                valid_number: true 

            },
            password:{
                required: true,
                valid_password: true
            },
            confirm_password:{
                required: true,
                valid_password: true,
                same:"#password"
            },
            role_id:{
                required: true
            },
            usertype:{
                required:true
            },
            status:{
                required:true,
            },

        },
        messages: {
            name: {
                required:  "This field is required",
                minlength: "Name should have 3 characters",
                maxlength: "Name should not more then 50 characters"
            },
            email: {
                required:  "This field is required",
                valid_email:"Please enter a valid email"

            },
            phone: {
                required:  "This field is required",
                valid_number:"Please enter a valid number"

            },
            password:{
                required: "This field is required",
                valid_password:"Please enter a valid password"
            },
            confirm_password:{
                required: "This field is required",
                valid_password:"Please enter a valid password",
                same:"password doesnt match"
            },
           role_id:{
            required: "This field is required"
           },
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });
//============= Add User==========================

//============= Edit User==========================
    $("#user_edit").validate({
        ignore: ':hidden',
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            email: {
                required:    true,
                valid_email: true
            },
            phone:{
                required: true,
                valid_number: true 

            },
            password:{
               
                
            },
            confirm_password:{
                
                
            },
            role_id:{
                required: true
            },
            status:{
                required:true,
            },
            website:{
                required:true,
            },
            facebook_url:{
                required:true,
            },
            twitter_url:{
                required:true,
            },
            linkedin_url:{
                required:true,
            },
            additional_info:{
                required:true,
            },

        },
        messages: {
            name: {
                required:  "This field is required",
                minlength: "Name should have 3 characters",
                maxlength: "Name should not more then 50 characters"
            },
            email: {
                required:  "This field is required",
                valid_email:"Please enter a valid email"

            },
            phone: {
                required:  "This field is required",
                valid_number:"Please enter a valid number"

            },
            password:{
                required: "This field is required",
                valid_password:"Please enter a valid password"
            },
            confirm_password:{
                required: "This field is required",
                valid_password:"Please enter a valid password"
            },
           role_id:{
            required: "This field is required"
           },
           status:{
            required: "This field is required"  
           },
           website:{
            required: "This field is required"  
           } ,
           facebook_url:{
            required: "This field is required"  
           },
           twitter_url:{
            required: "This field is required"  
           },
           linkedin_url:{
            required: "This field is required"  
           },  
           additional_info:{
            required: "This field is required"  
           }        
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });
//============= Edit User==========================
//============= Create Role==========================
    $("#create_role").validate({
        rules: {
            role_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            role_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            status:{
                required:true,
            },
           
        },
        messages: {
            role_name: {
                required:  "This field is required",
                minlength: "Role name should have 3 characters",
                maxlength: " Role name should not more then 50 characters"
            },
            role_description: {
                required:  "This field is required",
                minlength: "Role description should have 3 characters",
                maxlength: " Role description should not more then 255 characters"
            },
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

//============= Create Role==========================

//============= Edit Role==========================
$(document).ready(function () {
    $("#edit_role").validate({
        rules: {
            role_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            role_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            status:{
                required:true,
            },
           
        },
        messages: {
            role_name: {
                required:  "This field is required",
                minlength: "Role name should have 3 characters",
                maxlength: " Role name should not more then 50 characters"
            },
            role_description: {
                required:  "This field is required",
                minlength: "Role description should have 3 characters",
                maxlength: " Role description should not more then 255 characters"
            },
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Edit Role==========================
//============= Create Module==========================
$(document).ready(function () {
    $("#create_module").validate({
        rules: {
            module_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            module_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            slug:{
                required:true,
                

            },

            status:{
                required:true,
            },
           
        },
        messages: {
            module_name: {
                required:  "This field is required",
                minlength: "Module name should have 3 characters",
                maxlength: "Module name should not more then 50 characters"
            },
            module_description: {
                required:  "This field is required",
                minlength: "Module description should have 3 characters",
                maxlength: "Module description should not more then 255 characters"
            },
            slug:{
                required: "This field is required" ,
                
               },  
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Create Module==========================
//============= Edit Module==========================
$(document).ready(function () {
    $("#edit_module").validate({
        rules: {
            module_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            module_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            slug:{
                required:true,
               
            },

            status:{
                required:true,
            },
           
        },
        messages: {
            module_name: {
                required:  "This field is required",
                minlength: "Module name should have 3 characters",
                maxlength: "Module name should not more then 50 characters"
            },
            module_description: {
                required:  "This field is required",
                minlength: "Module description should have 3 characters",
                maxlength: "Module description should not more then 255 characters"
            },
            slug:{
                required: "This field is required" ,
                
               },  
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Edit Module==========================
//============= Create Functionality==========================
$(document).ready(function () {
    $("#create_function").validate({
        rules: {
            module_id:{
                required:true
            },
            function_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            function_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            slug:{
                required:true,
                enique_slug:true

            },

            status:{
                required:true,
            },
           
        },
        messages: {
            module_id:{
                required:"Please select this field"
            },
            function_name: {
                required:  "This field is required",
                minlength: "Function name should have 3 characters",
                maxlength: "Function name should not more then 50 characters"
            },
            function_description: {
                required:  "This field is required",
                minlength: "Function description should have 3 characters",
                maxlength: "Function description should not more then 255 characters"
            },
            slug:{
                required: "This field is required" 
                
               },  
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Create Functionality==========================
//============= Edit Functionality==========================
$(document).ready(function () {
    $("#edit_function").validate({
        rules: {
            module_id:{
                required:true
            },
            function_name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
           
            function_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            slug:{
                required: true
               

            },

            status:{
                required: true,
            }
           
        },
        messages: {
            module_id:{
                required:"Please select this field"
            },
            function_name: {
                required:  "This field is required",
                minlength: "Function name should have 3 characters",
                maxlength: "Function name should not more then 50 characters"
            },
            function_description: {
                required:  "This field is required",
                minlength: "Function description should have 3 characters",
                maxlength: "Function description should not more then 255 characters"
            },
            slug:{
                required: "This field is required" ,
                
               },  
               status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Edit Functionality==========================
//============= Change Password==========================
$(document).ready(function () {
    $("#change_password").validate({
        rules: {
            current_password: {
                required: true,
            },
           
            new_password: {
                required: true,
                valid_password:true  
               
            },
            confirm_password:{
                required: true,
                valid_password: true,
                same:"#new-password"
            },
           
        },
        messages: {
            current_password: {
                required:  "This field is required",
                
            },
            new_password: {
                required:  "This field is required",
                valid_password: "Please enter valid password"
               
            },
            confirm_password:{
                required: "This field is required.",
                valid_password:"Please enter a valid password",
                same:"Password doesnt match."
            },
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Change Password ==========================
//=============Login form==========================
$(document).ready(function () {
    $("#login_table").validate({
        rules: {
            email: {
                required: true,
                valid_email:true
            },
           
            password: {
                required: true,
                valid_password:true  
               
            }
            
           
        },
        messages: {
            email: {
                required:  "This field is required",
                valid_email:"Please enter valid email"
                
            },
            password: {
                required:  "This field is required",
                valid_password: "Please enter valid password"
               
            }
          
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Login form ==========================
//============forgotpassword form==========================
$(document).ready(function () {
    $("#forgot_password").validate({
        rules: {
            email: {
                required: true,
                valid_email:true
            }           
        },
        messages: {
            email: {
                required:  "This field is required",
                valid_email:"Please enter valid email"
                
            },
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.input-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= forgotpassword form ==========================

//============= Subscription Create==========================
$(document).ready(function () {
    $("#sub_create").validate({
        rules: {
            subscription_title: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            subscription_type:{
                required:true
            },
            submission_count:{
                required:true,
                valid_number:true

            },
            price:{
                required:true,
                valid_number:true
            },

           
            subscription_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            status:{
                required:true,
            },
           
        },
        messages: {
           
            subscription_title: {
                required:  "This field is required",
                minlength: "Subscription title should have 3 characters",
                maxlength: "Subscription title should not more then 50 characters"
            },
            subscription_type:{
                required:  "This field is required", 
                
            },
            submission_count:{
                required:  "This field is required",
                valid_number:"Please type number"
            },
            price:{
                required:  "This field is required",
                valid_number:"Please type number"
            },
            subscription_description: {
                required:  "This field is required",
                minlength: "Subscription description should have 3 characters",
                maxlength: "Subscription description should not more then 255 characters"
            },  
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Subscription Create==========================

//============= Subscription Edit==========================
$(document).ready(function () {
    $("#sub_edit").validate({
        rules: {
            subscription_title: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            subscription_type:{
                required:true
            },
            submission_count:{
                required:true,
                valid_number:true

            },
            price:{
                required:true,
                valid_number:true
            },

           
            subscription_description: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            status:{
                required:true,
            },
           
        },
        messages: {
           
            subscription_title: {
                required:  "This field is required",
                minlength: "Subscription title should have 3 characters",
                maxlength: "Subscription title should not more then 50 characters"
            },
            subscription_type:{
                required:  "This field is required", 
                
            },
            submission_count:{
                required:  "This field is required",
                valid_number:"Please type number"
            },
            price:{
                required:  "This field is required",
                valid_number:"Please type number"
            },
            subscription_description: {
                required:  "This field is required",
                minlength: "Subscription description should have 3 characters",
                maxlength: "Subscription description should not more then 255 characters"
            },  
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Subscription Edit==========================

//============= Create Category==========================
$(document).ready(function () {
    $("#Create_category").validate({
        rules: {
            
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            slug:{
                required:true,
                enique_slug:true
              
            },

            status:{
                required:true,
            },
           
        },
        messages: {
           
            name: {
                required:  "This field is required",
                minlength: "Category name should have 3 characters",
                maxlength: "Category name should not more then 50 characters"
            },
            
            slug:{
                required: "This field is required" ,
                enique_slug:"Slug must be unique"
                
               },  
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Create Category==========================

//============= Edit Category==========================
$(document).ready(function () {
    $("#category_edit").validate({
        rules: {
            
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            slug:{
                required:true,
                enique_slug:true
                
            },

            status:{
                required:true,
            },
           
        },
        messages: {
           
            name: {
                required:  "This field is required",
                minlength: "Category name should have 3 characters",
                maxlength: "Category name should not more then 50 characters"
            },
            
            slug:{
                required: "This field is required" ,
                enique_slug:"Don't use whitesapce and special characters."
                
               },  
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Edit Category==========================

//============= Create Our Services==========================
$(document).ready(function () {
    $("#Create_outservice").validate({
        rules: {
            
            title: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            description:{
                required:true,
                minlength: 3,
                maxlength: 255,
              
            },
           

            status:{
                required:true,
            },
           
        },
        messages: {
           
            title: {
                required:  "This field is required",
                minlength: " Title should have 3 characters",
                maxlength: " Title should not more then 50 characters"
            },
            
            description:{
                required: "This field is required" ,
                minlength: "Description should have 3 characters",
                maxlength: "Description should not more then 255 characters"
                
               },
              
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Create Our Services==========================

//============= Edit Our Services==========================
$(document).ready(function () {
    $("#ourservice_edit").validate({
        rules: {
            
            title: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            description:{
                required:true,
                minlength: 3,
                maxlength: 255,
              
            },
           

            status:{
                required:true,
            },
           
        },
        messages: {
           
            title: {
                required:  "This field is required",
                minlength: " Title should have 3 characters",
                maxlength: " Title should not more then 50 characters"
            },
            
            description:{
                required: "This field is required" ,
                minlength: "Description should have 3 characters",
                maxlength: "Description should not more then 255 characters"
                
               }, 
              
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= Edit Our Services==========================

//============= create addon==========================
$(document).ready(function () {
    $("#Create_addon").validate({
        rules: {
            
            title: {
                required: true,
                minlength: 3,
                maxlength: 50,
            },
            description:{
                required:true,
                minlength: 3,
                maxlength: 255,
              
            },
            addon_logo:{
                required:true,
            },
           

            status:{
                required:true,
            },
           
        },
        messages: {
           
            title: {
                required:  "This field is required",
                minlength: " Title should have 3 characters",
                maxlength: " Title should not more then 50 characters"
            },
            
            description:{
                required: "This field is required" ,
                minlength: "Description should have 3 characters",
                maxlength: "Description should not more then 255 characters"
                
               }, 
               addon_logo:{
                required: "This field is required"  
               },   
              
           status:{
            required: "This field is required"  
           }   
        },
        errorElement: 'span',
            errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            },
        submitHandler: function(form) {
            form.submit();
        }
    });

});
//============= create addon==========================