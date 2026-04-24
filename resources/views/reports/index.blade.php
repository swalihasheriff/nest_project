@extends('layouts.app')
@section('title', 'Reports')

@section('content')

    <div class="container-fluid py-3">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div>
                <h4 class="fw-semibold mb-0">Reports</h4>
            </div>
            <span class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->format('D, d M Y') }}
            </span>
        </div>

        <hr class="mt-2 mb-3">

        {{-- Stats Bar --}}
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center gap-4">
                    <div class="report-stat">
                        <span class="report-stat-value">4</span>
                        <span class="report-stat-label">Total Reports</span>
                    </div>
                    <div class="report-stat-divider"></div>
                    <div class="report-stat">
                        <span class="report-stat-value">CSV</span>
                        <span class="report-stat-label">Export Format</span>
                    </div>
                    <div class="report-stat-divider"></div>
                    <div class="report-stat">
                        <span class="report-stat-value text-success">Live</span>
                        <span class="report-stat-label">Data Source</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Label --}}
        <p class="text-muted fw-semibold mb-2" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em;">
            Available Reports
        </p>

        {{-- Report Cards --}}
        <div class="row g-3">

            {{-- Stock Report --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('reports.stock') }}" class="text-decoration-none">
                    <div class="card report-card shadow-sm border-0 h-100">
                        <div class="card-body p-3">
                            <div class="report-icon-box mb-3" style="background:#eef2ff; color:#4f46e5;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h6 class="fw-semibold text-dark mb-1">Stock Report</h6>
                            <p class="text-muted small mb-3">Current stock on hand by product &amp; supplier</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="report-tag" style="color:#4f46e5; background:#eef2ff;">Inventory</span>
                                <i class="bi bi-arrow-up-right text-muted report-arrow"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Pickup Report --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('reports.pickup') }}" class="text-decoration-none">
                    <div class="card report-card shadow-sm border-0 h-100">
                        <div class="card-body p-3">
                            <div class="report-icon-box mb-3" style="background:#f0fdf4; color:#16a34a;">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h6 class="fw-semibold text-dark mb-1">Pickup Report</h6>
                            <p class="text-muted small mb-3">Pickups within a selected date range</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="report-tag" style="color:#16a34a; background:#f0fdf4;">Logistics</span>
                                <i class="bi bi-arrow-up-right text-muted report-arrow"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Sales Summary --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('reports.sales.sum') }}" class="text-decoration-none">
                    <div class="card report-card shadow-sm border-0 h-100">
                        <div class="card-body p-3">
                            <div class="report-icon-box mb-3" style="background:#fffbeb; color:#d97706;">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <h6 class="fw-semibold text-dark mb-1">Sales Summary</h6>
                            <p class="text-muted small mb-3">Aggregated sales totals per period</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="report-tag" style="color:#d97706; background:#fffbeb;">Sales</span>
                                <i class="bi bi-arrow-up-right text-muted report-arrow"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Sales Report --}}
            <div class="col-lg-3 col-md-4 col-sm-6">
                <a href="{{ route('reports.sales') }}" class="text-decoration-none">
                    <div class="card report-card shadow-sm border-0 h-100">
                        <div class="card-body p-3">
                            <div class="report-icon-box mb-3" style="background:#fef2f2; color:#dc2626;">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <h6 class="fw-semibold text-dark mb-1">Sales Report</h6>
                            <p class="text-muted small mb-3">Detailed line-by-line sales breakdown</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="report-tag" style="color:#dc2626; background:#fef2f2;">Sales</span>
                                <i class="bi bi-arrow-up-right text-muted report-arrow"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/reports/reports.css') }}">
@endpush