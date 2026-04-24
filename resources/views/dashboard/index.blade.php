@extends('layouts.app')

@section('content')

    <div class="app-content-header">
        <div class="container-fluid">
            <h3 class="mb-0">Dashboard</h3>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="row g-3 mb-4">

                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('suppliers.index') }}" class="text-decoration-none">
                        <div class="small-box bg-danger text-white">
                            <div class="inner">
                                <h4>{{ $supplierCount }}</h4>
                                <p>Suppliers</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('products.index') }}" class="text-decoration-none">
                        <div class="small-box bg-secondary text-white">
                            <div class="inner">
                                <h4>{{ $productCount }}</h4>
                                <p>Products</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <a href="{{ route('warehouse.orders.index') }}" class="text-decoration-none">
                        <div class="small-box bg-dark text-white">
                            <div class="inner">
                                <h4>{{ $orderCount }}</h4>
                                <p>Orders</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <div class="small-box bg-primary text-white">
                        <div class="inner">
                            <h4>{{ $pickupsToday }}</h4>
                            <p>Pickups Today</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <div class="small-box bg-success text-white">
                        <div class="inner">
                            <h4>₹{{ number_format($salesYesterday, 0) }}</h4>
                            <p>Sales Yesterday</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <div class="small-box bg-black text-white">
                        <div class="inner">
                            <h4>₹{{ number_format($salesToday, 0) }}</h4>
                            <p>Sales Today</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <div class="small-box bg-success text-white">
                        <div class="inner">
                            <h4>{{ $pendingDeliveries }}</h4>
                            <p>Pending Deliveries</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row align-items-stretch mb-4">

                <div class="col-lg-8">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-semibold">Sales (Last 7 Days)</h6>
                        </div>
                        <div class="card-body d-flex align-items-center">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-semibold">Orders vs Deliveries</h6>
                        </div>
                        <div class="card-body d-flex align-items-center">
                            <canvas id="ordersChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Sales Chart (Line Chart - Last 7 Days)
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($salesChartLabels) !!},
                datasets: [{
                    label: 'Sales Amount',
                    data: {!! json_encode($salesChartData) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.15)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: true } 
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Orders Chart (Bar Chart)
        new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: ['Total Orders', 'Delivered', 'Pending'],
                datasets: [{
                    label: 'Count',
                    data: [{{ $totalOrders }}, {{ $totalDelivered }}, {{ $totalPending }}],
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false } 
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

@endpush