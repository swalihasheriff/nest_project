$(document).on('click', '#finalizeBtn', function () {

    let saleId = $(this).data('id');

    Swal.fire({
        title: 'Finalize Sale?',
        text: 'You cannot edit after finalizing!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Finalize'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/sales/${saleId}/finalize`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function (res) {

                    if (res.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Finalized!',
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