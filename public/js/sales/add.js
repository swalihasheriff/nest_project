$(document).ready(function () {

    $('#saleForm').validate({

        rules: {
            account_id: { required: true },
            type: { required: true },
            delivery_date: { date: true },
            reference: { maxlength: 255 }
        },

        messages: {
            account_id: { required: "Account is required" },
            type: { required: "Type is required" }
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
                url: SALES_STORE_URL,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                beforeSend: function () {
                    $('#saleForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Saving...');
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

                    $('#saleForm button[type="submit"]')
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