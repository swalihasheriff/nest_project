const salesTable = $('#salesTable').DataTable({

    processing: true,
    serverSide: true,

    ajax: {
        url: SALES_INDEX_URL,
        data: function (d) {
            d.status = $('#filterStatus').val(); 
        }
    },

    columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'invoice_no' },
        { data: 'account' },
        { data: 'status', orderable: false, searchable: false },
        { data: 'reference' },
        { data: 'type' },
        { data: 'delivery_date' },
        { data: 'total_amount' },
        { data: 'created_at' },
        { data: 'finalized_at' },
        { data: 'action', orderable: false, searchable: false },
    ]

});

$('#filterStatus').change(function () {
    salesTable.draw();
});