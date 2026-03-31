$(document).ready(function () {
    $('#addItemForm').validate({

        rules: {
            product_id: {
                required: true
            },
            quantity: {
                required: true,
                number: true,
                min: 1
            }
        },

        messages: {
            product_id: "Please select a product",
            quantity: "Enter valid quantity"
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
                url: MANUAL_INVOICE_ITEM_STORE_URL,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function (res) {

                    if (res.status) {

                        $('#addItemModal').modal('hide');

                        form.reset();

                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();


                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.success
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },

                error: function (xhr) {

                    let errors = xhr.responseJSON?.errors;

                    if (errors) {
                        let errorMsg = Object.values(errors)
                            .map(e => e[0])
                            .join('<br>');

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: errorMsg
                        });
                    } else {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                }
            });
        }
    });

});