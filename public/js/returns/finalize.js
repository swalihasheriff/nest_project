$(document).ready(function () {

    // ✅ trigger submit
    $('#finalizeReturnBtn').on('click', function () {
        $('#returnForm').submit();
    });

    $('#returnForm').validate({

        rules: {
            'items[][return_qty]': {
                number: true,
                min: 0
            }
        },

        messages: {
            'items[][return_qty]': {
                number: "Enter valid number",
                min: "Cannot be negative"
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

            let hasValue = false;
            let isValid = true;

            $('.return-qty').each(function () {

                let qty = parseInt($(this).val()) || 0;
                let max = parseInt($(this).attr('max')) || 0;

                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();

                if (qty > max) {
                    $(this).addClass('is-invalid');
                    $(this).after('<div class="invalid-feedback d-block">Return qty cannot exceed ordered qty</div>');
                    isValid = false;
                }

                if (qty > 0) {
                    hasValue = true;
                }
            });

            if (!isValid) return false;

            if (!hasValue) {
                Swal.fire('Warning', 'Enter at least one return quantity', 'warning');
                return false;
            }

            Swal.fire({
                title: 'Finalize Return?',
                text: 'This will update stock!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Finalize'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: STORE_URL,
                        type: 'POST',
                        data: $(form).serialize(),

                        beforeSend: function () {
                            $('#finalizeReturnBtn')
                                .prop('disabled', true)
                                .html('<i class="bi bi-hourglass"></i> Finalizing...');
                        },

                        success: function (res) {
                            if (res.status) {
                                Swal.fire('Success', res.message, 'success')
                                    .then(() => location.reload());
                            }
                        },

                        error: function (xhr) {

                            let msg = 'Something went wrong';

                            if (xhr.responseJSON?.errors) {
                                msg = Object.values(xhr.responseJSON.errors)[0][0];
                            } else if (xhr.responseJSON?.message) {
                                msg = xhr.responseJSON.message;
                            }

                            Swal.fire('Error', msg, 'error');

                            $('#finalizeReturnBtn')
                                .prop('disabled', false)
                                .html('<i class="bi bi-check-circle me-1"></i> Finalize Return');
                        }
                    });

                }
            });

            return false;
        }

    });

});