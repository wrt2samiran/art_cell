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
            required:  "Group/Role name is required",
            minlength: "Group/Role name should have 3 characters",
            maxlength: "Group/Role name should not be more then 50 characters",
            remote:"Group/Role name alredy exist. Enter different name",
        },
        role_description: {
            required:  "Group/Role description is required",
            minlength: "Group/Role description should have 3 characters",
            maxlength: "Group/Role description should not more then 255 characters"
        },
        parent_role: {
            required:  "Select base group/role under which you want to create new group/role",
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

async function onParentRoleChange(parent_role_id,url){
    $.LoadingOverlay("show");
    try {
        const response = await axios.post(url,{parent_role_id});
        $('#module_permissions_container').html(response.data);
        $.LoadingOverlay("hide");
    } catch (error) {
        $.LoadingOverlay("hide");
        console.error(error);
    }
}



