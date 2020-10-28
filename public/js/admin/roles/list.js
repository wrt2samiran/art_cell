//initializing galleries datatable
    var roles_table=$('#roles_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#roles_data_url').val(),
            data: function (d) {
                d.user_type_id = $('#user_type_id').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'role_name', name: 'role_name'},
            { data: 'user_type.name', name: 'user_type.name'},
            { data: 'creator.name', name: 'creator.name' },
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
        }

    });

 //function to delete role
 function delete_role(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this group!",
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
          roles_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Group successfully deleted.', 'Success', {timeOut: 5000});
        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;

         
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }else if(status=='400'){
            if(response.message){
               toastr.error(response.message, 'Error', {timeOut: 5000});
             }else{
               toastr.error('Server error', 'Error', {timeOut: 5000});
             }
           
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
  text: "You want to "+activate_or_deactivate+" the role.",
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
          roles_table.ajax.reload(null, false);
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



$('.user_type-filter').select2({
  theme: 'bootstrap4',
  placeholder:'Filter By User type'
});


$('#user_type_id').on('change', function(e) {
    if(this.value!=''){
      $('#user_type-filter-clear').show();
    }else{
      $('#user_type-filter-clear').hide();
    }
    
    $.LoadingOverlay("show");
    roles_table.draw();
});


$('#user_type-filter-clear').on('click',function(){
  $('#user_type_id').val("").change();
});