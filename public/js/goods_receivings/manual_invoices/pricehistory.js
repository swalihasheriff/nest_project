$(document).on('click', '.priceHistoryBtn', function () {

    let productId = $(this).data('product-id');

    $.ajax({
        url: PRODUCT_PRICE_HISTORY_URL + '/' + productId,
        type: 'GET',

        success: function (res) {

            let rows = '';

            if (res.data.length === 0) {
                rows = '<tr><td colspan="3" class="text-center">No history found</td></tr>';
            } else {
                res.data.forEach(function (item) {
                    rows += `
                        <tr>
                            <td>₹${item.price}</td>
                            <td>${item.changed_from ?? '-'}</td>
                            <td>${new Date(item.created_at).toLocaleDateString()}</td>
                        </tr>
                    `;
                });
            }

            $('#priceHistoryTable').html(rows);

            $('#priceHistoryModal').modal('show');
        }
    });
});