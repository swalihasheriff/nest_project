$(document).on('click', '#fillDataBtn', function () {

    Swal.fire({
        title: 'Load all products?',
        text: "This will add all products to this stocktake",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, load'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: STOCKTAKE_FILL_URL,
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
                            confirmButtonText: 'OK'
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