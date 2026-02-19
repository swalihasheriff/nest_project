@extends('layouts.app')
@section('title', 'Nest | Suppliers')
@section('content')
    <div class="container-fluid py-3">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Supplier List</h4>

            <button  id="addSupplierBtn"class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                <i class="bi bi-plus-circle me-1"></i> Add New Supplier
            </button>

        </div>
        <!-- Supplier Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table id="suppliersTable" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:80px; white-space:nowrap;">Sl No</th>
                                <th>Name</th>
                                <th style="width:140px;">Phone</th>
                                <th style="width:220px;">Email</th>
                                <th style="width:250px;">Address</th>
                                <th style="width:180px;">Contact Person</th>
                                <th style="width:120px;">Status</th>
                                <th style="width:140px;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="supplierForm">
                    @csrf

                    <input type="hidden" id="supplier_id" name="supplier_id">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter supplier name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" placeholder="Enter phone number">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" class="form-control" name="contact_person"
                                placeholder="Enter contact person">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" rows="3" name="address"
                                placeholder="Enter address"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Update Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editSupplierForm">
                    @csrf

                    <input type="hidden" id="edit_supplier_id" name="supplier_id">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Supplier Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="edit_contact_person" name="contact_person">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" rows="3" id="edit_address" name="address"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update Supplier
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    const SUPPLIER_STORE_URL = "{{ route('suppliers.store') }}";
</script>
@push('scripts')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">


    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/add.js') }}"></script>
    <script src="{{ asset('js/edit.js') }}"></script>
@endpush