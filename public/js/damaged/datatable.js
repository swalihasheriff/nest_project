$(document).ready(function () {

    const table = $('#damagedTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: {
            url: DAMAGED_INDEX_URL,

            data: function (d) {
                d.status = $('#filterStatus').val();
            },

            dataSrc: function (json) {

                const $select = $('#productSelect');

                if ($select.length) {

                    $select.find('option').prop('disabled', false);

                    json.data.forEach(function (item) {

                        if (item.status == 1) {
                            $select.find('option[value="' + item.product_id + '"]')
                                .prop('disabled', true);
                        }

                    });

                    $select.trigger('change.select2');
                }

                return json.data;
            }
        },

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'item_description' },
            { data: 'barcode' },
            { data: 'quantity' },
            { data: 'action', orderable: false, searchable: false }
        ]

    });

    $('#applyFilter').click(function () {
        table.draw();
    });

    $('#resetFilter').click(function () {
        $('#filterStatus').val('1');
        table.draw();
    });

});