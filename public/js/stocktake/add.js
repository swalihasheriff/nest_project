$(document).ready(function () {

    $('#newWorksheetForm').validate({
        rules: {
            worksheet_name: {
                required: true
            },
            type: {
                required: true
            }
        },
        messages: {
            worksheet_name: {
                required: 'Please enter worksheet name'
            },
            type: {
                required: 'Please select stocktake type'
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
                url: STOCKTAKE_STORE_URL,
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',

                beforeSend: function () {
                    $('#newWorksheetForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Saving...');
                },

                success: function (response) {
                    if (response.status) {

                        $('#newWorksheetModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    }
                },

                error: function (xhr) {

                    $('#newWorksheetForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save');

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


$('#newWorksheetModal').on('hidden.bs.modal', function () {

    $('#newWorksheetForm')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

});