$(document).ready(function () {

    $('#accountForm').validate({

        rules: {
            company_name: { required: true },
            email: { required: true, email: true },
            username: { required: true },
            password: { required: true, minlength: 6 },
            price_group: { required: true }
        },

        messages: {
            company_name: { required: "Company name is required" },
            email: {
                required: "Email is required",
                email: "Enter a valid email"
            },
            username: { required: "Username is required" },
            password: {
                required: "Password is required",
                minlength: "Minimum 6 characters"
            },
            price_group: { required: "Please select price group" }
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
                url: ACCOUNT_STORE_URL,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('#accountForm button[type="submit"]')
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
                            window.location.href = "/accounts";
                        });

                    }

                },

                error: function (xhr) {

                    $('#accountForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save Account');

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