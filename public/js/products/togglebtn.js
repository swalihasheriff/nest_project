$(document).on('change', '.product-status-toggle', function () {

    var $checkbox = $(this);
    var productId = $checkbox.data('id');
    var status = $checkbox.is(':checked') ? 1 : 0;
    var $badge = $('.status-badge[data-id="' + productId + '"]');

    var actionText = status === 1 ? 'enable' : 'disable';

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to " + actionText + " this product?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, ' + actionText + ' it!',
        cancelButtonText: 'Cancel'
    }).then(function (result) {

        if (result.isConfirmed) {
            updateProductStatus(productId, status, $badge);
        } else {
            // ❌ revert toggle if cancelled
            $checkbox.prop('checked', !status);
        }

    });
});


function updateProductStatus(productId, status, $badge) {

    var originalText = $badge.text();
    $badge.text('Updating...');

    $.ajax({
        url: '/products/' + productId + '/toggle-status',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            status: status
        },

        success: function (response) {

            if (response.success) {

                if (status === 1) {
                    $badge
                        .removeClass('bg-secondary')
                        .addClass('bg-success')
                        .text('Active');
                } else {
                    $badge
                        .removeClass('bg-success')
                        .addClass('bg-secondary')
                        .text('Inactive');
                }
            } else {

                alert('Failed to update status');
                $badge.text(originalText);
                // Revert checkbox if update failed
                $badge.text(originalText);
                $checkbox.prop('checked', !status);
            }
        },

        error: function () {
            alert('Something went wrong');
            $badge.text(originalText);
            $checkbox.prop('checked', !status);
        }
    });
}