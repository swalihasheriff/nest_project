$(document).ready(function () {


    $(document).on('click', '.editItemBtn', function () {
        let id = $(this).data('id');
        let price = $(this).data('price');
        let qty = $(this).data('qty');

        $('#edit_item_id').val(id);
        $('#edit_price').val(price);
        $('#edit_qty').val(qty);


        let modal = new bootstrap.Modal(document.getElementById('editItemModal'));
        modal.show();
    });

});
$('#editItemForm').validate({

    rules: {
        price: {
            required: true,
            number: true,
            min: 0
        },
        quantity: {
            required: true,
            number: true,
            min: 1
        }
    },

    messages: {
        price: {
            required: "Price is required",
            number: "Enter a valid number",
            min: "Price cannot be negative"
        },
        quantity: {
            required: "Quantity is required",
            number: "Enter a valid number",
            min: "Quantity must be at least 1"
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
            url: MANUAL_INVOICE_ITEM_UPDATE_URL,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (res) {

                if (res.status) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: res.success,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                }
            },

            error: function (xhr) {

                let errors = xhr.responseJSON?.errors;

                if (errors) {
                    let errorMsg = Object.values(errors).map(e => e[0]).join('<br>');

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errorMsg
                    });
                }
            }
        });
    }
});