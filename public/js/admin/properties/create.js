


$("#admin_property_create_form").validate({
    rules: {
        property_name:{
            required: true,
            minlength: 2,
            maxlength: 100,  
        },
        property_type_id:{
            required: true
        },
        description:{
            required: true,
            maxlength: 1000,  
        },
        no_of_active_units:{
            required:true,
            number:true
        },
        no_of_inactive_units: {
            required:true,
            number:true
        },
        city_id:{
            required: true, 
        },
        address:{
            required: true,
            maxlength: 255,  
        },
        location:{
            required: true,
            maxlength: 255,  
        },
        property_owner:{
            required: true
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


  $('.file_title_list').each(function(i, obj) {

    $(this).rules("add", {
       required: true,
       maxlength: 100,
       messages: {
       }
    });

  });






$('#city_id').select2({
    theme: 'bootstrap4',
    placeholder:translations.property_manage_module.placeholders.city,
    language: current_locale,
});

$('#property_type_id').select2({
    theme: 'bootstrap4',
    placeholder:translations.property_manage_module.placeholders.property_type,
    language: current_locale,
});



$('#property_owner').select2({
    theme: 'bootstrap4',
    placeholder:translations.property_manage_module.placeholders.property_owner,
    "language": {
        locale: current_locale,
       "noResults": function(){
           if(current_locale=='ar'){
            return "لم يتم العثور على مالك عقار";
           }else{
             return "No Property Owner Found";
           }
          
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#property_manager').select2({
    theme: 'bootstrap4',
    placeholder:translations.property_manage_module.placeholders.property_manager,
    "language": {
         locale: current_locale,
        "noResults": function(){
            if(current_locale=='ar'){
                return "لم يتم العثور على مدير عقارات ";
            }else{
                return "No Property Manager Found ";
            }
            
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});


$("#add_new_file").on("click", function () {
    var max_filesize=$('#max_filesize').val();
    let random_string = String(Math.random(10)).substring(2,14); 

    var help_text=(current_locale=="ar")?"تحميل ملفات PDF / DOC / JPEG / PNG / TEXT بحد أقصى. "+max_filesize+"Mb":"Upload PDF/DOC/JPEG/PNG/TEXT files of max. "+max_filesize+"Mb";

    var row=`<div class="row mt-1 files_row">`;
    row += `<div class="col-md-6"><input placeholder="`+translations.property_manage_module.placeholders.image_title+`" class="form-control file_title_list"  id="title_`+random_string+`" name="title[]" type="text"></div>`;
    row += `<div class="col-md-5">
    <input placeholder="File" required class="form-control file_list"  id="property_files_`+random_string+`" name="property_files[]" type="file">
      <small class="form-text text-muted">
        `+help_text+`
      </small>
    </div>`;
    row += `<div class="col-md-1"><button data-delete_url="" type="button" class="btn btn-danger files_row_del_btn"><i class="fa fa-trash" aria-hidden="true"></i></button></div>`;
    row +=`</div>`;
    $("#files_container").append(row);

    $('#title_'+random_string).rules("add", {
       required: true,
       maxlength: 100,
       messages: {
       }
    });







});

$(document).on('click', '.files_row_del_btn', function(){  
    
    var element_to_remove=$(this).closest(".files_row");
    element_to_remove.remove();
    
});



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




