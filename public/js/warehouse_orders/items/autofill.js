$(document).on('click', '#autoFillBtn', function (e) {
    e.preventDefault();

    Swal.fire({
        title: 'Auto Fill Order?',
        text: 'Items will be added based on stock and shelf capacity',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Auto Fill'
    }).then((result) => {

        if (!result.isConfirmed) return;

        $.ajax({
            url: AUTO_FILL_URL,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                Swal.fire('Done', res.message, 'success');
                orderItemsTable.ajax.reload();
            },
            error: function (xhr) {
                Swal.fire(
                    'Error',
                    xhr.responseJSON?.message || 'Something went wrong',
                    'error'
                );
            }
        });

    });

    return false; // 🔥 ABSOLUTE STOP
});