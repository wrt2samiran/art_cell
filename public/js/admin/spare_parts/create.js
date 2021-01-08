    $("#admin_spare_parts_add_form").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            manufacturer: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            unit_master_id: {
                required: true,
            },
            
            price: {
                required: true,
            },
            image: {
                required: true,
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
                error_message=(current_locale=="ar")?"يرجى تحميل ملفات JPG / JPEG / PNG / GIF فقط بحجم 2 ميغابايت بحد أقصى":"Please upload only JPG/JPEG/PNG/GIF files of max size 2MB";
            }else if(file_size_error==true && file_type_error==false){
                error_message=(current_locale=="ar")?"يجب ألا يزيد حجم الملف عن 2 ميغا بايت":"File size should not be more than 2MB";
            }else{
                error_message=(current_locale=="ar")?"يرجى تحميل ملفات JPG / JPEG / PNG / GIF فقط":"Please upload only JPG/JPEG/PNG/GIF files";
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
