$(document).ready(function () {

    $(document).on('click', '.editItemBtn', function () {

        let id = $(this).data('id');
        let qty = $(this).data('qty');

        $('#edit_item_id').val(id);
        $('#edit_quantity').val(qty);

        $('#editItemModal').modal('show');
    });

    $('#editItemForm').validate({

        rules: {
            quantity: { required: true, number: true, min: 1 }
        },

        messages: {
            quantity: {
                required: "Quantity is required",
                number: "Must be a number",
                min: "Minimum quantity is 1"
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

            let id = $('#edit_item_id').val();
            let formData = new FormData(form);

            $.ajax({
                url: `/sales/items/${id}/update`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('#editItemForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Updating...');
                },

                success: function (response) {

                    if (response.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        }).then(() => {
                            window.location.reload(); // same as your style
                        });
                    }
                },

                error: function (xhr) {

                    $('#editItemForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Update');

                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (xhr.status === 422 && xhr.responseJSON.errors) {

                        $.each(xhr.responseJSON.errors, function (key, value) {

                            let input = $('#edit_' + key);

                            input.addClass('is-invalid');
                            input.after(
                                '<div class="invalid-feedback d-block">' +
                                value[0] +
                                '</div>'
                            );
                        });

                    } else {

                        Swal.fire(
                            'Error',
                            xhr.responseJSON?.message || 'Something went wrong',
                            'error'
                        );
                    }
                }
            });
        }
    });

});