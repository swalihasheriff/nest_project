$(function () {

    let columns = [

        { data: 'DT_RowIndex', orderable: false, searchable: false },

        { data: 'name' },
        { data: 'barcode' },
        { data: 'stock_on_hand' },
        { data: 'count' },
        { data: 'variance' },
        { data: 'notes' }

    ];
    if (STOCKTAKE_STATUS == 0) {
        columns.push({
            data: 'action',
            orderable: false,
            searchable: false
        });
    }

    $('#worksheetItemsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: STOCKTAKE_ITEMS_URL,
        columns: columns
    });

});