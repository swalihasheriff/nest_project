$(document).ready(function () {
    refreshOrderTotal()

    $('#addItemForm').validate({

        rules: {
            product_id: { required: true },
            quantity: { required: true, digits: true, min: 1 }
        },

        messages: {
            product_id: {
                required: "Please select a product"
            },
            quantity: {
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

            $.ajax({
                url: ORDER_ITEM_STORE_URL,
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',

                beforeSend: function () {
                    $('#addItemForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Saving...');
                },

                success: function (response) {

                    if (response.status) {

                        $('#addItemModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Added',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // ✅ reload page HERE
                        });

                    }
                },

                error: function (xhr) {

                    $('#addItemForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save Item');

                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                        });
                    } else {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                }
            });
        }
    });
});
$('#addItemModal').on('hidden.bs.modal', function () {
    $('#addItemForm')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    $('#addItemForm button[type="submit"]').prop('disabled', false).text('Save Order');
});
function refreshOrderTotal() {
    $.get(ORDER_TOTAL_URL, function (response) {
        $('#totalOrderAmount').text('₹ ' + response.total);
    });
}