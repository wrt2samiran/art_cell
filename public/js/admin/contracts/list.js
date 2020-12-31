//initializing galleries datatable
    var contract_table=$('#contract_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: {
          url:$('#contracts_data_url').val(),
          
          data: function (d) {
            d.contract_status_id = $('#contract_status_id').val();
            d.daterange = $('#daterange').val();  
            
        }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'code', name: 'code'},
            { data: 'title', name: 'title'},
            { data: 'start_date', name: 'start_date'},
            { data: 'end_date', name: 'end_date'},
            { data: 'creation_complete', name: 'creation_complete'},
            { data: 'contract_status.status_name', name: 'contract_status.status_name',orderable: false, searchable: false},
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

 $('.status-filter').select2({
  theme: 'bootstrap4',
  placeholder:'Filter by Status type'
});
$('#contract_status_id').on('change', function(e) {
  if(this.value!=''){
    $('#status-filter-clear').show();
  }else{
    $('#status-filter-clear').hide();
  }
  
  $.LoadingOverlay("show");
  contract_table.draw();
});
$('#status-filter-clear').on('click',function(){
  $('#contract_status_id').val("").change();
});

 $('#contract_duration').daterangepicker({
  autoUpdateInput: false,
  timePicker: false,
  timePicker24Hour: true,
  timePickerIncrement: 1,
  startDate: moment().startOf('hour'),
  //endDate: moment().startOf('hour').add(24, 'hour'),
  locale: {
      format: 'YYYY-MM-DD'
  }
}, dateRangeCallback);
$('#contract_duration-filter-clear').on('click',function(){
  $('#contract_duration').val("");
  $('#contract_duration-filter-clear').hide();
  $('#daterange').val('');
  $.LoadingOverlay("show");
    contract_table.draw();
    $('#contract_duration').daterangepicker({
      autoUpdateInput: false,
      timePicker: false,
      timePicker24Hour: true,
      timePickerIncrement: 1,
      startDate: moment().startOf('hour'),
      //endDate: moment().startOf('hour').add(24, 'hour'),
      locale: {
          format: 'YYYY-MM-DD'
      }
    }, dateRangeCallback);
    // console.log(A);
    
    
});

function dateRangeCallback(start_date, end_date){
  $('#daterange').val(start_date.format('YYYY-MM-DD') + '_' + end_date.format('YYYY-MM-DD'));
    
  $.LoadingOverlay("show");
    contract_table.draw();
    $('#contract_duration-filter-clear').show();
    

  
  $('#contract_duration').val(start_date.format('YYYY-MM-DD') + '-' + end_date.format('YYYY-MM-DD'));
  
}


