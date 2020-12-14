
var complaints_table=$('#complaints_table').DataTable({
    "responsive": true,
    "autoWidth": false,
    processing: true,
    serverSide: true,
    ajax: {
      url:$('#complaints_data_url').val(),
      data: function (d) {
         d.contract_id = $('#contract_id').val();
      }
    },
    columns: [

        { data: 'id', name: 'id' },
        { data: 'contract.code', name: 'contract.code'},
        { data: 'work_order_title', name: 'work_order_title', orderable: false, searchable: false},
        { 
          data: 'details', name: 'details'
        },
        { data: 'complaint_status.status_name', name: 'complaint_status.status_name' },
        { data: 'created_at', name: 'created_at' },
        {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
     order: [ [0, 'desc'] ],
    columnDefs: [
    {   "targets": [0],
        "visible": false,
        "searchable": false
    }],
    "drawCallback": function( settings ) {
        $.LoadingOverlay("hide");
    }

});



 //function to delete complaint
 function delete_complaint(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this complaint!",
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
          complaints_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Complaint successfully deleted.', 'Success', {timeOut: 5000});
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


/* code for contract wise complaints filter*/
$('#contract_id').select2({
  theme: 'bootstrap4',
  placeholder:'Filter by Contract'
});

$('#contract_id').on('change', function(e) {
  if(this.value!=''){
    $('#contract-filter-clear').show();
  }else{
    $('#contract-filter-clear').hide();
  }
  
  $.LoadingOverlay("show");
  complaints_table.draw();
});

$('#contract-filter-clear').on('click',function(){
  $('#contract_id').val("").change();
});
/***/