var manage_shared_service_orders_table=$('#manage_shared_service_orders_table').DataTable({
    "responsive": true,
    "autoWidth": false,
    processing: true,
    serverSide: true,
    ajax: $('#manage_shared_service_orders_data_url').val(),
    columns: [
        { data: 'id', name: 'id' },
        { data: 'user.name', name: 'user.name' },
        { data: 'ordered_shared_services_count', name: 'ordered_shared_services_count'},
        { data: 'total_amount', name: 'total_amount'},
        { data: 'curent_status', name: 'curent_status'},
        {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
    order: [ [0, 'asc'] ],
});