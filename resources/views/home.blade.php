@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="main-content-table"
    style="background-color: {{ auth()->user()->role == 'admin' ? 'rgba(5, 5, 5, 0.87)' : 'rgba(255, 255, 255, 0.95)' }}; padding: 20px;">
    <div class="main-content-table {{ auth()->user()->role == 'user' ? 'admin-bg' : 'user-bg' }}">

        <section class="section">
            <div class="margin-content">
                <div class="container-sm">
                    <div class="section-header">
                        <h1 class="text-primary">Selamat Datang, {{ substr(auth()->user()->name, 0, 15) }}!</h1>
                    </div>

                    @if (Auth::user()->role == 'admin')
                    <div class="row mt-4">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card card-statistic-1 shadow-sm border-0 rounded-lg" style="transition: transform 0.2s;">
                                <div class="card-icon bg-success text-white">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Produk</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $productCount }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card card-statistic-1 shadow-sm border-0 rounded-lg" style="transition: transform 0.2s;">
                                <div class="card-icon bg-warning text-white">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Penjualan</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $salesCount }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card card-statistic-1 shadow-sm border-0 rounded-lg" style="transition: transform 0.2s;">
                                <div class="card-icon bg-danger text-white">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>Member</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $memberCount }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div style="width: 100%; height: 500px;">

                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4 class="text-primary">Grafik Penjualan Bulanan</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="salesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="card shadow-sm mt-4 border-0 rounded-lg bg-light">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <h4 class="text-dark mb-2">Total Penjualan Hari Ini</h4>
                                </div>
                                <h2 class="font-weight-bold text-success">
                                    {{ number_format($salesCount, 0, ',', '.') }}
                                </h2>
                                <p class="text-muted">Jumlah total penjualan yang berhasil dilakukan hari ini.</p>
                            </div>
                            <div class="card-footer bg-transparent text-center">
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> Penjualan Terakhir Dilakukan:
                                    @if ($lastSale)
                                    {{ \Carbon\Carbon::parse($lastSale->created_at)->translatedFormat('d M Y H:i') }}
                                    @else
                                    Belum ada penjualan
                                    @endif
                                </small>
                            </div>
                        </div>

                    </div>
                    @endif


                </div>
            </div>
    </div>
    </section>
</div>


<style>
    .admin-bg {
        background-color: rgba(5, 5, 5, 0.87);
        padding: 20px;
    }

    .user-bg {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 20px;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .text-primary {
        color: #007bff !important;
        /* Adjust this to match your sidebar color */
    }

    .bg-success {
        background-color: #28a745 !important;
        /* Match with sidebar */
    }

    .bg-warning {
        background-color: #ffc107 !important;
        /* Match with sidebar */
    }

    .bg-primary {
        background-color: #007bff !important;
        /* Match with sidebar */
    }

    .bg-danger {
        background-color: #dc3545 !important;
        /* Match with sidebar */
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Jumlah Penjualan',
                data: @json($salesCounts),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Penjualan'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        }
    });
</script>

@endsection