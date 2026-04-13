@extends('layouts.app')
@section('title', 'Sales')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Sales List</h4>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newSaleModal">
                    <i class="bi bi-plus-circle me-1"></i> New Sale
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-3">
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <select id="filterStatus" class="form-select">
                            <option value="">All Sales</option>
                            <option value="0">Initialized</option>
                            <option value="1">Finalized</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="salesTable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Invoice Number</th>
                                <th>Account</th>
                                <th>Status</th>
                                <th>Reference</th>
                                <th>Type</th>
                                <th>Delivery Date</th>
                                <th>Total</th>
                                <th>Created</th>
                                <th>Finalized</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="newSaleModal">
        <div class="modal-dialog">
            <form id="saleForm">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">New Sale</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-2">
                            <label>Account</label>
                            <select name="account_id" class="form-select">
                                <option value="">Select Account</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">
                                        {{ $acc->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Type -->
                        <div class="mb-2">
                            <label>Type</label>
                            <select name="type" class="form-select">
                                <option value="">Select Type</option>
                                <option value="0">Pickup</option>
                                <option value="1">Delivery</option>
                            </select>
                        </div>

                        <!-- Delivery Date -->
                        <div class="mb-2">
                            <label>Delivery Date</label>
                            <input type="date" name="delivery_date" class="form-control">
                        </div>

                        <!-- Reference -->
                        <div class="mb-2">
                            <label>Reference</label>
                            <input type="text" name="reference" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Save
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editSaleModal">
        <div class="modal-dialog">
            <form id="editSaleForm">
                @csrf

                <input type="hidden" id="edit_sale_id" name="sale_id">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Sales</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Account (Disabled) -->
                        <div class="mb-2">
                            <label>Account *</label>
                            <select id="edit_account_id" name="account_id" class="form-select" disabled>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">
                                        {{ $acc->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Reference -->
                        <div class="mb-2">
                            <label>Reference *</label>
                            <input type="text" id="edit_reference" name="reference" class="form-control">
                        </div>

                        <!-- Delivery Date -->
                        <div class="mb-2">
                            <label>Pickup/Delivery Date *</label>
                            <input type="date" id="edit_delivery_date" name="delivery_date" class="form-control">
                        </div>

                        <!-- Delivery Address -->
                        <div class="mb-2">
                            <label>Delivery Address</label>
                            <textarea id="edit_delivery_address" name="delivery_address" class="form-control"></textarea>
                        </div>

                        <!-- Round -->
                        <div class="mb-2">
                            <label>Round</label>
                            <input type="number" id="edit_round" name="round" class="form-control" value="0">
                        </div>

                        <div class="mb-2">
                            <label>Payment Mode *</label>
                            <input type="text" id="edit_payment_mode" name="payment_mode" class="form-control">
                        </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Update
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
        const SALES_INDEX_URL = "{{ route('sales.index') }}";
        const SALES_STORE_URL = "{{ route('sales.store') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('js/sales/datatable.js') }}"></script>
    <script src="{{ asset('js/sales/add.js') }}"></script>
    <script src="{{ asset('js/sales/edit.js') }}"></script>
    <script src="{{ asset('js/sales/delete.js') }}"></script>

@endpush