

$(document).ready(function(){

  $("#contract_files_form").validate({
      rules: {

      },
      messages:{
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


  $('.file_title_list').each(function(i, obj) {

    $(this).rules("add", {
       required: true,
       maxlength: 100,
       messages: {
         required: "Enter title",
         maxlength: "Maximum 100 characters allowed",
       }
    });

  });


  // $('.file_list').each(function(i, obj) {

  //   if($(this).data('is_required')=='yes'){
  //     $(this).rules("add", {
  //        required: true,
  //        messages: {
  //          required: "Upload file",
  //        }
  //     });
  //   }else{
  //      $(this).rules('remove', 'required');
  //   }

  // });


});



$("#add_new_file").on("click", function () {
    var max_filesize=$('#max_filesize').val();
    let random_string = String(Math.random(10)).substring(2,14); 
    var help_text=(current_locale=="ar")?"تحميل ملفات PDF / DOC / JPEG / PNG / TEXT بحد أقصى. "+max_filesize+"Mb":"Upload PDF/DOC/JPEG/PNG/TEXT files of max. "+max_filesize+"Mb";

    var row=`<div class="row mt-1 files_row">`;
    row += `<div class="col-md-6"><input placeholder="`+translations.contract_manage_module.placeholders.image_title+`" class="form-control file_title_list"  id="title_`+random_string+`" name="title[]" type="text"></div>`;
    row += `<div class="col-md-5">
    <input placeholder="File" required data-is_required="yes" class="form-control file_list"  id="contract_files_`+random_string+`" name="contract_files[]" type="file">
      <small class="form-text text-muted">
        `+help_text+`
      </small>
    </div>`;
    row += `<div class="col-md-1"><button data-delete_url="" type="button" class="btn btn-danger files_row_del_btn"><i class="fa fa-trash" aria-hidden="true"></i></button></div>`;
    row +=`<input type="hidden" name="file_id[]" value=""></div>`;
    $("#files_container").append(row);

    $('#title_'+random_string).rules("add", {
       required: true,
       maxlength: 100,
       messages: {

       }
    });



    // $('#contract_files_'+random_string).rules("add", {
    //    required: true,
    //    messages: {
    //      required: "Upload file",
    //    }
    // });






});

$(document).on('click', '.files_row_del_btn', function(){  
    
    var delete_url=$(this).data('delete_url');
    var element_to_remove=$(this).closest(".files_row");
    if(delete_url){

      swal({
      title: translations.contract_manage_module.warning_title,
      text: translations.contract_manage_module.file_delete_warning,
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
              element_to_remove.remove();
              $.LoadingOverlay("hide");
              toastr.success(translations.contract_manage_module.file_delete_success_message, 'Success', {timeOut: 5000});
            },
            error: function(jqXHR, textStatus, errorThrown) {
               $.LoadingOverlay("hide");
               var response=jqXHR.responseJSON;
               var status=jqXHR.status;
               if(status=='404'){
                toastr.error('Invalid URL', 'Error', {timeOut: 5000});
               }
               else if(status=='403'){
                  toastr.error('You do not have permission to perform this action.', 'Error', {timeOut: 5000});
               }
               else{
                 toastr.error('Internal server error.', 'Error', {timeOut: 5000});
               }
            }
         });

         
        } 
      });
      


    }else{
      element_to_remove.remove();
    }
    
});



 function delete_attach_file(url,file_id){
  swal({
    title: translations.contract_manage_module.warning_title,
    text: translations.contract_manage_module.file_delete_warning,
    icon: "warning",
    buttons: [translations.general_sentence.button_and_links.cancel,translations.general_sentence.button_and_links.ok],
    dangerMode: false
  })
  .then((willDelete) => {
    if (willDelete) {
        
      $.LoadingOverlay("show");
      $.ajax({
        url: url,
        type: "DELETE",
        data:{ "_token": $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {

          $('#attachment_file_'+file_id).remove();

          var NumberOfFilePresent = $('.attachment_files').length;
          if(NumberOfFilePresent=='0'){
            $('.attachment_files_container').append('<div class="col-md-12 text-muted">No files attached to this property</div>');
          }
       
          $.LoadingOverlay("hide");
          toastr.success(translations.contract_manage_module.file_delete_success_message, 'Success', {timeOut: 5000});
        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }
           else if(status=='403'){
              toastr.error('You do not have permission to perform this action.', 'Error', {timeOut: 5000});
           }
           else{
             toastr.error('Internal server error.', 'Error', {timeOut: 5000});
           }
        }
     });

     
    } 
  });

 }


$(document).on('change', '.file_list', function() {
    
    var max_filesize_mb=$('#max_filesize').val();
    var max_filesize_kb=1024*parseFloat(max_filesize_mb);

    var files = this.files;

    var file_size_error=false;
    var file_type_error=false;

    var file_size_in_kb=(files[0].size/1024);
    var file_type= files[0].type;

    if(file_size_in_kb>max_filesize_kb){
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

           error_message=(current_locale=="ar")?"يرجى تحميل ملفات PDF / DOC / JPG / JPEG / PNG / TEXT فقط ذات الحجم الأقصى "+max_filesize_mb+"Mb":"Please upload only PDF/DOC/JPG/JPEG/PNG/TEXT files of max size "+max_filesize_mb+"Mb";

        }else if(file_size_error==true && file_type_error==false){
            error_message=(current_locale=="ar")?"يجب ألا يزيد حجم الملف عن "+max_filesize_mb+"Mb":"File size should not be more than "+max_filesize_mb+"Mb";
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

