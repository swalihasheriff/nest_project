$(document).ready(function () {

    $('#loginForm').validate({

        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            }
        },

        messages: {
            email: {
                required: "Email is required",
                email: "Enter a valid email address"
            },
            password: {
                required: "Password is required",
                minlength: "Password must be at least 6 characters"
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

            // reset previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('#serverError').addClass('d-none').text('');

            $.ajax({
                url: loginUrl,
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',

                beforeSend: function () {
                    $('#sign_in_btn')
                        .prop('disabled', true)
                        .html('<span class="spinner-border spinner-border-sm me-2"></span>Please wait...');
                },

                success: function (response) {
                    if (response.success) {
                        window.location.href = response.redirect;
                    }
                },

                error: function (xhr) {

                    $('#sign_in_btn')
                        .prop('disabled', false)
                        .html('SIGN IN');

                    // Laravel validation errors
                    if (xhr.status === 422 && xhr.responseJSON.errors) {

                        $.each(xhr.responseJSON.errors, function (key, value) {
                            let input = $('#' + key);
                            input.addClass('is-invalid');

                            input.after(
                                '<div class="invalid-feedback d-block">' +
                                value[0] +
                                '</div>'
                            );
                        });

                    } else {
                        $('#serverError')
                            .removeClass('d-none')
                            .text(xhr.responseJSON?.message || 'Login failed');
                    }
                }
            });
        }
    });

});
