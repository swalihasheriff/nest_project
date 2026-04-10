$(document).on('click', '.deleteSaleBtn', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'This sale will be deleted!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/sales/${id}/delete`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    if (res.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
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