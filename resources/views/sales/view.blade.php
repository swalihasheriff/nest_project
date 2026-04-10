@extends('layouts.app')
@section('title', 'Sales Details')

@section('content')
    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4 class="mb-0">Sales Details</h4>

            <div class="d-flex gap-2">

                @if($sale->status == 0)
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-circle"></i> Add Item
                    </button>
                @endif

                <!-- Back -->
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>

            </div>

        </div>

        <!-- Sale Info -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6">
                        <strong>Account:</strong>
                        {{ $sale->account->company_name ?? '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Order Number:</strong>
                        {{ $sale->reference ?? '-' }}
                    </div>

                </div>
            </div>
        </div>


        <!-- Items Table -->
        <div class="card">
            <div class="card-body">

                <table id="saleItemsTable" class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>SL No</th>
                            <th>Description</th>
                            <th>Barcode</th>
                            <th>SOH</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

        <!-- Totals -->
        <div class="card mt-3 {{ $hasItems ? '' : 'd-none' }}" id="totalsCard">
            <div class="card-body text-end">

                <p>Total (Exc GST): <span id="total_excl">0</span></p>
                <p>GST: <span id="gst">0</span></p>
                <p>Total: <span id="total">0</span></p>
                <p>Rounded: {{ $sale->round ?? 0 }}</p>
                <h5>
                    <strong>Grand Total:</strong>
                    <span id="grand_total" class="fw-bold">0</span>
                </h5>
            </div>
        </div>

        <!-- Finalize Button -->
        <div class="mt-3 text-end {{ ($hasItems && $sale->status == 0) ? '' : 'd-none' }}">
            <button class="btn btn-success" id="finalizeBtn" data-id="{{ $sale->id }}">
                <i class="bi bi-check-circle me-1"></i>Finalize
            </button>
        </div>

    </div>

    <div class="modal fade" id="addItemModal">
        <div class="modal-dialog">
            <form id="addItemForm">
                @csrf

                <input type="hidden" name="sale_id" value="{{ $sale->id }}">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Product -->
                        <div class="mb-2">
                            <label>Product *</label>
                            <select name="product_id" id="productSelect" class="form-select">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{  $product->description  }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-2">
                            <label>Quantity *</label>
                            <input type="number" name="quantity" class="form-control" min="1" step="1">
                        </div>

                        <!-- Discount -->
                        <div class="mb-2">
                            <label>Discount</label>
                            <input type="number" name="discount" class="form-control" value="0">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Save Item
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editItemModal">
        <div class="modal-dialog">
            <form id="editItemForm">
                @csrf

                <input type="hidden" name="id" id="edit_item_id">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Quantity</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-2">
                            <label>Quantity *</label>
                            <input type="number" name="quantity" id="edit_quantity" class="form-control" step="0.01">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Update
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection


@push('scripts')

    <script>
        const SALE_ITEMS_URL = "{{ route('sales.items', $sale->id) }}";
    </script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Your JS -->
    <script src="{{ asset('js/sales/view-datatable.js') }}"></script>
    <script src="{{ asset('js/sales/view-add.js') }}"></script>
    <script src="{{ asset('js/sales/view-edit.js') }}"></script>
    <script src="{{ asset('js/sales/view-delete.js') }}"></script>
    <script src="{{ asset('js/sales/finalize.js') }}"></script>

@endpush