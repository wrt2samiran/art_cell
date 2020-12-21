    $("#admin_shared_service_edit_form").validate({
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
            },
           
            extra_price_per_day: {
                required: true,
            }

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
            quantity_available: {
                required:  "Enter available quantity",
            }

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



$('#images').on('change',function(){
    
    var files = document.getElementById("images").files;

    if(files.length>3){
        reset($('#images'));
        swal('You can upload maximum 3 images');
    }else{
        var max_filesize_mb=$('#max_filesize').val();
        var max_filesize_kb=1024*parseFloat(max_filesize_mb);
        var file_size_error=false;
        var file_type_error=false;
        for (var i = 0; i < files.length; i++)
        {
            var file_size_in_kb=(files[i].size/1024);
            var file_type= files[i].type;

            if(file_size_in_kb>max_filesize_kb){
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