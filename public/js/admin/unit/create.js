
$("#unit_create_form").validate({
    rules: {
        unit_name:{
            required: true,
            minlength: 2,
            maxlength: 100,  
        },
        
    },
    messages: {
        unit_name: {
            required:  "Unit name is required",
            minlength: "Unit name should have 2 characters",
            maxlength: "Unit name should not be more then 100 characters",
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



