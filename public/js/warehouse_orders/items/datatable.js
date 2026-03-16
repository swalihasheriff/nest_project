window.orderItemsTable = $('#orderItemsTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: ORDER_ITEMS_LIST_URL,
        dataSrc: function (json) {

            const $select = $('select[name="product_id"]');
            $select.find('option').show();
            json.data.forEach(function (item) {
                $select.find('option[value="' + item.product_id + '"]').hide();
            });

            return json.data;
        }
    },

    columns: [
        { data: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'description', searchable: true },
        { data: 'barcode', searchable: true },
        { data: 'price', searchable: true },
        { data: 'quantity', searchable: true },
        { data: 'total' },
        { data: 'action', orderable: false }
    ],               
});