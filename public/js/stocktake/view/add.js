$(document).ready(function () {

    $('#addItemForm').validate({

        rules: {
            barcode: {
                required: true
            },
            count: {
                required: true,
                number: true,
                min: 0
            }
        },

        messages: {
            barcode: {
                required: 'Please enter barcode'
            },
            count: {
                required: 'Please enter count'
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
                url: STOCKTAKE_ITEM_STORE_URL,
                type: 'POST',
                data: $(form).serialize(),

                beforeSend: function () {
                    $('#addItemForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Saving...');
                },

                success: function (res) {

                    $('#addItemForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save');

                    if (res.status) {

                        $('#addItemModal').modal('hide');

                        $('#addItemForm')[0].reset();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });

                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },

                error: function (xhr) {

                    $('#addItemForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save');

                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (xhr.status === 422) {

                        $.each(xhr.responseJSON.errors, function (key, value) {

                            let input = $('[name="' + key + '"]');

                            input.addClass('is-invalid');

                            input.after(
                                '<div class="invalid-feedback d-block">' + value[0] + '</div>'
                            );
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

});

