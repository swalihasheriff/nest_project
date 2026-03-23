$(document).on('click', '#finalizeBtn', function () {

    Swal.fire({
        title: 'Are you sure?',
        text: "This will finalize the stocktake",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, finalize it!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: STOCKTAKE_FINALIZE_URL,
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

