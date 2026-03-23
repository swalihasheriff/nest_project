$(document).on('click', '.editItemBtn', function () {

    let itemId = $(this).data('id');
    let count = $(this).data('count');
    let notes = $(this).data('notes');

    $('#edit_item_id').val(itemId);
    $('#edit_count').val(count);
    $('#edit_notes').val(notes);

    $('#editItemModal').modal('show');
});

$('#editItemForm').validate({

    rules: {
        count: {
            required: true,
            number: true,
            min: 0
        },
        notes: {
            maxlength: 255
        }
    },

    messages: {
        count: {
            required: 'Please enter count',
            number: 'Count must be a number',
            min: 'Count cannot be negative'
        },
        notes: {
            maxlength: 'Notes cannot exceed 255 characters'
        }
    },

    errorElement: 'div',
    errorClass: 'invalid-feedback',

    errorPlacement: function(error, element){
        error.insertAfter(element);
    },

    highlight: function(element){
        $(element).addClass('is-invalid');
    },

    unhighlight: function(element){
        $(element).removeClass('is-invalid');
    },

    submitHandler: function(form){

        let itemId = $('#edit_item_id').val();
        let url = STOCKTAKE_ITEM_UPDATE_URL.replace(':id', itemId);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                count: $('#edit_count').val(),
                notes: $('#edit_notes').val()
            },

            beforeSend: function(){
                $('#editItemForm button[type="submit"]')
                    .prop('disabled', true)
                    .text('Updating...');
            },

            success: function(res){
                $('#editItemForm button[type="submit"]')
                    .prop('disabled', false)
                    .text('Update');

                if(res.status){
                    $('#editItemModal').modal('hide');
                    $('#worksheetItemsTable').DataTable().ajax.reload();
                    Swal.fire('Success', res.message, 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },

            error: function(xhr){
                $('#editItemForm button[type="submit"]')
                    .prop('disabled', false)
                    .text('Update');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                if(xhr.status === 422 && xhr.responseJSON.errors){
                    $.each(xhr.responseJSON.errors, function(key, value){
                        let input = $('#editItemForm [name="'+key+'"]');
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback d-block">'+value[0]+'</div>');
                    });
                } else {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            }

        });

        return false;
    }

});

$('#editItemModal').on('hidden.bs.modal', function(){
    $('#editItemForm')[0].reset();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
});