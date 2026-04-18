@extends('layouts.app')
@section('title', 'Return Products')

@section('content')

<div class="container-fluid py-3">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Return Products</h4>

        <a href="{{ ($type ?? '') == 'goods_receiving' 
            ? route('goods.receiving.index') 
            : route('manual-invoices.index') }}" 
           class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <!-- INFO CARD -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3">

                <!-- Order / Invoice -->
                <div class="col-md-3">
                    <strong>{{ ($type ?? '') == 'goods_receiving' ? 'Order ID:' : 'Invoice No:' }}</strong>
                    <div>
                        {{ ($type ?? '') == 'goods_receiving' 
                            ? '#'.($data?->warehouse_order_id ?? '-') 
                            : ($data?->invoice_number ?? '-') }}
                    </div>
                </div>

                <!-- Date -->
                <div class="col-md-3">
                    <strong>{{ ($type ?? '') == 'goods_receiving' ? 'Order Date:' : 'Invoice Date:' }}</strong>
                    <div>
                        {{ ($type ?? '') == 'goods_receiving'
                            ? optional($data?->warehouseOrder)->created_at?->format('d-m-Y h:i A')
                            : ($data?->invoice_date 
                                ? \Carbon\Carbon::parse($data->invoice_date)->format('d-m-Y') 
                                : '-') }}
                    </div>
                </div>

                <!-- Supplier -->
                <div class="col-md-3">
                    <strong>Supplier:</strong>
                    <div>
                        {{ ($type ?? '') == 'goods_receiving'
                            ? ($data?->supplier?->name ?? '-')
                            : ($data?->supplier ?? '-') }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card shadow-sm">
        <div class="card-body p-3">

            <form id="returnForm">
                @csrf

                @if(($type ?? '') == 'goods_receiving')
                    <input type="hidden" id="goods_receiving_id" name="goods_receiving_id" value="{{ $data?->id }}">
                    <input type="hidden" name="warehouse_order_id" value="{{ $data?->warehouse_order_id }}">
                @endif

                @if(($type ?? '') == 'manual_invoice')
                    <input type="hidden" id="manual_invoice_id" name="manual_invoice_id" value="{{ $data?->id }}">
                @endif

                <input type="hidden" name="invoice_number"
                    value="{{ ($type ?? '') == 'manual_invoice' 
                        ? ($data?->invoice_number ?? '') 
                        : '' }}">

                <!-- TABLE -->
                <div class="table-responsive">
                    <table id="returnTable" class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sl No</th>
                                <th>Description</th>
                                <th>Barcode</th>
                                <th>Ordered Qty</th>
                                <th width="150">Return Qty</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="text-end mt-3">
                    @if(!($isFinalized ?? false))
                        <button type="button" id="finalizeReturnBtn" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Finalize Return
                        </button>
                    @else
                        <button type="button" id="undoReturnBtn" class="btn btn-warning">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Undo Return
                        </button>
                    @endif
                </div>

            </form>

        </div>
    </div>

</div>

@endsection

@push('scripts')

<script>
    const INDEX_URL = "{{ route('returns.index') }}";
    const STORE_URL = "{{ route('returns.store') }}";
    const UNDO_URL = "{{ isset($data) ? route('returns.undo', $data->id) : '' }}";
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script src="{{ asset('js/returns/datatable.js') }}"></script>
<script src="{{ asset('js/returns/finalize.js') }}"></script>
<script src="{{ asset('js/returns/undofinalize.js') }}"></script>

@endpush