
$("#complaint_edit_form").validate({
    rules: {
        // work_order_id:{
        //     required: true
        // },

        subject:{
            required: true,
            maxlength: 100,  
        },
        details:{
            required: true,
            maxlength: 1000,  
        },

    },
    messages: {

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
    },
    
});


    $('#work_order_id').select2({
        theme: 'bootstrap4',
        placeholder:translations.complaint_module.placeholders.work_order,
        "language": {
            locale: current_locale,
           "noResults": function(){
               if(current_locale=='ar'){
                return "لم يتم العثور على أمر عمل";
               }else{
                 return "No Work Order Found";
               }
              
           }
        },
        escapeMarkup: function(markup) {
          return markup;
        },
    });




$(document).on('change', '#file', function() {
    
    var files = this.files;

    var file_size_error=false;
    var file_type_error=false;

    var file_size_in_kb=(files[0].size/1024);
    var file_type= files[0].type;

    if(file_size_in_kb>1024){
       file_size_error=true; 
    }

    var allowed_file_types=['application/pdf',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/msword',
    'image/jpeg',
    'image/jpg',
    'image/png',
    'text/plain'
    ];

    if(!allowed_file_types.includes(file_type)){
        file_type_error=true;
    }

    if(file_size_error==true || file_type_error==true){
        reset($('#'+$(this).attr("id")));

        var error_message='';

        if(file_size_error==true && file_type_error==true){
            error_message=(current_locale=="ar")?"يرجى تحميل ملفات PDF / DOC / JPG / JPEG / PNG / TEXT فقط بحجم أقصى 1 ميجا بايت":"Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files of max size 1Mb";
        }else if(file_size_error==true && file_type_error==false){
            error_message=(current_locale=="ar")?"يجب ألا يزيد حجم الملف عن 1 ميغا بايت":"File size should not be more than 1Mb";
        }else{
            error_message=(current_locale=="ar")?"يرجى تحميل ملفات PDF / DOC / JPG / JPEG / PNG / TEXT فقط":"Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files";
        }

        swal(error_message);
    }


});


/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}
