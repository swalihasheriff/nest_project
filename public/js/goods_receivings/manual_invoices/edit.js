$(document).ready(function () {


    $(document).on('click', '.editInvoiceBtn', function () {

        let invoice = $(this).data('invoice');

        $('#edit_invoice_id').val(invoice.id);
        $('#edit_invoice_number').val(invoice.invoice_number);
        $('#edit_invoice_date').val(invoice.invoice_date);
        $('#edit_received_on').val(invoice.received_on);
        $('#edit_received_by').val(invoice.received_by);
        $('#edit_amount').val(invoice.amount);
        $('#edit_rounding').val(invoice.rounding);

        $('#editInvoiceModal').modal('show');
    });



    $('#editInvoiceForm').validate({

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
                number: "Enter valid amount"
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

            let id = $('#edit_invoice_id').val();
            let formData = new FormData(form);

            $.ajax({
                url: `/manual-invoices/${id}/update`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('#editInvoiceForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Updating...');
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

                    $('#editInvoiceForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Update');

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