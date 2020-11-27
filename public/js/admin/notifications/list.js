
var notifications_table=$('#notifications_table').DataTable({
    "responsive": true,
    "autoWidth": false,
    processing: true,
    serverSide: true,
    ajax: {
      url:$('#notifications_data_url').val(),
    },
    columns: [

        { data: 'id', name: 'id' },
        { 
          data: 'message', name: 'message'
        },
        { data: 'created_at', name: 'created_at' },
        
    ],
    order: [ [0, 'asc'] ],
    columnDefs: [
    {   "targets": [0],
        "visible": false,
        "searchable": false
    }],


});