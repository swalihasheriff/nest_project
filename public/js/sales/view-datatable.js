$(document).ready(function () {

    window.saleItemsTable = $('#saleItemsTable').DataTable({
        processing: true,
        serverSide: true,

        ajax: {
            url: SALE_ITEMS_URL,

            dataSrc: function (json) {

                const $select = $('#productSelect');
                $select.find('option').show();
                json.data.forEach(function (item) {
                    $select.find('option[value="' + item.product_id + '"]').hide();
                });

                return json.data;
            }
        },

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'description' },
            { data: 'barcode' },
            { data: 'soh' },
            { data: 'price' },
            { data: 'quantity' },
            { data: 'discount' },
            { data: 'total' },
            { data: 'action', orderable: false, searchable: false }
        ],

        drawCallback: function (settings) {

            let res = settings.json;

            if (res && res.data.length > 0) {

                $('#totalsCard').removeClass('d-none');

                $('#finalizeBtnWrapper').removeClass('d-none');

                $('#total').text(res.total);
                $('#gst').text(res.gst);
                $('#total_excl').text(res.total_excl);
                $('#grand_total').text(res.grand_total);

            } else {

                $('#totalsCard').addClass('d-none');
                $('#finalizeBtnWrapper').addClass('d-none');
            }
        }
    });

});