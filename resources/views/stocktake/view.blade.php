@extends('layouts.app')
@section('title', 'Nest | Worksheet Items')

@section('content')

    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold mb-0">Worksheet Items</h4>

            <div class="d-flex gap-2 align-items-center">
                @if( $stocktake->status == 0)
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="bi bi-plus-circle me-1"></i> New Item
                    </button>
                @endif

                @if($stocktake->type == 1 && $stocktake->status == 0)
                    <button id="fillDataBtn" class="btn btn-outline-success">
                        <i class="bi bi-list-check me-1"></i> Fill Data
                    </button>
                @endif

                <a href="{{ route('stocktake.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>

            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">

                <table id="worksheetItemsTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Name</th>
                            <th>Barcode</th>
                            <th>Stock on Hand</th>
                            <th>Count</th>
                            <th>Variance</th>
                            <th>Notes</th>
                            @if($stocktake->status == 0)
                                <th width="120">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-end">

                @if($stocktake->status == 0 && $stocktake->items->count() > 0)
                    <button id="finalizeBtn" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Finalize
                    </button>
                @endif

                <!-- @if($stocktake->status == 1)
                                <button id="undoFinalizeBtn" class="btn btn-warning">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Undo Finalize
                                </button>
                            @endif -->

            </div>
        </div>

    </div>
    <div class="modal fade" id="addItemModal">
        <div class="modal-dialog">
            <form id="addItemForm">
                @csrf

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="mb-1">Product</label>

                            <select name="barcode" class="form-control select2 w-100">
                                <option value="">Select Product</option>

                                @foreach($products as $product)
                                    @php
                                        $barcode = $product->product_barcode1
                                            ?? $product->product_barcode2
                                            ?? $product->product_barcode3
                                            ?? $product->upc
                                            ?? $product->ctn_barcode;
                                    @endphp

                                    <option value="{{ $barcode }}">
                                        {{ $product->description }} — {{ $barcode }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="mb-1">Count</label>
                            <input type="number" name="count" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="mb-1">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"
                                placeholder="Enter remarks (optional)"></textarea>
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

    <div class="modal fade" id="editItemModal">
        <div class="modal-dialog">
            <form id="editItemForm">
                @csrf
                <input type="hidden" name="item_id" id="edit_item_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="mb-1">Count</label>
                            <input type="number" name="count" id="edit_count" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="mb-1">Notes</label>
                            <textarea id="edit_notes" name="notes" class="form-control" rows="3"
                                placeholder="Enter remarks (optional)"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')


    <script>
        const STOCKTAKE_ID = "{{ $stocktake->id }}";
        const STOCKTAKE_ITEMS_URL = "{{ route('stocktake-items.index', $stocktake->id) }}";
        const STOCKTAKE_ITEM_STORE_URL = "{{ route('stocktake-items.store', $stocktake->id) }}";
        const PRODUCT_SEARCH_URL = "{{ route('products.search') }}";
        const STOCKTAKE_ITEM_DELETE_URL = "{{ route('stocktake-items.delete', ':id') }}";
        const STOCKTAKE_ITEM_UPDATE_URL = "{{ route('stocktake-items.update', ['stocktake' => $stocktake->id, 'item' => ':id']) }}";
        const STOCKTAKE_FILL_URL = "{{ route('stocktake-items.fillData', $stocktake->id) }}";
        const STOCKTAKE_FINALIZE_URL = "{{ route('stocktake-items.finalize', $stocktake->id) }}";
        const STOCKTAKE_STATUS = "{{ $stocktake->status }}";
    </script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="{{ asset('js/stocktake/view/datatable.js') }}"></script>
    <script src="{{ asset('js/stocktake/view/add.js') }}"></script>
    <script src="{{ asset('js/stocktake/view/search.js') }}"></script>
    <script src="{{ asset('js/stocktake/view/edit.js') }}"></script>
    <script src="{{ asset('js/stocktake/view/delete.js') }}"></script>
    <script src="{{ asset('js/stocktake/view/filldata.js') }}"></script>
    <script src="{{ asset('js/stocktake/view/finalize.js') }}"></script>

@endpush