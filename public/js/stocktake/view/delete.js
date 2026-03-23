// Setup CSRF for all AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Delete Stocktake Item
$(document).on('click', '.deleteItemBtn', function () {

    let id = $(this).data('id'); 
    let url = STOCKTAKE_ITEM_DELETE_URL.replace(':id', id);

    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the item permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: url,
                type: 'POST',
                success: function (response) {
                    if (response.status) {
                        Swal.fire('Deleted!', response.message, 'success');
                        $('#worksheetItemsTable').DataTable().ajax.reload(); 
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            });
        }
    });

});