const accountsTable = $('#accountsTable').DataTable({

    processing: true,
    serverSide: true,

    ajax: {
        url: ACCOUNT_LIST_URL
    },

    columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'company_name', name: 'company_name' },
        { data: 'username', name: 'username' },
        { data: 'status', name: 'status', orderable: false, searchable: false },
        { data: 'created_at', name: 'created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ]

});