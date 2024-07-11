@extends('layouts.base_admin.base_dashboard')
@section('judul', 'Halaman Dashboard')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h2 class="font-weight">DASHBOARD</h2>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container">
        <div class="row justify-content-center">
            <!-- Pie Chart Card -->
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Grafik Surat Masuk, Keluar, dan Disposisi</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4">
                            <canvas id="suratChart" width="200" height="200"></canvas>
                        </div>
                        <hr>
                        <div class="text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> Surat Masuk
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-danger"></i> Surat Keluar
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Disposisi
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Cards -->
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-info text-white">
                        <h6 class="m-0 font-weight-bold">Surat Masuk</h6>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Surat Masuk</h5>
                        <p class="card-text display-4">{{ $masukCount }}</p>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-danger text-white">
                        <h6 class="m-0 font-weight-bold">Surat Keluar</h6>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Surat Keluar</h5>
                        <p class="card-text display-4">{{ $keluarCount }}</p>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">Belum Disposisi</h6>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Surat Belum Disposisi</h5>
                        <p class="card-text display-4">{{ $belumDisposisiCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('suratChart').getContext('2d');
        var suratChart = new Chart(ctx, {
            type: 'pie', // Tipe chart
            data: {
                labels: ['Surat Masuk', 'Surat Keluar', 'Disposisi'],
                datasets: [{
                    data: [{{ $masukCount }}, {{ $keluarCount }}, {{ $disposisiCount }}], // Jumlah data
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)', // Surat Masuk (blue)
                        'rgba(255, 99, 132, 0.6)', // Surat Keluar (red)
                        'rgba(75, 192, 192, 0.6)'  // Disposisi (green)
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: false
                }
            }
        });
    });
</script>

@endsection
