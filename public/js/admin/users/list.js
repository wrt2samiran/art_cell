//initializing galleries datatable
    var users_table=$('#users_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#users_data_url').val(),
            data: function (d) {
                d.role_id = $('#role_id').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name'},
            { data: 'email', name: 'email' },
            { data: 'role.role_name', name: 'role.role_name' },
            { data: 'status', name: 'status',orderable: false },
            { data: 'created_at', name: 'created_at' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [ [0, 'asc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }],
        "drawCallback": function( settings ) {
            $.LoadingOverlay("hide");
        },
        "language": {
            "url": (current_locale=="ar")?"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"
        }
    });

 //function to delete user
 function delete_user(url){
  swal({
  title: translations.user_manage_module.warning_title,
  text: translations.user_manage_module.delete_warning,
  icon: "warning",
buttons: [translations.general_sentence.button_and_links.ok,translations.general_sentence.button_and_links.cancel],
  dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {

      $.LoadingOverlay("show");
      $.ajax({
        url: url,
        type: "DELETE",
        data:{ "_token": $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
          users_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success(translations.user_manage_module.delete_success_message, 'Success', {timeOut: 5000});
        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }else{
             toastr.error('Internal server error.', 'Error', {timeOut: 5000});
           }
        }
     });
   
    } 
  });

 }

//function to change status of gallery
 function change_status(url,activate_or_deactivate){
  swal({
  title: translations.user_manage_module.warning_title,
  text: translations.user_manage_module.change_status_warning,
  icon: "warning",
buttons: [translations.general_sentence.button_and_links.ok,translations.general_sentence.button_and_links.cancel],
  dangerMode: true,
  })
  .then((confirm) => {
    if (confirm) {
      $.LoadingOverlay("show");
      $.ajax({
        url: url,
        type: "GET",
        data:{},
        success: function (data) {
          users_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success(translations.user_manage_module.change_status_success_message, 'Success', {timeOut: 5000});

        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }else{
             toastr.error('Internal server error.', 'Error', {timeOut: 5000});
           }
        }
     });
 
    // window.location.href=url;
    } 
  });

 }



 $('.role-filter').select2({
  theme: 'bootstrap4',
  placeholder:translations.user_manage_module.placeholders.filter_by_group,
  language: current_locale,
});


$('#role_id').on('change', function(e) {
    if(this.value!=''){
      $('#role-filter-clear').show();
    }else{
      $('#role-filter-clear').hide();
    }
    
    $.LoadingOverlay("show");
    users_table.draw();
});


$('#role-filter-clear').on('click',function(){
  $('#role_id').val("").change();
});


