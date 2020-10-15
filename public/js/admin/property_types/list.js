//initializing galleries datatable
    var property_types_table=$('#property_types_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: $('#property_types_data_url').val(),
        columns: [
            { data: 'id', name: 'id' },
            { data: 'type_name', name: 'type_name'},
            { data: 'description', name: 'description' },
            { data: 'is_active', name: 'is_active',orderable: false },
            { data: 'created_at', name: 'created_at' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
         order: [ [0, 'desc'] ],
        columnDefs: [
        {   "targets": [0],
            "visible": false,
            "searchable": false
        }]

    });

 //function to delete property type
 function delete_property_type(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this property type!",
  icon: "warning",
  buttons: true,
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
          property_types_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Property type successfully deleted.', 'Success', {timeOut: 5000});
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
  title: "Are you sure?",
  text: "You want to "+activate_or_deactivate+" the property type.",
  icon: "warning",
  buttons: true,
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
          property_types_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Status successfully updated.', 'Success', {timeOut: 5000});
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