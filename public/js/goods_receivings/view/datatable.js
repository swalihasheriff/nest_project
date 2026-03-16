$(document).ready(function () {

    $('#itemsTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: "{{ route('goods.receiving.show', $receiving->id) }}",

        columns: [
            {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data:'description', name:'description'},
            {data:'barcode', name:'barcode'},
            {data:'price', name:'price', orderable:false, searchable:false},
            {data:'quantity', name:'quantity'},
            {data:'supplied_qty', name:'supplied_qty', orderable:false, searchable:false},
        ]

    });

});