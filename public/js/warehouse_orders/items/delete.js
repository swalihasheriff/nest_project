$(document).on('click', '.delete-item', function () {

    let itemId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'This item will be removed',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: `/warehouse-orders/${ORDER_ID}/items/${itemId}`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {

                    if (response.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: response.message,
                            timer: 1200,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); 
                        });

                    }
                },
                error: function () {
                    Swal.fire('Error', 'Unable to delete item', 'error');
                }
            });
        }
    });
});