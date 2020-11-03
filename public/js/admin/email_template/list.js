//initializing galleries datatable
var email_table=$('#email_table').DataTable({
    "responsive": true,
    "autoWidth": false,
    processing: true,
    serverSide: true,
    ajax: $('#email_data_url').val(),
    columns: [
        { data: 'id', name: 'id' },
        { data: 'template_name', name: 'template_name'},
        { data: 'slug', name: 'slug'},
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

//function to delete gallery
// function delete_message(id){
//   // alert(id);
//   // return false;
//   swal({
//   title: "Are you sure?",
//   text: "Once deleted, you will not be able to recover this message!",
//   icon: "warning",
//   buttons: true,
//   dangerMode: true,
//   })
//   .then((willDelete) => {
//     if (willDelete) {

//       $.LoadingOverlay("show");
//       $.ajax({
//         url: "https://www.demoyourprojects.com/cmms/public/admin/email/delete/"+id,
//         type: "GET",
//         data:{ 
//           "_token": $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (data) {
//           email_table.ajax.reload(null, false);
//           $.LoadingOverlay("hide");
//           toastr.success('Emailtemplate successfully deleted.', 'Success', {timeOut: 5000});
//         },
//         error: function(jqXHR, textStatus, errorThrown) {
//            $.LoadingOverlay("hide");
//            var response=jqXHR.responseJSON;
//            var status=jqXHR.status;
//            if(status=='404'){
//             toastr.error('Invalid URL', 'Error', {timeOut: 5000});
//            }else{
//              toastr.error('Internal server error.', 'Error', {timeOut: 5000});
//            }
//         }
//      });

//      email_table.ajax.reload(null, false);
//     } 
//   });

//  }



//function to change status of gallery
// function change_status(url,activate_or_deactivate){
// swal({
// title: "Are you sure?",
// text: "You want to "+activate_or_deactivate+" the message.",
// icon: "warning",
// buttons: true,
// dangerMode: true,
// })
// .then((confirm) => {
// if (confirm) {
//   $.LoadingOverlay("show");
//   $.ajax({
//     url: url,
//     type: "GET",
//     data:{},
//     success: function (data) {
//       $.LoadingOverlay("hide");
//       toastr.success('Status successfully updated.', 'Success', {timeOut: 5000});
//     },
//     error: function(jqXHR, textStatus, errorThrown) {
//        $.LoadingOverlay("hide");
//        var response=jqXHR.responseJSON;
//        var status=jqXHR.status;
//        if(status=='404'){
//         toastr.error('Invalid URL', 'Error', {timeOut: 5000});
//        }else{
//          toastr.error('Internal server error.', 'Error', {timeOut: 5000});
//        }
//     }
//  });

//   window.LaravelDataTables["message_table"].ajax.reload();
// // window.location.href=url;
// } 
// });

// }

$("document").ready(function(){
setTimeout(function(){
    $(".alert-success").remove();
}, 5000 );
});