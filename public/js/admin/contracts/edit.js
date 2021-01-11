
$("#admin_contract_edit_form").validate({
    ignore:[],
    rules: {
        title:{
            required: true,
            maxlength: 255,  
        },
        description:{
            required: function() 
            {
             CKEDITOR.instances.description.updateElement();
             return true;
            },
        },
 
        property:{
            required: true
        },
        service_provider:{
            required: true, 
        },
        start_date:{
            required: true, 
            maxlength: 10,
        },
        end_date:{
            required: true,
            maxlength: 10, 
        },
    },
    messages: {

        end_date:{
          endDateShouldBeGreatherThanStartDate : function(){
            if(current_locale=='ar'){
              return "يجب أن يكون تاريخ الانتهاء أكبر من تاريخ البدء";
            }else{
              return "End date should be greater than start date";
            }
          }
        },

    },
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        if(element.attr('name')=='description'){
            error.appendTo($('#description_error'));
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





$('#property').select2({
    theme: 'bootstrap4',
    placeholder:translations.contract_manage_module.placeholders.property,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
            return "لم يتم العثور على الممتلكات";
           }else{
             return "No Property Found";
           }
          
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});



$('#services').select2({
    theme: 'bootstrap4',
    placeholder:translations.contract_manage_module.placeholders.service,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
            return "لا توجد خدمة";
           }else{
             return "No Service Found";
           }
          
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


// $('#property').select2({
//     theme: 'bootstrap4',
//     placeholder:'Select property',
//     "language": {
//         "noResults": function(){
//             return "No Property Found <a href='"+$('#property_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
//         }
//     },
//     escapeMarkup: function(markup) {
//         return markup;
//     },
// });

// $('#service_provider').select2({
//     theme: 'bootstrap4',
//     placeholder:'Select property',
//     "language": {
//         "noResults": function(){
//             return "No Service Provider Found <a href='"+$('#service_provider_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
//         }
//     },
//     escapeMarkup: function(markup) {
//         return markup;
//     },
// });


$('#service_provider').select2({
    theme: 'bootstrap4',
    placeholder:translations.contract_manage_module.placeholders.service_provider,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
            return "لم يتم العثور على مقدم خدمة";
           }else{
             return "No Service Provider Found";
           }
          
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


$('#contract_status_id').select2({
    theme: 'bootstrap4',
    placeholder:translations.contract_manage_module.placeholders.status,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
            return "لم يتم العثور على حالة";
           }else{
             return "No Status Found";
           }
          
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});



$('#start_date').datepicker({
    dateFormat:'dd/mm/yy'
});
$('#end_date').datepicker({
    dateFormat:'dd/mm/yy'
});




/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}



$('.datepicker').datepicker({
    dateFormat:'dd/mm/yy'
});




