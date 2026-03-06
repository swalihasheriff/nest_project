$(document).on('click', '.edit-item', function () {
    $('#edit_item_id').val($(this).data('id'));
    $('#edit_quantity').val($(this).data('qty'));
    $('#edit_product_name').val($(this).data('name'));
    $('#editItemModal').modal('show');
});
$('#editItemForm').validate({
    rules: {
        edit_quantity: {
            required: true,
            digits: true,
            min: 1
        }
    },
    messages: {
        edit_quantity: {
            required: "Quantity is required",
            digits: "Only numbers allowed",
            min: "Quantity must be at least 1"
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

        let itemId = $('#edit_item_id').val();

        $.ajax({
            url: `/warehouse-orders/${ORDER_ID}/items/${itemId}/update`,
            type: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                edit_quantity: $('#edit_quantity').val()
            },
            beforeSend: function () {
                $('#editItemForm button[type="submit"]')
                    .prop('disabled', true)
                    .text('Updating...');
            },

            success: function (response) {
                if (response.status) {
                    $('#editItemModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload()
                    });
                }
            },

            error: function (xhr) {

                $('#editItemForm button[type="submit"]')
                    .prop('disabled', false)
                    .text('Update Supplier');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        let input = $('#editItemForm [name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                    });
                } else {
                    alert(xhr.responseJSON?.message || 'Something went wrong');
                }
            }
        });

        return false;
    }
});

