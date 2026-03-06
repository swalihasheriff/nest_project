$(document).ready(function () {

    $('#newOrderForm').validate({
        rules: {
            supplier_id: {
                required: true
            }
        },
        messages: {
            supplier_id: {
                required: 'Please select a supplier'
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
                url: ORDER_STORE_URL,
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',

                beforeSend: function () {
                    $('#orderForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Saving...');
                },

                success: function (response) {
                    if (response.status) {
                        $('#newOrderModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = "/warehouse-orders";
                        });
                    }
                },

                error: function (xhr) {

                    $('#newOrderForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save Order');

                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (xhr.status === 422 && xhr.responseJSON.errors) {

                        $.each(xhr.responseJSON.errors, function (key, value) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after(
                                '<div class="invalid-feedback d-block">' + value[0] + '</div>'
                            );
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Something went wrong'
                        });
                    }
                }
            });
        }
    });
});
$('#newOrderModal').on('hidden.bs.modal', function () {
    $('#newOrderForm')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
});
