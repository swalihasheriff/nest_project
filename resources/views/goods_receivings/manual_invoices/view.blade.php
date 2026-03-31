@extends('layouts.app')
@section('title', 'Manual Invoice Details')

@section('content')

    <div class="container-fluid py-3">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Manual Invoice Details</h4>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Item
                </button>

                <a href="{{ route('manual-invoices.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- Invoice Info --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body py-3">

                <div class="row g-3">

                    <div class="col-md-3">
                        <small class="text-muted">Invoice No</small>
                        <div class="fw-semibold">{{ $invoice->invoice_number }}</div>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted">Invoice Date</small>
                        <div class="fw-semibold">
                            {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted">Supplier</small>
                        <div class="fw-semibold">{{ $invoice->suppliers->name}}</div>
                    </div>


                    <div class="col-md-3">
                        <small class="text-muted">Received By</small>
                        <div class="fw-semibold">{{ $invoice->received_by }}</div>
                    </div>

                </div>

            </div>
        </div>

        {{-- Items Table --}}
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table id="invoiceItemsTable" class="table table-bordered table-striped align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Sl No</th>
                                <th>Item Description</th>
                                <th>Barcode</th>
                                <th>Item Price</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>


        <br>
        <div id="invoiceSummaryCard" class="card shadow-sm d-none">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span>Invoice Total</span>
                    <strong id="invoice_total">{{ number_format($invoice->amount, 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Rounding</span>
                    <strong id="rounding">{{ number_format($invoice->rounding, 2) }}</strong>
                </div>

                <hr class="my-2">

                <div class="d-flex justify-content-between">
                    <span class="fw-semibold">Total Amount</span>
                    <span class="fw-bold text-primary" id="final_total">
                        {{ number_format($invoice->amount + $invoice->rounding, 2) }}
                    </span>
                </div>

                <div id="balance_status" class="mt-2 text-end fw-semibold"></div>
            </div>

        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body text-end">

                @if($invoice->status == 1)
                    <button id="finalizeBtn" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Finalize
                    </button>
                @else
                    <button id="undoFinalizeBtn" class="btn btn-danger">
                        <i class="bi bi-arrow-counterclockwise"></i> Undo Finalize
                    </button>
                @endif

            </div>
        </div>

    </div>

    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="addItemForm">
                @csrf

                <input type="hidden" name="manual_invoice_id" value="{{ $invoice->id }}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select name="product_id" id="product_id" class="form-select">
                                <option value="">Select Product</option>

                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-barcode="{{ $product->product_barcode1 }}"
                                        data-price="{{ $product->ctn_cost_price }}">

                                        {{ $product->description }}

                                        @if($product->product_barcode1)
                                            — {{ $product->product_barcode1 }}
                                        @endif
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity *</label>
                            <input type="number" name="quantity" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Item</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editItemModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editItemForm">
                @csrf

                <input type="hidden" name="id" id="edit_item_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Quantity</label>
                            <input type="number" name="quantity" id="edit_qty" class="form-control">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                    </div>

                </div>
            </form>
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
        const INVOICE_ITEMS_URL = "{{ route('manual-invoices.items', $invoice->id) }}";
        const MANUAL_INVOICE_ITEM_STORE_URL = "{{ route('manual-invoices.items.store') }}";
        const MANUAL_INVOICE_ITEM_UPDATE_URL = "{{ route('manual-invoices.items.update') }}";
        const INVOICE_STATUS = {{ $invoice->status }};
        const MANUAL_INVOICE_FINALIZE_URL = "{{ route('manual-invoices.finalize', $invoice->id) }}";
        const MANUAL_INVOICE_UNDO_URL = "{{ route('manual-invoices.undoFinalize', $invoice->id) }}";
        const DELETE_ITEM_URL = "{{ route('manual-invoices.items.delete') }}";
        const PRODUCT_PRICE_HISTORY_URL = "{{ url('product-price-history') }}";        </script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/goods_receivings/manual_invoices/items_datatable.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/items_add.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/items_edit.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/finalize.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/undofinalize.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/items_delete.js') }}"></script>
    <script src="{{ asset('js/goods_receivings/manual_invoices/pricehistory.js') }}"></script>

@endpush