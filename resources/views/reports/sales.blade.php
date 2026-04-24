@extends('layouts.app')
@section('title', 'Sales Report')

@section('content')

    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="font-size: 0.78rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('reports.index') }}" class="text-decoration-none">Reports</a>
                        </li>
                        <li class="breadcrumb-item active">Sales Report</li>
                    </ol>
                </nav>
                <h4 class="fw-semibold mb-0">Detailed Sales Report</h4>
            </div>
        </div>

        <div class="card shadow-sm mb-3 border-0">
            <div class="card-body py-2 px-3">
                <form method="GET" action="{{ route('reports.sales') }}">
                    <div class="row g-2 align-items-end">

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold mb-1">From Date</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold mb-1">To Date</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm" required>
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-search me-1"></i> Show Report
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if($salesItems->count() > 0)
            <div class="card shadow-sm mb-3 border-0">
                <div class="card-body py-2 px-3">
                    <form method="POST" action="{{ route('reports.sales.export') }}">
                        @csrf
                        <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                        <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                        <button type="submit" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-download me-1"></i> Export CSV
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="table-responsive">
                    <table id="salesTable" class="table table-bordered table-striped nowrap w-100">
                        <thead>
                            <tr>
                                <th>Sl No</th>
                                <th>Items (Product)</th>
                                <th class="text-end">Quantity Sold</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesItems as $idx => $item)
                                <tr>
                                    <td class="text-muted small">{{ $idx + 1 }}</td>
                                    <td class="fw-semibold">{{ $item->product->description }}</td>
                                    <td class="text-end">{{ $item->quantity }}</td>
                                    <td class="text-end">₹{{ number_format($item->product->ctn_sell_price, 2) }}</td>
                                    <td class="text-end">₹{{ number_format($item->discount, 2) }}</td>
                                    <td class="text-end fw-semibold">₹{{ number_format($item->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-inbox d-block fs-2 mb-2"></i>
                                        Select date range and click "Show Report" to view sales
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
            @if($salesItems->count() > 0)
                $('#salesTable').DataTable({
                    pageLength: 25,
                    order: [[0, 'desc']],
                    columnDefs: [{ orderable: false, targets: [0] }]
                });
            @endif
        });
    </script>
@endpush