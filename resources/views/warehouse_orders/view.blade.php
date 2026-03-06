@extends('layouts.app')
@section('title', 'Warehouse Order Details')

@section('content')
    <div class="container-fluid py-3">

        <!--HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Warehouse Order Details</h4>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Item
                </button>

                <!-- <button class="btn btn-outline-secondary" id="autoFillBtn">
                                <i class="bi bi-magic me-1"></i> Auto Fill Order
                            </button> -->
                <button type="button" id="autoFillBtn" class="btn btn-outline-secondary">
                    <i class="bi bi-magic me-1"></i> Auto Fill Order
                </button>

                <a href="{{ route('warehouse.orders.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <!--ORDER INFO -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <strong>Order ID:</strong>
                        <div>#{{ $order->id }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Order Date:</strong>
                        <div>{{ $order->created_at->format('d-m-Y h:i A') }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Supplier:</strong>
                        <div>{{ $order->supplier->name ?? '-' }}</div>
                    </div>

                </div>
            </div>
        </div>

        <!--TOTAL ORDER -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-0">
                    <strong>Total Order Amount:</strong> 
                    <span class="fw-bold text-success ms-2" id="totalOrderAmount">
                        <strong>₹ 0.00</strong>
                    </span>
                </h6>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="orderItemsTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sl No</th>
                                <th>Item Description</th>
                                <th>Barcode</th>
                                <th>Item Price</th>
                                <th>Ordered Quantity</th>
                                <th>Item Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No items added yet
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        @if($order->status == 1 && $ordereditems > 0)
            <!-- FINALIZE SECTION -->
            <div class="card shadow-sm" id="finalizeSection">
                <div class="card-body text-end">
                    <button id="finalizeOrderBtn" type="button" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Finalize Order
                    </button>
                </div>
            </div>
        @endif

        <!-- FINALIZE ACTIONS -->
        @if($order->status == 2)
            <div id="finalizeActions" class="text-end mt-3">
                <button id="undoFinalizeBtn" class="btn btn-warning me-2">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Undo Finalize
                </button>

                <button id="cancelOrderBtn" class="btn btn-danger me-2">
                    <i class="bi bi-x-circle me-1"></i> Cancel Order
                </button>

                <button id="deliverOrderBtn" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Deliver Order
                </button>
            </div>
        @endif

    </div>

    <!-- ADD ITEM MODAL-->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="addItemForm">
                @csrf
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Order Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- Product -->
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-select">
                                <option value="">Select Product</option>

                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->description }}
                                        @if($product->product_barcode1)
                                            — {{ $product->product_barcode1 }}
                                        @endif
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="1">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Save Item
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <!-- EDIT ITEM MODAL -->
    <div class="modal fade" id="editItemModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editItemForm">
                @csrf
                <input type="hidden" id="edit_item_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Order Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Quantity -->
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="edit_quantity" id="edit_quantity" class="form-control" min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update Order Quantity
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const ORDER_ID = {{ $order->id }};
        const ORDER_ITEMS_LIST_URL = "{{ route('warehouse.orders.items.index', $order->id) }}";
        const ORDER_ITEM_STORE_URL = "{{ route('warehouse.orders.items.store', $order->id) }}";
        const ORDER_TOTAL_URL = "{{ route('warehouse.orders.total', $order->id) }}";
        const FINALIZE_URL = "{{ route('warehouse.orders.finalize', $order->id) }}";
        const UNDO_FINALIZE_URL = "{{ route('warehouse.orders.undoFinalize', $order->id) }}";
        const CANCEL_ORDER_URL = "{{ route('warehouse.orders.cancel', $order->id) }}";
        const DELIVER_ORDER_URL = "{{ route('warehouse.orders.deliver', $order->id) }}";
        const AUTO_FILL_URL = "{{ route('warehouse.orders.items.autoFill', $order->id) }}";

    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


    <script src="{{ asset('js/warehouse_orders/items/datatable.js') }}"></script>
    <script src="{{ asset('js/warehouse_orders/items/add.js') }}"></script>
    <script src="{{ asset('js/warehouse_orders/items/delete.js') }}"></script>
    <script src="{{ asset('js/warehouse_orders/finalizebtn.js') }}"></script>
    <script src="{{ asset('js/warehouse_orders/items/edit.js') }}"></script>
    <script src="{{ asset('js/warehouse_orders/items/autofill.js') }}"></script>

@endpush