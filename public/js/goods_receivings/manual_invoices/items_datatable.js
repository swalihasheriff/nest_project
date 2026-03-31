const table = $('#invoiceItemsTable').DataTable({

    processing: true,
    serverSide: true,

    ajax: {
        url: INVOICE_ITEMS_URL
    },

    columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'item_description' },
        { data: 'barcode' },
        { data: 'price' },
        { data: 'quantity' },
        { data: 'action' }
    ],

    drawCallback: function () {

        let api = this.api();
        let rowCount = api.rows().count();

        if (rowCount > 0) {
            $('#invoiceSummaryCard').removeClass('d-none');
            $('#finalizeBtn').show();
        } else {
            $('#invoiceSummaryCard').addClass('d-none');
            $('#finalizeBtn').hide();
        }

        let total = 0;

        api.rows().every(function () {
            let data = this.data();

            let price = parseFloat(data.price.toString().replace(/,/g, '')) || 0;
            let qty = parseFloat(data.quantity) || 0;

            total += price * qty;
        });

        let finalTotal = parseFloat(
            $('#final_total').text().replace(/,/g, '')
        ) || 0;

        if (Math.abs(total - finalTotal) < 0.01) {
            $('#balance_status')
                .removeClass('text-danger')
                .addClass('text-success')
                .text('Invoice Balanced');

            $('#finalizeBtn').removeClass('d-none');
        } else {
            $('#balance_status')
                .removeClass('text-success')
                .addClass('text-danger')
                .text('Invoice is not balanced');

            $('#finalizeBtn').addClass('d-none');
        }
    }
});