@extends('layouts.app')
@section('title', 'Stock Report')

@section('content')

    <div class="container-fluid py-3">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="font-size: 0.78rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('reports.index') }}" class="text-decoration-none">Reports</a>
                        </li>
                        <li class="breadcrumb-item active">Stock On Hand Report</li>
                    </ol>
                </nav>
                <h4 class="fw-semibold mb-0">Stock On Hand</h4>
            </div>
        </div>

        <div class="card shadow-sm mb-3 border-0">
            <div class="card-body py-2 px-3">
                <form method="POST" action="{{ route('reports.stock.export') }}">
                    @csrf
                    <div class="row g-2 align-items-end">

                        <div class="col-md-3">
                            <label class="form-label small fw-semibold mb-1">Supplier</label>
                            <select name="supplier_id" id="supplier_id" class="form-select form-select-sm">
                                <option value="">All Suppliers</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-download me-1"></i> Export CSV
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-auto">
                <div class="card border-0 shadow-sm px-3 py-2 d-flex flex-row align-items-center gap-2">
                    <i class="bi bi-boxes text-primary"></i>
                    <div>
                        <div class="fw-semibold lh-1">{{ $products->count() }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">Products</div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="card border-0 shadow-sm px-3 py-2 d-flex flex-row align-items-center gap-2">
                    <i class="bi bi-stack text-success"></i>
                    <div>
                        <div class="fw-semibold lh-1">{{ number_format($products->sum('stock_on_hand')) }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">Total Units</div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="card border-0 shadow-sm px-3 py-2 d-flex flex-row align-items-center gap-2">
                    <i class="bi bi-exclamation-circle text-danger"></i>
                    <div>
                        <div class="fw-semibold lh-1 text-danger">{{ $products->where('stock_on_hand', '<=', 0)->count() }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">Out of Stock</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock Table --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="stockTable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Barcode</th>
                                <th>Product Name</th>
                                <th class="text-end">Stock On Hand</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $i => $product)
                                @php
                                    $qty = $product->stock_on_hand;
                                    $cls = $qty > 20 ? 'stock-high' : ($qty > 5 ? 'stock-mid' : 'stock-low');
                                @endphp
                                <tr>
                                    <td class="text-muted small">{{ $i + 1 }}</td>
                                    <td class="text-muted small font-monospace">{{ $product->product_barcode1 }}</td>
                                    <td class="fw-semibold">{{ $product->description }}</td>
                                    <td class="text-end">
                                        <span class="stock-badge {{ $cls }}">{{ number_format($qty) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox d-block fs-2 mb-2"></i>
                                        No products found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reports/reports.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#stockTable').DataTable({
                pageLength: 25,
                order: [[2, 'asc']],
                columnDefs: [{ orderable: false, targets: [0] }]
            });
        });
    </script>
@endpush