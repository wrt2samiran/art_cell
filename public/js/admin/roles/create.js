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
            required:  "Group name is required",
            minlength: "Group name should have 3 characters",
            maxlength: "Group name should not be more then 50 characters",
            remote:"Group name alredy exist. Enter different name",
        },
        role_description: {
            required:  "Group description is required",
            minlength: "Group description should have 3 characters",
            maxlength: "Group description should not more then 255 characters"
        },
        parent_role: {
            required:  "Select user type",
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
  placeholder:'Select user type'
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




$('.permission_checkbox').on('change',function(){
    
    var element_id=$(this).attr('id');
    var element_id_split=element_id.split('_');
    var module_id=element_id_split[1];
    var permission_slug=element_id_split[2];

    var permission_slug_split=permission_slug.split('-');
    var action_name=permission_slug_split[permission_slug_split.length-1];

    if(this.checked){
        if(['details','edit','delete','change','create'].includes(action_name)){
            //alert('ok');
            // if($("#permission_"+module_id+'_'+permission_slug_split[0]+'-list').length){
            //     alert('ok');
            // }
        }
    }else{

    }
    
    
});


