$(document).on('click', '.editBtn', function () {

    $('#edit_id').val($(this).data('id'));
    $('#edit_quantity').val($(this).data('qty'));

    $('#editModal').modal('show');
});


$('#editItemForm').validate({

    rules: {
        quantity: {
            required: true,
            digits: true,
            min: 1
        }
    },

    messages: {
        quantity: {
            required: "Quantity is required",
            digits: "Only numbers allowed",
            min: "Minimum 1 required"
        }
    },

    errorElement: 'div',
    errorClass: 'invalid-feedback',

    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },

    highlight: function (element) {
        $(element).addClass('is-invalid');
    },

    unhighlight: function (element) {
        $(element).removeClass('is-invalid');
    },

    submitHandler: function (form) {

        let id = $('#edit_id').val();

        $.ajax({
            url: `/damaged-products/update/${id}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                quantity: $('#edit_quantity').val(),
                id: $('#edit_id').val()
            },

            beforeSend: function () {
                $('#editItemForm button[type="submit"]')
                    .prop('disabled', true)
                    .text('Updating...');
            },

            success: function (res) {

                if (res.status) {

                    $('#editModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message
                    }).then(() => {
                        location.reload();
                    });
                }
            },

            error: function (xhr) {

                $('#editItemForm button[type="submit"]')
                    .prop('disabled', false)
                    .text('Update');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                if (xhr.status === 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {

                        let input = $('[name="' + key + '"]');

                        input.addClass('is-invalid');
                        input.after(
                            '<div class="invalid-feedback d-block">' +
                            value[0] +
                            '</div>'
                        );
                    });

                } else {

                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            }
        });

        return false;
    }
});