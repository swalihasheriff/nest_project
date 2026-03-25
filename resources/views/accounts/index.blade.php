@extends('layouts.app')
@section('title', 'Nest | Accounts')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Accounts</h4>

            <div class="d-flex gap-2">
                <a href="{{ route('accounts.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle me-1"></i> New Account
                </a>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body p-3">

                <div class="table-responsive">
                    <table id="accountsTable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Company Name</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th width="140">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')

    <script>
        const ACCOUNT_LIST_URL = "{{ route('accounts.index') }}";
        const ACCOUNT_DELETE_URL = "{{ route('accounts.destroy', ':id') }}";
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

    <script src="{{ asset('js/accounts/datatable.js') }}"></script>
    <script src="{{ asset('js/accounts/delete.js') }}"></script>

@endpush