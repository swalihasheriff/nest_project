$(document).ready(function () {

    $('#finalizeBtn').on('click', function () {

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will finalize the invoice and update stock!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Finalize',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: MANUAL_INVOICE_FINALIZE_URL,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function (res) {

                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Finalized',
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