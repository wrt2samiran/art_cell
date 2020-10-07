$("#admin_roles_create_form").validate({
    rules: {
        role_name: {
            required: true,
            minlength: 3,
            maxlength: 50,
            remote: {
              url: $('#ajax_check_role_name_unique_url').val(),
              type: "post",
                data: {
                  role_name: function() {
                    return $("#role_name" ).val();
                  },
                  "_token": $('meta[name="csrf-token"]').attr('content')
                }
            }
        },
        role_description: {
            required: true,
            minlength: 3,
            maxlength: 255,
        },
        parent_role: {
            required: true,
        },
        'functionalities[]':'required'
    },
    messages: {
        role_name: {
            required:  "Role name is required",
            minlength: "Role name should have 3 characters",
            maxlength: "Role name should not be more then 50 characters",
            remote:"Role name alredy exist. Enter different name",
        },
        role_description: {
            required:  "Role description is required",
            minlength: "Role description should have 3 characters",
            maxlength: "Role description should not more then 255 characters"
        },
        parent_role: {
            required:  "Select the group for which you want  to create the role",
        },
        'functionalities[]':'Select atleast one permission'
    },
    errorPlacement: function (error, element) {
        
        
        if(element.attr('name')=='functionalities[]'){
            error.appendTo($('#permissions_error'));
        }else{
            error.addClass('invalid-feedback');
            error.insertAfter(element);
        }
        
     
    },
    highlight: function (element, errorClass, validClass) {
        if(element.getAttribute('name')!='functionalities[]'){
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if(element.getAttribute('name')!='functionalities[]'){
            $(element).removeClass('is-invalid');    
        }
    },
    submitHandler: function(form) {
        $.LoadingOverlay("show");
        form.submit();
    }
});

$('.parent_role_select2').select2({
  theme: 'bootstrap4',
  placeholder:'Select a group'
});

async function onCountryChange(country_id,url){
    $.LoadingOverlay("show");
    try {
        const response = await axios.post(url,{country_id});
        $('#module_permissions_container').html(response.data);
        $.LoadingOverlay("hide");
    } catch (error) {
        $.LoadingOverlay("hide");
        console.error(error);
    }
}


