$(document).ready(function () {

    $('#returnsTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: LIST_URL,

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'invoice_number' },
            { data: 'type' },
            { data: 'date' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]

    });

});