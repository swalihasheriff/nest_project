@extends('layouts.app')
@section('title', 'Nest | Products')

@section('content')
    <div class="container-fluid py-3">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Product List</h4>

            <div class="d-flex gap-2">
                <!-- <button class="btn btn-outline-secondary">
                    <i class="bi bi-download me-1"></i> Import Products
                </button> -->

                <a href="{{ route('products.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add New Product
                </a>
            </div>
        </div>
        
        <!-- Product Table -->
        <div class="card shadow-sm">
            <div class="card-body p-3">

                <div class="table-responsive">
                    <table id="productsTable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Description</th>
                                <th>Supplier</th>
                                <th>CTN Barcode</th>
                                <th>Product Barcode </th>
                                <th>CTN Cost Price</th>
                                <th>CTN Sell Price</th>
                                <th>Margin (%)</th>
                                <th>Stock on Hand</th>
                                <th>Location</th>
                                <th>Updated At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="priceHistoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Price History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Price</th>
                                <th>Changed From</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="priceHistoryTable"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')

    <script>
        const PRODUCT_PRICE_HISTORY_URL = "{{ url('product-price-history') }}";
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('js/products/datatable.js') }}"></script>
    <script src="{{ asset('js/products/togglebtn.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/pricehistory.js') }}"></script>


@endpush