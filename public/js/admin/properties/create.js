


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
        property_manager:{
            required: true
        },
 
    },
    messages: {
        property_name: {
            required:  "Property name is required",
            minlength: "Property name should have 2 characters",
            maxlength: "Property name should not be more then 100 characters",
        },
        property_type_id:{
            required:  "Select property type",
        },
        description: {
            required:  "Description is required",
            maxlength: "Description should not be more then 1000 characters",
        },
        no_of_active_units:{
             required:  "Please enter number of active units of the property",
        },
        no_of_inactive_units:{
            required:  "Please enter number of inactive units of the property",
        },
        city_id: {
            required:  "Please select city from dropdown list",
        },
        address: {
            required:  "Address is required",
            maxlength: "Address should not be more then 255 characters",
        },
        location:{
            required:  "Location is required",
            maxlength: "Location should not be more then 255 characters",
        },
        property_owner:{
            required:  "Select property owner",
        },
        property_owner:{
            required:  "Select property manager",
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






$('#city_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select city'
});

$('#property_type_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select property type'
});



$('#property_owner').select2({
    theme: 'bootstrap4',
    placeholder:'Select property owner',
    "language": {
       "noResults": function(){
           return "No Property Owner Found <a href='"+$('#property_owner_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#property_manager').select2({
    theme: 'bootstrap4',
    placeholder:'Select property manager',
    "language": {
        "noResults": function(){
            return "No Property Manager Found <a href='"+$('#property_manager_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});


$("#add_new_file").on("click", function () {
    let random_string = String(Math.random(10)).substring(2,14); 
    var row=`<div class="row mt-1 files_row">`;
    row += `<div class="col-md-6"><input placeholder="Title" class="form-control file_title_list"  id="title_`+random_string+`" name="title[]" type="text"></div>`;
    row += `<div class="col-md-5">
    <input placeholder="File" required class="form-control file_list"  id="property_files_`+random_string+`" name="property_files[]" type="file">
      <small class="form-text text-muted">
        Upload PDF/DOC/JPEG/PNG/TEXT files of max. 1mb
      </small>
    </div>`;
    row += `<div class="col-md-1"><button data-delete_url="" type="button" class="btn btn-danger files_row_del_btn"><i class="fa fa-trash" aria-hidden="true"></i></button></div>`;
    row +=`</div>`;
    $("#files_container").append(row);

    $('#title_'+random_string).rules("add", {
       required: true,
       maxlength: 100,
       messages: {
         required: "Enter title",
         maxlength: "Maximum 100 characters allowed",
       }
    });







});

$(document).on('click', '.files_row_del_btn', function(){  
    
    var element_to_remove=$(this).closest(".files_row");
    element_to_remove.remove();
    
});



$(document).on('change', '.file_list', function() {
    
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




