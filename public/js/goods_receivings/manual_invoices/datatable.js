const invoicesTable = $('#invoicesTable').DataTable({

    processing: true,
    serverSide: true,

    ajax: {
        url: MANUAL_INVOICE_LIST_URL
    },

    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'invoice_number', name: 'invoice_number' },
        { data: 'supplier', name: 'supplier' },
        { data: 'received_on', name: 'received_on' },
        { data: 'received_by', name: 'received_by' },
        { data: 'invoice_date', name: 'invoice_date' },
        { data: 'amount', name: 'amount' },
        { data: 'rounding', name: 'rounding' },

        { data: 'status', name: 'status', orderable: false, searchable: false },

        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ]

});