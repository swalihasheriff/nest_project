$(document).on('click', '.deleteBtn', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'This will delete the record',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/damaged-products/${id}/delete`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function (res) {

                    if (res.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: res.message
                        }).then(() => {
                            location.reload();
                        });

                    }
                }
            });

        }
    });

});