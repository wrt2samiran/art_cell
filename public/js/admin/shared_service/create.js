    $("#admin_shared_service_add_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
           
            number_of_days: {
                required: true,
            },
            
            price: {
                required: true,
                number:true
            },
           
            extra_price_per_day: {
                required: true,
                number:true
            },
            selling_price: {
                required: true,
                number:true
            },
        },
        messages: {
            name: {
                required:  "Shared Service name is required",
                minlength: "Shared Service name should have 3 characters",
                maxlength: "Shared Service name should not be more then 255 characters"
            },
            number_of_days: {
                required:  "Number of Days is required",
            },
            price: {
                required:  "Price is required",
            },
            extra_price_per_day: {
                required:  "Extra Price/day is required",
            },
            selling_price: {
                required:  "Selling price is required",
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
            form.submit();
        }

    });


$("input[data-bootstrap-switch]").each(function(){
  $(this).bootstrapSwitch('state', $(this).prop('checked'));
});

$('#is_selling').on('switchChange.bootstrapSwitch', function (event, state) {

    if($("#is_selling").is(':checked')) {
      $('#selling_price_container').show();
    } else {
      $('#selling_price_container').hide();
    }
});

$('#is_sharing').on('switchChange.bootstrapSwitch', function (event, state) {

    if($("#is_sharing").is(':checked')) {
      $('.is_sharing_field').show();
    } else {
      $('.is_sharing_field').hide();
    }
});



$('#images').on('change',function(){
    
    var files = document.getElementById("images").files;

    if(files.length>3){
        reset($('#images'));
        swal('You can upload maximum 3 images');
    }else{
        var file_size_error=false;
        var file_type_error=false;
        for (var i = 0; i < files.length; i++)
        {
            var file_size_in_kb=(files[i].size/1024);
            var file_type= files[i].type;

            if(file_size_in_kb>2048){
               file_size_error=true; 
            }

            var allowed_file_types=[
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            ];

            if(!allowed_file_types.includes(file_type)){
                file_type_error=true;
            }

        }

        if(file_size_error==true || file_type_error==true){
            reset($('#images'));

            var error_message='';

            if(file_size_error==true && file_type_error==true){
                error_message="Please upload only JPG/JPEG/PNG/GIF files of max size 2mb";
            }else if(file_size_error==true && file_type_error==false){
                error_message="File size should not be more than 2 mb";
            }else{
                error_message="Please upload only JPG/JPEG/PNG/GIF files";
            }

            swal(error_message);

        }
    }
});


/*-- reset the image file input --*/
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}

