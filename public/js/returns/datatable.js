const salesTable = $('#returnTable').DataTable({

    processing: true,
    serverSide: true,

    ajax: {
        url: INDEX_URL,
        data: function (d) {
            d.type = 'items';
            d.goods_receiving_id = $('input[name="goods_receiving_id"]').val();
            d.manual_invoice_id = $('input[name="manual_invoice_id"]').val();
        }
    },

    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'description', name: 'description' },
        { data: 'barcode', name: 'barcode' },
        { data: 'ordered_qty', name: 'ordered_qty' },
        { data: 'return_qty', name: 'return_qty', orderable: false, searchable: false },
    ]

});