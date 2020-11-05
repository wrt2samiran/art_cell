var manage_spare_parts_ordered_table=$('#manage_spare_parts_ordered_table').DataTable({
    "responsive": true,
    "autoWidth": false,
    processing: true,
    serverSide: true,
    ajax: $('#manage_spare_parts_orders_data_url').val(),
    columns: [
        { data: 'id', name: 'id' },
        { data: 'user.name', name: 'user.name' },
        { data: 'ordered_spare_parts_count', name: 'ordered_spare_parts_count'},
        { data: 'total_amount', name: 'total_amount'},
        { data: 'curent_status', name: 'curent_status'},
        {data: 'action', name: 'action', orderable: false, searchable: false}
    ],
    order: [ [0, 'asc'] ],
});