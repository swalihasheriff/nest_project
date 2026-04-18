$(document).on('click', '.edit-receiving', function () {

    let id = $(this).data('id');

    $('#receiving_id').val(id);

    $('#invoice_number').val($(this).data('invoice'));
    
    $('#order_id').val($(this).data('order'));

    $('#editReceiveModal').modal('show');

});


$('#receiveForm').validate({

    rules: {
        received_by: {
            required: true
        },
        received_on: {
            required: true
        },
        invoice_number: {
            required: true
        }
    },

    messages: {
        received_by: {
            required: "Receiver name is required"
        },
        received_on: {
            required: "Received date is required",
            date: "Enter a valid date"
        },
        invoice_number: {
            required: "Invoice number is required"
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

        let receivingId = $('#receiving_id').val();

        $.ajax({

            url: `/goods-receiving/${receivingId}/update`,
            type: 'POST',

            data: {
                _token: $('input[name="_token"]').val(),
                received_by: $('#received_by').val(),
                received_on: $('#received_on').val(),
                invoice_number: $('#invoice_number').val(),
                receiving_id: $('#receiving_id').val(),
                order_id: $('#order_id').val()
            },

            beforeSend: function () {

                $('#receiveForm button[type="submit"]')
                    .prop('disabled', true)
                    .text('Updating...');

            },

            success: function (response) {

                if (response.status) {

                    $('#editReceiveModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        confirmButtonText: 'OK'
                    }).then(() => {

                        location.reload();

                    });

                }

            },

            error: function (xhr) {

                $('#receiveForm button[type="submit"]')
                    .prop('disabled', false)
                    .text('Save');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                if (xhr.status === 422 && xhr.responseJSON.errors) {

                    $.each(xhr.responseJSON.errors, function (key, value) {

                        let input = $('#receiveForm [name="' + key + '"]');

                        input.addClass('is-invalid');

                        input.after('<div class="invalid-feedback d-block">' + value[0] + '</div>');

                    });

                } else {

                    alert(xhr.responseJSON?.message || 'Something went wrong');

                }

            }

        });

        return false;

    }

});