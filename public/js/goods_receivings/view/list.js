$('#addItemForm').validate({

    rules: {
        product_id: {
            required: true
        },
        quantity: {
            required: true,
            digits: true,
            min: 1
        }
    },

    messages: {
        product_id: {
            required: "Please select a product"
        },
        quantity: {
            required: "Quantity is required",
            digits: "Only numbers allowed",
            min: "Minimum quantity is 1"
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

        $.ajax({
            url: LIST_ITEM_URL,
            type: "POST",
            data: $(form).serialize(),
            dataType: "json",

            success: function (response) {

                let item = response.data;

                let rowNumber = $('#itemsTable tbody tr').length + 1;

                let row = `
                <tr>
                    <td>${rowNumber}</td>

                    <td>
                        ${item.description}
                        <input type="hidden" name="product_id[]" value="${item.product_id}">
                    </td>

                    <td>${item.barcode}</td>

                    <td>
                        <input type="number" class="form-control" name="price[]" value="${item.price}">
                    </td>

                    <td>${item.quantity}</td>

                    <td>
                        <input type="number" class="form-control" name="supplied_qty[]" value="${item.quantity}">
                    </td>
                </tr>
                `;

                $('#itemsTable tbody').append(row);

                $('#addItemModal').modal('hide');

                form.reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            },

            error: function (xhr) {

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                if (xhr.status === 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {

                        let input = $('[name="' + key + '"]');

                        input.addClass('is-invalid');

                        input.after(
                            '<div class="invalid-feedback d-block">' + value[0] + '</div>'
                        );

                    });

                }
            }
        });

    }

});

$('#finalizeBtn').click(function () {
    Swal.fire({
        title: "Finalize Receiving?",
        text: "Stock will be updated",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, finalize"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({

                url: FINALIZE_URL,
                type: "POST",
                data: $('#finalizeForm').serialize(),
                dataType: "json",

                success: function (response) {
                    if (response.status) {
                        Swal.fire("Finalized!", "", "success")
                             window.location.href ="/goods-receiving";
                    }
                }
            });
        }
    });
});

$('#undoFinalizeBtn').click(function () {

    Swal.fire({
        title: "Undo Finalize?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({

                url: UNDO_FINALIZE_URL,
                type: "POST",
                data: $('#finalizeForm').serialize(),
                dataType: "json",

                success: function (response) {
                    if (response.status) {
                        Swal.fire("Reverted!", "", "success")
                             window.location.href ="/goods-receiving";
                    }
                }
            });
        }
    });
});

