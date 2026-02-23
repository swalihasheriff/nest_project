
$(document).ready(function () {
    $('#productsTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        autoWidth: true,
        responsive: true,

        ajax: '/products',

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },

            { data: 'description', searchable: true },
            { data: 'supplier', searchable: true },
            { data: 'ctn_barcode', searchable: true },
            { data: 'product_barcode1', searchable: true },
            { data: 'ctn_cost_price' },
            { data: 'ctn_sell_price' },

            {
                data: null,
                name: 'margin',
                render: function (data) {
                    let cost = parseFloat(data.ctn_cost_price) || 0;
                    let sell = parseFloat(data.ctn_sell_price) || 0;
                    if (cost === 0) return '0%';
                    return (((sell - cost) / cost) * 100).toFixed(2) + '%';
                }
            },

            { data: 'stock_on_hand' },
            { data: 'location' },
            { data: 'updated_at' },

            { data: 'status', orderable: false, searchable: false },

            { data: 'action', orderable: false, searchable: false }
        ]
    });
});

