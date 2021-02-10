
$("#order_create_form").validate({
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
        
        customer_contact:{
            required: true,
            minlength: 10,
            maxlength: 10,
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
        mobile_brand_id:{
            required: true,
        },

        mobile_brand_model_id:{
            required: true, 
        },
        physical_condition:{
            required: true,
        },
        service_complaints:{
            required: true,
        },
        estimated_price:{
            required: true,
        },
        advanced_payment:{
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
        
        customer_contact: {
            required:  "Phone/Contact number is required",
            minlength: "Phone/Contact number should have minimum 10 characters",
            maxlength: "Phone/Contact number should not be more then 10 characters",
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
        mobile_brand_id:{
            required: "Please select brand", 
        },
        mobile_brand_model_id:{
            required: "Please select model", 
        },
        physical_condition:{
            required: "Please enter physical condition",
        },
        service_complaints:{
            required: "Please enter service complaints",
        },
        estimated_price:{
            required: "Please enter estimated price",
        },
        advanced_payment:{
            required: "Please enter advance payment",
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




