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

    if(action_name=='change'){
       permission_slug_split.splice(-2,permission_slug_split.length - 1);
    }else{
       permission_slug_split.splice(-1,permission_slug_split.length - 1);
    }

    var module_name=permission_slug_split.join('-');

    var list_permission_checkbox=$("#permission_"+module_id+'_'+module_name+'-list');

    if(this.checked){
        if(['details','edit','delete','change','create'].includes(action_name)){

            if(list_permission_checkbox.length){
                
                list_permission_checkbox.prop("checked", true);
            }
        }
    }else{

        var dependent_checkbox_array=['create','edit','details','delete','status-change'];
        var dependent_checkbox_checked=false;

        dependent_checkbox_array.forEach(function(dependent_checkbox){

            var checkbox=$("#permission_"+module_id+'_'+module_name+'-'+dependent_checkbox);

            if(checkbox.length && checkbox.prop("checked")){
                dependent_checkbox_checked=true;
            }
        });




        if(['details','edit','delete','change','create'].includes(action_name)){

            if(dependent_checkbox_checked==false){
                if(list_permission_checkbox.length &&  list_permission_checkbox.prop("checked")){
                    list_permission_checkbox.prop("checked", false);
                }
            }
        }else if(action_name=='list'){

            if(dependent_checkbox_checked){
                swal("You need to give list permission along with create/edit/details/delete/status-change because list page is dpependent on that functionality"); 
                if(list_permission_checkbox.length){
                    list_permission_checkbox.prop("checked", true);
                }
            }
           
        }

    }
    
    
});


