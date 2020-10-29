    $("#admin_service_add_form").validate({
        rules: {

        
        contract_id:{
            required: true
        },   
        property_id:{
            required: true
        },
        service_provider_id:{
            required: true, 
        },
        property_owner:{
            required: true, 
        },
        service_id:{
            required: true, 
        },
        service_details:{
            required: true,
            maxlength: 1000,  
        },
        service_start_date:{
            required: true, 
            maxlength: 10,
        },
        service_end_date:{
            required: true,
            maxlength: 10, 
        }
        
    },
    messages: {

        contract_id:{
            required:  "Please Select a contract",
        },
        
        property_id: {
            required:  "Please select property",
        },

        service_provider_id:{
            required:  "Please select service provider",
        },
        service_id:{
            required:  "Please select service",
        },
        service_details: {
            required:  "Service Details is required",
            maxlength: "Info should not be more then 1000 characters",
        },
        service_start_date:{
            required:  "Enter start date in dd/mm/yyy format",
        },
        service_end_date:{
            required:  "Enter end date in dd/mm/yyy format",
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
            form.submit();
        }
    });

    
    