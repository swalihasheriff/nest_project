$(document).ready(function () {

    window.suppliersTable = $('#suppliersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/suppliers',

        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'phone' },
            { data: 'email' },
            { data: 'address', orderable: false },
            { data: 'contact_person' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

});
