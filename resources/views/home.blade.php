@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="main-content-table" style="background-color: #f4f4f4; padding: 20px;">
    <section class="section">
        <div class="margin-content">
            <div class="container-sm">
                <div class="section-header">
                    <h1 class="text-primary">Selamat Datang, {{ substr(auth()->user()->name, 0, 15) }}!</h1>
                </div>

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

                    @if (auth()->user()->role == 'manageradmin')
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card card-statistic-1 shadow-sm border-0 rounded-lg" style="transition: transform 0.2s;">
                            <div class="card-icon bg-primary text-white">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>User</h4>
                                </div>
                                <div class="card-body">
                                    {{ $userCount }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

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

                </div>

            </div>
        </div>
    </section>
</div>

<style>
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    .text-primary {
        color: #007bff !important; /* Adjust this to match your sidebar color */
    }
    .bg-success {
        background-color: #28a745 !important; /* Match with sidebar */
    }
    .bg-warning {
        background-color: #ffc107 !important; /* Match with sidebar */
    }
    .bg-primary {
        background-color: #007bff !important; /* Match with sidebar */
    }
    .bg-danger {
        background-color: #dc3545 !important; /* Match with sidebar */
    }
</style>
@endsection
