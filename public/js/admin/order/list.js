//initializing galleries datatable
var order_table=$('#order_table').DataTable({
    "responsive": true,
    "autoWidth": false,
    processing: true,
    serverSide: true,
    ajax: {
        url: $('#labour_data_url').val(),
        data: function (d) {
            d.role_id = $('#role_id').val();
        }
    },
    columns: [
        { data: 'id', name: 'id' },
        { data: 'job_no', name: 'job_no' },
        { data: 'first_name', name: 'first_name'},
        { data: 'customer_contact', name: 'customer_contact' },
        { data: 'is_active', name: 'is_active',orderable: false },
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

//function to delete user
function delete_user(url){
swal({
title: "Are you sure?",
text: "Once deleted, you will not be able to recover this user!",
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

      order_table.ajax.reload(null, false);
      $.LoadingOverlay("hide");
      toastr.success('User successfully deleted.', 'Success', {timeOut: 5000});
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
text: "You want to "+activate_or_deactivate+" the user.",
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
      order_table.ajax.reload(null, false);
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



$('.role-filter').select2({
theme: 'bootstrap4',
placeholder:'Filter By Group'
});


$('#role_id').on('change', function(e) {
if(this.value!=''){
  $('#role-filter-clear').show();
}else{
  $('#role-filter-clear').hide();
}

$.LoadingOverlay("show");
order_table.draw();
});


$('#role-filter-clear').on('click',function(){
$('#role_id').val("").change();
});


