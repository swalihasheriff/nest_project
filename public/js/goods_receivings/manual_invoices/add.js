$(document).ready(function () {

    $('#invoiceForm').validate({

        rules: {
            invoice_number: { required: true },
            invoice_date: { required: true },
            received_on: { required: true },
            received_by: { required: true },
            amount: { required: true, number: true }
        },

        messages: {
            invoice_number: { required: "Invoice number is required" },
            invoice_date: { required: "Invoice date is required" },
            received_on: { required: "Received date is required" },
            received_by: { required: "Received by is required" },
            amount: {
                required: "Amount is required",
                number: "Enter a valid amount"
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

            let formData = new FormData(form);

            $.ajax({
                url:  MANUAL_INVOICE_STORE_URL,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('#invoiceForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Saving...');
                },

                success: function (response) {

                    if (response.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success
                        }).then(() => {
                            window.location.reload();
                        });

                    }
                },

                error: function (xhr) {

                    $('#invoiceForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save');

                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (xhr.status === 422 && xhr.responseJSON.errors) {

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