@extends('layouts.app')
@section('title', 'Nest | Warehouse Orders')

@section('content')
    <div class="container-fluid py-3">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Warehouse Order List</h4>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newOrderModal">
                    <i class="bi bi-plus-circle me-1"></i> New Order
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-3">
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">

                    <!-- Supplier Filter -->
                    <div class="col-md-3">
                        <select id="filterSupplier" class="form-select">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-3">
                        <select id="filterStatus" class="form-select">
                            <option value="">All Orders</option>
                            <option value="1">Initiated</option>
                            <option value="2">Ordered</option>
                            <option value="3">Cancelled</option>
                            <option value="4">Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button id="applyFilter" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                    </div>

                    <!-- Reset Button -->
                    <div class="col-md-2">
                        <button id="resetFilter" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouse Orders Table -->
        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="warehouseOrdersTable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Supplier</th>
                                <th>Created At</th>
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

    <!-- New Order Modal -->
    <div class="modal fade" id="newOrderModal">
        <div class="modal-dialog">
            <form id="newOrderForm">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">New Warehouse Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label class="mb-1">Supplier</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="saveOrderBtn">
                           <i class="bi bi-save me-1"></i> Save Order
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')

    <script>
        const ORDER_STORE_URL = "{{ route('warehouse.orders.store') }}";
        const WAREHOUSE_ORDERS_URL = "{{ route('warehouse.orders.index') }}"
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


    <script src="{{ asset('js/warehouse_orders/datatables.js') }}"></script>
    <script src="{{ asset('js/warehouse_orders/add.js') }}"></script>

@endpush