$(document).ready(function () {

    $('#undoReturnBtn').on('click', function () {

        let btn = $(this);

        Swal.fire({
            title: 'Undo Finalize?',
            text: 'Stock will be restored and editing enabled',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Undo'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: UNDO_URL,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),

                        goods_receiving_id: $('input[name="goods_receiving_id"]').val(),
                        manual_invoice_id: $('input[name="manual_invoice_id"]').val()
                    },

                    beforeSend: function () {
                        btn.prop('disabled', true)
                            .html('<i class="bi bi-hourglass"></i> Undoing...');
                    },

                    success: function (res) {

                        if (res.status) {
                            Swal.fire('Success', res.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', res.message || 'Undo failed', 'error');
                        }
                    },

                    error: function (xhr) {

                        let msg = 'Something went wrong';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }

                        Swal.fire('Error', msg, 'error');

                        btn.prop('disabled', false)
                            .html('<i class="bi bi-arrow-counterclockwise me-1"></i> Undo Finalize');
                    }
                });
            }
        });
    });

});