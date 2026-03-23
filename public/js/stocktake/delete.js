$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
}); 

$(document).on('click', '.deleteBtn', function () {

    let id = $(this).data('id');
    let url = STOCKTAKE_DELETE_URL.replace(':id', id);

    Swal.fire({
        title: 'Are you sure?',
        text: "This will delete the worksheet",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: url,
                type: "POST",

                success: function (response) {
                    if (response.status) {
                        Swal.fire('Deleted!', response.message, 'success');
                        $('#stocktakeTable').DataTable().ajax.reload();
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