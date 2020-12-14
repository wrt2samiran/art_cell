//initializing galleries datatable
    var quotations_table=$('#quotations_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#quotations_data_url').val(),
            data: function (d) {
                d.service = $('#service').val();
                d.property_type = $('#property_type').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'first_name', name: 'first_name'},
            { data: 'email', name: 'email'},
            { data: 'services', name: 'services',orderable: false,searchable: false },
            { data: 'property_types', name: 'property_types',orderable: false,searchable: false },
            { data: 'contract_duration', name: 'contract_duration',orderable: false },
            { data: 'status.status_name', name: 'status.status_name' },
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

 //function to delete quotation
 function delete_quotation(url){
  swal({
  title: "Are you sure?",
  text: "Once deleted, you will not be able to recover this quotation!",
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
          quotations_table.ajax.reload(null, false);
          $.LoadingOverlay("hide");
          toastr.success('Quotation successfully deleted.', 'Success', {timeOut: 5000});
        },
        error: function(jqXHR, textStatus, errorThrown) {
           $.LoadingOverlay("hide");
           var response=jqXHR.responseJSON;
           var status=jqXHR.status;
           if(status=='404'){
            toastr.error('Invalid URL', 'Error', {timeOut: 5000});
           }
           else if(status=='403'){
              toastr.error('You do not have permission to perform this action.', 'Error', {timeOut: 5000});
           }
           else{
             toastr.error('Internal server error.', 'Error', {timeOut: 5000});
           }
        }
     });

     
    } 
  });

 }

$('.service-filter').select2({
  theme: 'bootstrap4',
  placeholder:'Filter by service type'
});
$('.property-type-filter').select2({
  theme: 'bootstrap4',
  placeholder:'Filter by property type'
});

$('#service').on('change', function(e) {
    if(this.value!=''){
      $('#service-filter-clear').show();
    }else{
      $('#service-filter-clear').hide();
    }
    
    $.LoadingOverlay("show");
    quotations_table.draw();
});
$('#property_type').on('change', function(e) {
    
    if(this.value!=''){
      $('#property-type-filter-clear').show();
    }else{
      $('#property-type-filter-clear').hide();
    }
    $.LoadingOverlay("show");
    quotations_table.draw();
});

$('#service-filter-clear').on('click',function(){
  $('#service').val("").change();
});

$('#property-type-filter-clear').on('click',function(){
  $('#property_type').val("").change();
});



