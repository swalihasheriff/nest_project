@extends('layouts.app')
@section('title', 'Damaged Products')

@section('content')

    <div class="container-fluid py-3">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Damaged Products</h4>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDamagedModal">
                <i class="bi bi-plus-circle me-1"></i> Add Damaged
            </button>

        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">

                    <div class="col-md-3">
                        <select id="filterStatus" class="form-select">
                            <option value="1">Finalized</option>
                            <option value="0">Initialized</option>

                        </select>
                    </div>

                    <!-- Apply -->
                    <div class="col-md-2">
                        <button id="applyFilter" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                    </div>

                    <!-- Reset -->
                    <div class="col-md-2">
                        <button id="resetFilter" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card shadow-sm">
            <div class="card-body">

                <table id="damagedTable" class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Item Description</th>
                            <th>Barcode</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

    </div>
    <div class="modal fade" id="addDamagedModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="addDamagedForm">
                @csrf

                <div class="modal-content">

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">Add Damaged Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Product -->
                        <div class="mb-3">
                            <label class="form-label">Product *</label>
                            <select name="product_id" id="productSelect" class="form-select">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->description }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-danger error_product_id"></small>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label class="form-label">Quantity *</label>
                            <input type="number" name="quantity" class="form-control" min="1">
                            <small class="text-danger error_quantity"></small>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Finalize
                        </button>

                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <form id="editItemForm">
                @csrf

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Edit Quantity</h5>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id" id="edit_id">

                        <div class="mb-3">
                            <label>Quantity</label>
                            <input type="number" name="quantity" id="edit_quantity" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Finalize
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        const DAMAGED_INDEX_URL = "{{ route('damaged-products.index') }}";
        const DAMAGED_STORE_URL = "{{ route('damaged-products.store') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/damaged/datatable.js') }}"></script>
    <script src="{{ asset('js/damaged/add.js') }}"></script>
    <script src="{{ asset('js/damaged/undo.js') }}"></script>
    <script src="{{ asset('js/damaged/edit.js') }}"></script>
    <script src="{{ asset('js/damaged/delete.js') }}"></script>

@endpush