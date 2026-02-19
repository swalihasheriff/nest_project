$(document).on('change', '.supplier-status-toggle', function () {
    var $checkbox = $(this);
    var supplierId = $checkbox.data('id');
    var status = $checkbox.is(':checked') ? 1 : 0;
    var $badge = $('.status-badge[data-id="' + supplierId + '"]');

    // Determine action text dynamically
    var actionText = status === 1 ? 'enable' : 'disable';

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to " + actionText + " this supplier?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, ' + actionText + ' it!',
        cancelButtonText: 'Cancel'
    }).then(function (result) {
        if (result.isConfirmed) {
            // User confirmed, update status
            updateSupplierStatus(supplierId, status, $badge);
        } else {
            // User canceled, revert checkbox
            $checkbox.prop('checked', !status);
        }
    });
});

function updateSupplierStatus(supplierId, status, $badge) {
    var originalText = $badge.text();
    $badge.text('Updating...'); // optional loading text

    $.ajax({
        url: '/suppliers/' + supplierId + '/toggle-status',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            status: status
        },
        success: function (response) {
            if (response.success) {
                if (status === 1) {
                    $badge.removeClass('bg-secondary').addClass('bg-success').text('Active');
                } else {
                    $badge.removeClass('bg-success').addClass('bg-secondary').text('Inactive');
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
