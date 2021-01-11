
    var spare_parts_table=$('#spare_parts_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: $('#spare_parts_data_url').val(),
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name'},
            { data: 'manufacturer', name: 'manufacturer'},
            { data: 'unitmaster.unit_name', name: 'unitmaster.unit_name',orderable: false, searchable: false },
            { data: "price", render: function ( data, type, row, meta ) {return row['currency'] + ' ' + data ;}},
            { data: 'is_active', name: 'is_active' },
            { data: 'created_at', name: 'created_at' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [ [0, 'asc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }],
        "language": {
            "url": (current_locale=="ar")?"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"
        }

    });

 //function to delete country
 function delete_shared_service(url){
  swal({
  title: translations.spare_part_manage_module.warning_title,
  text: translations.spare_part_manage_module.delete_warning,
  icon: "warning",
  buttons: [translations.general_sentence.button_and_links.cancel,translations.general_sentence.button_and_links.ok],
  dangerMode: false,
  })
  .then((willDelete) => {
    if (willDelete) {

      $.LoadingOverlay("show");
      $.ajax({
        url: url,
        type: "DELETE",
        
        data:{ "_token": $('meta[name="csrf-token"]').attr('content')},
        success: function (data) {
          $.LoadingOverlay("hide");
          spare_parts_table.ajax.reload(null, false);
          toastr.success(translations.spare_part_manage_module.delete_success_message, 'Success', {timeOut: 5000});
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
  title: translations.spare_part_manage_module.warning_title,
  text: translations.spare_part_manage_module.delete_warning,
  icon: "warning",
  buttons: [translations.general_sentence.button_and_links.cancel,translations.general_sentence.button_and_links.ok],
  dangerMode: false,
  })
  .then((confirm) => {
    if (confirm) {
      $.LoadingOverlay("show");
      $.ajax({
        url: url,
        type: "GET",
        data:{},
        success: function (data) {
          $.LoadingOverlay("hide");
          spare_parts_table.ajax.reload(null, false);
          toastr.success(translations.spare_part_manage_module.change_status_success_message, 'Success', {timeOut: 5000});
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

 $("document").ready(function(){
    setTimeout(function(){
        $(".alert-success").remove();
    }, 5000 );
});
