$(document).ready(function () {

    $(document).on('click', '.editSaleBtn', function () {

        let sale = $(this).data('sale');

        $('#edit_sale_id').val(sale.id);

        $('#edit_account_id').val(sale.account_id);
        $('#edit_account_hidden').val(sale.account_id);

        $('#edit_reference').val(sale.reference);
        $('#edit_delivery_date').val(sale.delivery_date);
        $('#edit_delivery_address').val(sale.delivery_address);
        $('#edit_round').val(sale.round ?? 0);
        $('#edit_payment_mode').val(sale.payment_mode);

        $('#editSaleModal').modal('show');
    });


    $('#editSaleForm').validate({

        rules: {
            reference: { required: true, maxlength: 255 },
            delivery_date: { required: true, date: true },
            payment_mode: { required: true }
        },
        messages: {
            reference: { required: "Reference is required" },
            delivery_date: { required: "Date is required" },
            payment_mode: { required: "Select payment mode" }
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

            let id = $('#edit_sale_id').val();
            let formData = new FormData(form);

            $.ajax({
                url: `/sales/${id}/update`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('#editSaleForm button[type="submit"]')
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
                            window.location.reload();
                        });

                    }
                },

                error: function (xhr) {

                    $('#editSaleForm button[type="submit"]')
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