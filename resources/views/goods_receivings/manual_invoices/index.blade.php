@extends('layouts.app')
@section('title', 'Nest | Manual Invoice')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Manual Invoice</h4>

            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#invoiceModal">
                <i class="bi bi-plus-circle me-1"></i> New Invoice
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-3">

                <div class="table-responsive">
                    <table id="invoicesTable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Invoice Number</th>
                                <th>Supplier</th>
                                <th>Received On</th>
                                <th>Received By</th>
                                <th>Invoice Date</th>
                                <th>Amount</th>
                                <th>Rounding</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <div class="modal fade" id="invoiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form id="invoiceForm">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">New Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Supplier</label>
                                <select name="supplier" id="supplier" class="form-select" >
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Invoice Number *</label>
                                <input type="text" name="invoice_number" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Invoice Date *</label>
                                <input type="date" name="invoice_date" class="form-control" max="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Received On</label>
                                <input type="date" name="received_on" class="form-control" max="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Received By</label>
                                <input type="text" name="received_by" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Amount *</label>
                                <input type="number" step="0.01" name="amount" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Rounding</label>
                                <input type="number" step="0.01" name="rounding" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="editInvoiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form id="editInvoiceForm">
                    @csrf

                    <input type="hidden" id="edit_invoice_id">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Invoice Number *</label>
                                <input type="text" name="invoice_number" id="edit_invoice_number" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Invoice Date *</label>
                                <input type="date" name="invoice_date" id="edit_invoice_date" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Received On *</label>
                                <input type="date" name="received_on" id="edit_received_on" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Received By *</label>
                                <input type="text" name="received_by" id="edit_received_by" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Amount *</label>
                                <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Rounding</label>
                                <input type="number" step="0.01" name="rounding" id="edit_rounding" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        const MANUAL_INVOICE_LIST_URL = "{{ route('manual-invoices.index') }}";
        const MANUAL_INVOICE_STORE_URL = "{{ route('manual-invoices.store') }}";
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/goods_receivings/manual_invoices/datatable.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/add.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/edit.js') }}"></script>

@endpush