
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
        // work_order_id:{
        //     required:  "Select work order",
        // },
        subject: {
            required:  "Subject is required",
            maxlength: "Subject should not be more then 100 characters",
        },
        details: {
            required:  "Details is required",
            maxlength: "Details should not be more then 1000 characters",
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
    },
    
});


$('#work_order_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select work order (optional)',
    "language": {
       "noResults": function(){
           return "No work order found";
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
            error_message="Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files of max size 1mb";
        }else if(file_size_error==true && file_type_error==false){
            error_message="File size should not be more than 1 mb";
        }else{
            error_message="Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files";
        }

        swal(error_message);
    }


});


/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}
