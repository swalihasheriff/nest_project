$(document).ready(function () {

    // FINALIZE ORDER
   $(document).on('click', '#finalizeOrderBtn', function () {

    Swal.fire({
        title: 'Finalize order?',
        text: 'Do you want to finalize this order?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, finalize'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: FINALIZE_URL,
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },

            success: function (res) {
                if (res.status) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Finalized!',
                        text: 'Order has been finalized.',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); 
                    });

                } else {
                    Swal.fire('Error', 'Could not finalize order.', 'error');
                }
            },

            error: function (xhr) {
                Swal.fire(
                    'Error',
                    xhr.responseJSON?.message || 'Something went wrong',
                    'error'
                );
            }
        });
    });
});

    // UNDO FINALIZE
    $(document).on('click', '#undoFinalizeBtn', function () {
        $.ajax({
            url: UNDO_FINALIZE_URL,
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                Swal.fire('Reverted', 'Order is back to initiated.', 'info');
            }
        });
    });

    // CANCEL ORDER
    $(document).on('click', '#cancelOrderBtn', function () {
        Swal.fire({
            title: 'Cancel this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: CANCEL_ORDER_URL,
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    window.location.href = '/warehouse-orders';
                }
            });
        });
    });

    // DELIVER ORDER
    $(document).on('click', '#deliverOrderBtn', function () {
        Swal.fire({
            title: 'Mark as delivered?',
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Yes, deliver'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: DELIVER_ORDER_URL,
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    window.location.href = '/warehouse-orders';
                }
            });
        });
    });

});