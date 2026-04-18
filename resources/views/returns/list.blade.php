@extends('layouts.app')
@section('title', 'Returns List')

@section('content')

<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Returns</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table id="returnsTable" class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Sl No</th>
                            <th>Invoice No</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>

</div>

@endsection

@push('scripts')

<script>
    const LIST_URL = "{{ route('returns.list') }}";
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script src="{{ asset('js/returns/list-datatable.js') }}"></script>

@endpush