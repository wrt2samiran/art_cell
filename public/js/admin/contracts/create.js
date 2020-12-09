


$("#admin_contract_create_form").validate({
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
            endDateShouldBeGreatherThanStartDate:true 
        },
    },
    messages: {
        title: {
            required:  "Contract title is required",
        },
        property:{
            required:  "Select property",
        },
        description: {
            required:  "Contract description is required",
        },
        service_provider:{
            required:  "Please select service provider",
        },
        contract_price:{
            required:  "Enter contract price",
        },
        start_date:{
            required:  "Enter start date in dd/mm/yyy format",
        },
        end_date:{
            required:  "Enter end date in dd/mm/yyy format",
            endDateShouldBeGreatherThanStartDate : "End date should be greater than start date"
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

$('#property').select2({
    theme: 'bootstrap4',
    placeholder:'Select property',
    "language": {
       "noResults": function(){
           return "No Property Found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#services').select2({
    theme: 'bootstrap4',
    placeholder:'Select services',
    "language": {
       "noResults": function(){
           return "No Service Found";
       }
    },
    escapeMarkup: function(markup) {
      return markup;
    },
});

$('#property').select2({
    theme: 'bootstrap4',
    placeholder:'Select property',
    "language": {
        "noResults": function(){
            return "No Property Found <a href='"+$('#property_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
        }
    },
    escapeMarkup: function(markup) {
        return markup;
    },
});

$('#service_provider').select2({
    theme: 'bootstrap4',
    placeholder:'Select property',
    "language": {
        "noResults": function(){
            return "No Service Provider Found <a href='"+$('#service_provider_create_url').val()+"' target='_blank' class='btn btn-success'>Create New One</a>";
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

$('#contract_files').on('change',function(){
    
    var files = document.getElementById("contract_files").files;
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
        reset($('#contract_files'));

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



$('.datepicker').datepicker({
    dateFormat:'dd/mm/yy'
});




