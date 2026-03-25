$(document).on('click', '.deleteAccountBtn', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "This account will be deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: ACCOUNT_DELETE_URL.replace(':id', id),
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {

                    if (response.status) {

                        Swal.fire('Deleted!', response.message, 'success');

                        $('#accountsTable').DataTable().ajax.reload();

                    } else {

                        Swal.fire('Error!', response.message, 'error');

                    }

                }
            });

        }

    });
});