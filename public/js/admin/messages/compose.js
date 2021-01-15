$("#message_compose_form").validate({
    ignore:[],
    rules: {
        subject:{
            required: true,
            maxlength: 100,  
        },
        message_to:{
            required: true,
        },
        message:{
            required: true
        },
    },
    messages: {
        subject: {
            required:  "Subject is required",
        },
        message_to:{
            required:  "Select recipient",
        },
        message: {
            required:  "Message is required",
        },

    },
    errorPlacement: function (error, element) {

        error.addClass('invalid-feedback');
        if(element.attr('name')=='message'){
            error.appendTo($('#message_error'));
        }else if(element.attr('name')=='message_to'){
            error.appendTo($('#message_to_error'));
        }
        else{
            error.insertAfter(element);
        }
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



$('#message_to').select2({
    theme: 'bootstrap4',
    placeholder:translations.message_module.placeholders.recipient,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
            return "لا مستخدم";
           }else{
             return "No User";
           }
          
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});