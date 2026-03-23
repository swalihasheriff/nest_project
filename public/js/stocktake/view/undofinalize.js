$(document).on('click', '#undoFinalizeBtn', function () {

    Swal.fire({
        title: 'Undo Finalize?',
        text: "You can edit again after this",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, undo'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: STOCKTAKE_UNDO_FINALIZE_URL,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function (res) {

                    if (res.status) {
                        Swal.fire('Success', res.message, 'success')
                            .then(() => {
                                location.reload();
                            });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }

                },

                error: function () {
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            });

        }
    });
});