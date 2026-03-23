@extends('layouts.app')
@section('title', 'Nest | Stocktake')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Stocktake</h4>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newWorksheetModal">
                    <i class="bi bi-plus-circle me-1"></i> New Worksheet
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-3">

                <div class="table-responsive">
                    <table id="stocktakeTable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <div class="modal fade" id="newWorksheetModal">
        <div class="modal-dialog">
            <form id="newWorksheetForm">
                @csrf

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">New Worksheet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="mb-1">Worksheet Name</label>
                            <input type="text" name="worksheet_name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="mb-1">Type</label>
                            <select name="type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="1">Full Stocktake</option>
                                <option value="2">Selective Stocktake</option>
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Save
                        </button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        const STOCKTAKE_LIST_URL = "{{ route('stocktake.index') }}";
        const STOCKTAKE_STORE_URL = "{{ route('stocktake.store') }}";
        const STOCKTAKE_DELETE_URL = "{{ route('stocktake.destroy', ':id') }}";
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/stocktake/datatable.js') }}"></script>
    <script src="{{ asset('js/stocktake/add.js') }}"></script>
    <script src="{{ asset('js/stocktake/delete.js') }}"></script>

@endpush