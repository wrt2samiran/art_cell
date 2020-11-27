
$("#complaint_create_form").validate({
    rules: {
        contract_id:{
            required: true, 
        },
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
        contract_id: {
            required:  "Select contract",
        },
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


$('#contract_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select contract',
    "language": {
       "noResults": function(){
           return "No Contract Found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});


$('#contract_id').on('change',function(){

  var work_orders=$('#contract_id').find(":selected").data("work_orders");

  if(work_orders.length>0){
  	var options=`<option value="">Select Order Order</option>`;
  	for (var i = 0; i <work_orders.length; i++) {
  		options+=`<option value="`+work_orders[0].id+`">`+work_orders[0].task_title+`</option>`;
  	}

  	$('#work_order_id').html(options);
  }else{
  	var options=`<option value="">Select Order Order</option>`;
  	$('#work_order_id').html(options);
  }

  $('#work_order_select_container').show();
  $('#work_order_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select work order',
    "language": {
       "noResults": function(){
           return "No Work Order Found";
       }
    }
  });
  
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

