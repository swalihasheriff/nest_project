@extends('layouts.app')

@section('content')

    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4 class="fw-semibold mb-0">Goods Receiving Details</h4>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="bi bi-plus-circle me-1"></i> Add Item
                </button>

                <a href="{{ route('goods.receiving.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <strong>Order ID:</strong>
                        <div>#{{ $receiving->warehouse_order_id }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Order Date:</strong>
                        <div>{{ $receiving->warehouseOrder->created_at->format('d-m-Y h:i A') }}</div>
                    </div>

                    <div class="col-md-4">
                        <strong>Supplier:</strong>
                        <div>{{ $receiving->supplier->name ?? '-' }}</div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form id="finalizeForm">
                    @csrf

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="itemsTable">

                            <thead class="table-light">
                                <tr>
                                    <th style="width:70px">Sl No</th>
                                    <th>Item Description</th>
                                    <th>Barcode</th>
                                    <th style="width:150px">Item Price</th>
                                    <th style="width:160px">Ordered Quantity</th>
                                    <th style="width:160px">Supplied Quantity</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($orderItems as $key => $item)

                                    <tr>

                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $item->product->description }}
                                            <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                        </td>

                                        <td>{{ $item->product->product_barcode1 }}</td>

                                        <td>
                                            <input type="number" class="form-control" name="price[]" value="{{ $item->price }}">
                                        </td>

                                        <td>{{ $item->quantity }}</td>
                                        

                                        <td>
                                            <input type="number" class="form-control" name="supplied_qty[]"
                                                value="{{ $item->supplied_quantity }}">
                                        </td>

                                    </tr>

                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    @if(!$receiving->finalized_at && count($orderItems) > 0)

                        <div class="card shadow-sm" id="finalizeSection">
                            <div class="card-body text-end">

                                <button id="finalizeBtn" type="button" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Finalize
                                </button>

                            </div>
                        </div>

                    @endif

                    @if($receiving->finalized_at)

                        <div id="finalizeActions" class="text-end mt-3">

                            <button id="undoFinalizeBtn" type="button" class="btn btn-warning">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Undo Finalize
                            </button>

                        </div>

                    @endif

                </form>

            </div>
        </div>

    </div>

    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">New Order Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <form id="addItemForm">
                        @csrf

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

                        <div class="mb-3">
                            <label class="form-label">Quantity</label>

                            <input type="number" name="quantity" class="form-control" min="1">
                        </div>

                    </form>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">

                    <button type="submit" form="addItemForm" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save
                    </button>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>

        const LIST_ITEM_URL = "{{ route('goods.receiving.list.item') }}";
        const FINALIZE_URL = "{{ route('goods.receiving.finalize', $receiving->id) }}";
        const UNDO_FINALIZE_URL = "{{ route('goods.receiving.undoFinalize', $receiving->id) }}";
        

    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="{{ asset('js/goods_receivings/view/list.js') }}"></script>

@endpush