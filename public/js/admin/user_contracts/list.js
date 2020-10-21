//initializing galleries datatable
    var user_contracts_table=$('#user_contracts_table').DataTable({
        "responsive": true,
        "autoWidth": false,
        processing: true,
        serverSide: true,
        ajax: {
          url:$('#user_contracts_data_url').val(),
          
          data: function (d) {
            d.contract_status_id = $('#contract_status_id').val();
            d.daterange = $('#daterange').val();
            
        }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'code', name: 'code'},
            { data: 'description', name: 'description'},
            { data: 'start_date', name: 'start_date'},
            { data: 'end_date', name: 'end_date'},
            { data: 'property.location', name: 'property.location'},
            { data: 'contract_status.status_name', name: 'contract_status.status_name'},
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
  user_contracts_table.draw();
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
    user_contracts_table.draw();
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
    user_contracts_table.draw();
    $('#contract_duration-filter-clear').show();
    
  $('#contract_duration').val(start_date.format('YYYY-MM-DD') + '-' + end_date.format('YYYY-MM-DD'));
  
}

