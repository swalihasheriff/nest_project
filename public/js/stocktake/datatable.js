

$(function () {

    $('#stocktakeTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: STOCKTAKE_LIST_URL,

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },

            { data: 'worksheet_name', name: 'worksheet_name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status' },
            
            { data: 'action', name: 'action', orderable:false, searchable:false }
        ]
    });

});