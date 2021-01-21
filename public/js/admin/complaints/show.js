function add_new_note() {
	$('#add_note_modal').modal('show');
}



$('.edit_note_button').on('click',function(){

	var note_data=$(this).data('note_data');
	var file_url=$(this).data('file_url');

    $("#property_owner_visibility").prop('checked', false);
    $("#labour_visibility").prop('checked', false);


	$('#note_edit').val(note_data.note);
    console.log(note_data);
	if(note_data.file){
		var file_help_text=`<small class="form-text text-muted">
	    `+translations.complaint_module.file_update_help_text+` (<a target="_blank" href="`+file_url+`">`+translations.complaint_module.download_file+`</a>)
	    </small>`;
		$('#file_help_text').html(file_help_text)
	}

    var visible_to=note_data.visible_to;

    if (visible_to.includes("property owner & manager")) {
        $("#property_owner_visibility").prop('checked', true);
    }

    if (visible_to.includes("labour")) {
        $("#labour_visibility").prop('checked', true);
    }



	var edit_url=$(this).data('edit_url');
	$('#edit_note_form').attr('action',edit_url);

	$('#edit_note_modal').modal('show');
});


$('.delete_note_button').on('click',function(){

	var delete_url=$(this).data('delete_url');

	swal({
      title: translations.complaint_module.warning_title,
      text: translations.complaint_module.note_delete_warning,
      icon: "warning",
      buttons: [translations.general_sentence.button_and_links.cancel,translations.general_sentence.button_and_links.ok],
      dangerMode: false,
	})
	.then((willDelete) => {
	if (willDelete) {

	  $.LoadingOverlay("show");
	  $.ajax({
	    url: delete_url,
	    type: "DELETE",
	    data:{ "_token": $('meta[name="csrf-token"]').attr('content')},
	    success: function (data) {
	      window.location.reload();
	      $.LoadingOverlay("hide");
	      toastr.success(translations.complaint_module.note_delete_success_message, 'Success', {timeOut: 5000});
	    },
	    error: function(jqXHR, textStatus, errorThrown) {
	       $.LoadingOverlay("hide");
	       var response=jqXHR.responseJSON;
	       var status=jqXHR.status;

	       
	       if(status=='404'){
	        toastr.error('Invalid URL', 'Error', {timeOut: 5000});
	       }else if(status=='400'){
	        if(response.message){
	           toastr.error(response.message, 'Error', {timeOut: 5000});
	         }else{
	           toastr.error('Server error', 'Error', {timeOut: 5000});
	         }
	       
	       }else{
	         toastr.error('Internal server error.', 'Error', {timeOut: 5000});
	       }
	    }
	 });

	} 
	});



});



$("#edit_note_form").validate({
    rules: {
        note:{
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

$("#add_note_form").validate({
    rules: {
        note:{
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


$(document).on('change', '.note_file', function() {
    
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
