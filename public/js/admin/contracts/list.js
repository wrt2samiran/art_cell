//initializing galleries datatable
    var contract_table=$('#contract_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: $('#contracts_data_url').val(),
        columns: [
            { data: 'id', name: 'id' },
            { data: 'code', name: 'code'},
            { data: 'description', name: 'description'},
            { data: 'start_date', name: 'start_date'},
            { data: 'property.location', name: 'property.location'},
            { data: 'status', name: 'status'},
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

 //function to delete contract
 function delete_contract(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this contract!",
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
          contract_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Contract successfully deleted.', 'Success', {timeOut: 5000});
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

