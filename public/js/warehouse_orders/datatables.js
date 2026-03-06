
let table = $('#warehouseOrdersTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: WAREHOUSE_ORDERS_URL,
        data: function (d) {
            d.supplier_id = $('#filterSupplier').val();
            d.status = $('#filterStatus').val();
        }
    },
    columns: [
        { data: 'DT_RowIndex', orderable: false },
        { data: 'supplier' },
        { data: 'created_at' },
        { data: 'order_status' },
        { data: 'action', orderable: false, searchable: false }
    ]
});

/* Apply filter */
$('#applyFilter').on('click', function () {
    table.ajax.reload();
});

/* Reset filter */
$('#resetFilter').on('click', function () {
    $('#filterSupplier').val('');
    $('#filterStatus').val('');
    table.ajax.reload();
});