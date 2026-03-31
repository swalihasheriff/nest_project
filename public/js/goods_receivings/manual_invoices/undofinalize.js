$(document).ready(function () {

    $('#undoFinalizeBtn').on('click', function () {

        Swal.fire({
            title: 'Undo Finalize?',
            text: 'Stock will be reverted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Undo',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: MANUAL_INVOICE_UNDO_URL,
                    type: 'POST',
                    data: {
                        invoice_id: $('input[name="manual_invoice_id"]').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function (res) {

                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Reverted',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },

                    error: function () {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                });

            }
        });
    });

});