@extends('layouts.app')
@section('title', 'Sales Summary')

@section('content')

    <div class="container-fluid py-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="font-size: 0.78rem;">
                        <li class="breadcrumb-item">
                            <a href="{{ route('reports.index') }}" class="text-decoration-none">Reports</a>
                        </li>
                        <li class="breadcrumb-item active">Sales Summary</li>
                    </ol>
                </nav>
                <h4 class="fw-semibold mb-0">Sales Summary Report</h4>
            </div>
        </div>

        <div class="card shadow-sm mb-3 border-0">
            <div class="card-body py-2 px-3">
                <form method="GET" action="{{ route('reports.sales.sum') }}">
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

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div style="font-size:2.5rem; color:#28a745;">₹</div>
                            <div>
                                <div class="text-muted small">Total Sales ({{ request('from_date') ?? 'N/A' }} to {{ request('to_date') ?? 'N/A' }})</div>
                                <div class="fw-bold" style="font-size:1.8rem; color:#111;">
                                    ₹{{ number_format($totalSales, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div style="font-size:2.5rem; color:#0056b3;">📊</div>
                            <div>
                                <div class="text-muted small">Total Transactions</div>
                                <div class="fw-bold" style="font-size:1.8rem; color:#111;">
                                    {{ $totalTransactions }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reports/reports.css') }}">
@endpush