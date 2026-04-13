$(document).on('click', '.undoBtn', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'This will revert stock and mark as initialized',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Undo'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/damaged-products/${id}/undo`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function (res) {

                    if (res.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
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