


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
        no_of_units:{
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
            required: true, 
        },
        property_manager:{
            required: true, 
        },
        contact_number:{
            required: true,
            minlength: 8,
            maxlength: 20,
            number:true  
        },
        contact_email:{
            required: true,
            email: true,
            maxlength: 100,  
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
        no_of_units:{
             required:  "Please enter number of units of the property",
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
        property_owner: {
            required:  "Please select property owner",
        },
        property_manager: {
            required:  "Please select property manager",
        },
        contact_number: {
            required:  "Contact number is required",
            minlength: "Contact number should have minimum 8 characters",
            maxlength: "Contact number should not be more then 20 characters",
            number:"Only number allowed"
        },
        contact_email: {
            required:  "Contact email is required",
            email: "Please enter valid email address",
            maxlength: "Email should not be more then 100 characters"
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

$('#city_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select city'
});

$('#property_type_id').select2({
    theme: 'bootstrap4',
    placeholder:'Select property type'
});

$('#electricity_account_day').select2({
    theme: 'bootstrap4',
    placeholder:'Select electricity account day'
});
$('#water_acount_day').select2({
    theme: 'bootstrap4',
    placeholder:'Select water account day'
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


$('#property_files').on('change',function(){
    
    var files = document.getElementById("property_files").files;
    var file_size_error=false;
    var file_type_error=false;
    for (var i = 0; i < files.length; i++)
    {
        var file_size_in_kb=(files[i].size/1024);
        var file_type= files[i].type;
  
        if(file_size_in_kb>1024){
           file_size_error=true; 
        }

        var allowed_file_types=['application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/msword',
        'application/jpeg',
        'application/jpg',
        'application/png',
        'text/plain'
        ];

        if(!allowed_file_types.includes(file_type)){
            file_type_error=true;
        }

    }

    if(file_size_error==true || file_type_error==true){
        reset($('#property_files'));

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

