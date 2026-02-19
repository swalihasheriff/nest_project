$(document).ready(function () {

    $('#supplierForm').validate({

        rules: {
            name: { required: true },
            phone: { required: true, digits: true, minlength: 10, maxlength: 10 },
            email: { required: true, email: true },
            address: { required: true },
            contact_person: { required: true }
        },

        messages: {
            name: { required: "Supplier name is required" },
            phone: {
                required: "Phone number is required",
                digits: "Only numbers allowed",
                minlength: "Phone number must be 10 digits",
                maxlength: "Phone number must be 10 digits"
            },
            email: {
                required: "Email is required",
                email: "Enter a valid email"
            },
            address: { required: "Address is required" },
            contact_person: { required: "Contact person is required" }
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
                url: SUPPLIER_STORE_URL,
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json',

                beforeSend: function () {
                    $('#supplierForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Saving...');
                },

                success: function (response) {
                    if (response.status) {
                        $('#editSupplierModal').modal('hide');
                       Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = "/suppliers";
                        });
                    }
                },

                error: function (xhr) {

                    $('#supplierForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save Supplier');

                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                        });
                    } else {
                        alert(xhr.responseJSON?.message || 'Something went wrong');
                    }
                }
            });
        }
    });

});