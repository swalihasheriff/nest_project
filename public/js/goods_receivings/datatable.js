const goodsTable = $(TABLE_ID).DataTable({

    processing: true,
    serverSide: true,

    ajax: {
        url: INDEX_URL
    },

    columns: [
        {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
        {data:'supplier', name:'supplier'},
        {data:'received_on', name:'received_on'},
        {data:'received_by', name:'received_by'},
        {data:'status', name:'status', orderable:false, searchable:false},
        {data:'action', name:'action', orderable:false, searchable:false},
    ]

});