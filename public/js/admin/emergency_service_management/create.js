$("#admin_task_add_form").validate({
        rules: {

            
            
            contract_id: {
                required: true,
            },
            
            service_id: {
                required: true,
            },
            property_id: {
                required: true,
            },
            country_id: {
                required: true,
            },
            state_id: {
                required: true,
            },           
            city_id: {
                required: true,
            },
            labour_id: {
                required: true,
            },
            date_range: {
                required: true,
            },
            task_title: {
                required: true,
                minlength: 3,
                maxlength: 50,
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


// $('#contract_id').select2({
//     theme: 'bootstrap4',
//     placeholder:'ar'?'الاسم الاول':'Select contract'
// });


$('#contract_id').select2({
    theme: 'bootstrap4',
    placeholder:translations.emergency_service_manage_module.placeholders.select_contract,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
            return "لا الاسم خدمة";
           }else{
             return "No Contract Found";
           }
          
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

    

    
    