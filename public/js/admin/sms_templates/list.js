var sms_templates_table=$('#sms_templates_table').DataTable({
    "responsive": true,
    "autoWidth": false,
    processing: true,
    serverSide: true,
    ajax: $('#sms_templates_data_url').val(),
    columns: [
        { data: 'id', name: 'id' },
        { data: 'template_name', name: 'template_name'},
        { data: 'created_at', name: 'created_at' },
        {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
     order: [ [0, 'desc'] ],
    columnDefs: [
    {   "targets": [0],
        "visible": false,
        "searchable": false
    }],
    "language": {
        "url": (current_locale=="ar")?"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"
    }

});