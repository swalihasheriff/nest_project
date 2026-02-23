$(document).ready(function () {

    $('#productForm').validate({

        rules: {
            description: { required: true },
            supplier_id: { required: true },

            ctn_barcode: { required: true },
            upc: { required: true },
            product_barcode1: { required: true },

            ctn_cost_price: { required: true, number: true },
            ctn_sell_price: { required: true, number: true },
            gst: { required: true, number: true },

            stock_on_hand: { required: true, digits: true },
            minimum_threshold: { required: true, digits: true },

            location: { required: true },
            uom: { required: true }
        },

        messages: {
            description: { required: "Description is required" },
            supplier_id: { required: "Please select a supplier" },

            ctn_barcode: { required: "CTN Barcode is required" },
            upc: { required: "UPC is required" },
            product_barcode1: { required: "Product Barcode 1 is required" },

            ctn_cost_price: {
                required: "Cost price is required",
                number: "Enter a valid number"
            },

            ctn_sell_price: {
                required: "Sell price is required",
                number: "Enter a valid number"
            },

            gst: {
                required: "GST is required",
                number: "Enter a valid GST value"
            },

            stock_on_hand: {
                required: "Stock on hand is required",
                digits: "Only whole numbers allowed"
            },

            minimum_threshold: {
                required: "Minimum threshold is required",
                digits: "Only whole numbers allowed"
            },

            location: { required: "Location is required" },
            uom: { required: "UOM is required" }
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

            let id = $('#edit_product_id').val();
            let formData = new FormData(form);

            $.ajax({
                url: `/products/${id}/update`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $('#productForm button[type="submit"]')
                        .prop('disabled', true)
                        .text('Updating...');
                },

                 success: function (response) {
                    if (response.status) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.success,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = "/products";
                        });
                    }
                },


                error: function (xhr) {

                    $('#productForm button[type="submit"]')
                        .prop('disabled', false)
                        .text('Save Product');

                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    if (xhr.status === 422 && xhr.responseJSON.errors) {

                        $.each(xhr.responseJSON.errors, function (key, value) {

                            let input = $('[name="' + key + '"]');

                            if (key.includes('.')) {
                                input = $('[name="' + key.replace('.', '\\.') + '"]');
                            }

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