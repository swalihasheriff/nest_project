$(document).ready(function () {

    $('#addItemForm').validate({

        rules: {
            product_id: { required: true },
            quantity: { required: true, digits: true },
        },

        messages: {
            product_id: { required: "Select product" },
            quantity: { required: "Enter quantity" }
        },

        submitHandler: function (form) {

            let formData = new FormData(form);

            let $btn = $(form).find('button[type="submit"]');

            $btn.prop('disabled', true).html('Saving...');

            $.ajax({
                url: '/sales/items/store',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function (res) {

                    if (res.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                },

                error: function (xhr) {

                    Swal.fire(
                        'Error',
                        xhr.responseJSON?.message || 'Something went wrong',
                        'error'
                    );

                },

                complete: function () {
                    $btn.prop('disabled', false).html('Add Item');
                }
            });
        }
    });

});