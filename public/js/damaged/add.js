$(document).ready(function () {

    $('#addDamagedModal').on('shown.bs.modal', function () {

        $('#productSelect').select2({
            placeholder: "Select Product",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addDamagedModal')
        });

    });
    
    $('#addDamagedForm').validate({

        rules: {
            product_id: { required: true },
            quantity: {
                required: true,
                number: true,
                min: 1
            }
        },

        messages: {
            product_id: { required: "Please select a product" },
            quantity: {
                required: "Quantity is required",
                number: "Enter a valid number",
                min: "Minimum 1 required"
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
                url: DAMAGED_STORE_URL,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('#addDamagedForm button[type="submit"]')
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
                            location.reload();
                        });

                    }
                },

                error: function (xhr) {

                    $('#addDamagedForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Finalize');

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