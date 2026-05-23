@extends('layouts.admin')

@section('title', 'Dashboard Admin - Gadgetra')

@section('content')
<div class="admin-title-row">
    <h1 class="admin-title">Dashboard</h1>
</div>

<div class="double-bezel-wrapper">
    <div class="double-bezel-inner">
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-header">
                    <span>Total Produk</span>
                    <div class="metric-icon-box">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $totalProducts }}</div>
                <div class="metric-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Aktif dalam katalog</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <span>Jumlah Produk Disewa</span>
                    <div class="metric-icon-box">
                        <i class="fas fa-hand-holding"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $totalRented }}</div>
                <div class="metric-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Sedang digunakan pelanggan</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <span>Pelanggan Aktif</span>
                    <div class="metric-icon-box">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $activeCustomers }}</div>
                <div class="metric-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Memiliki transaksi aktif</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <span>Ketersediaan Stok</span>
                    <div class="metric-icon-box">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="metric-value">{{ $availableStock }}</div>
                <div class="metric-trend">
                    <span>Unit siap disewa</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <span>Total Keuntungan Diterima</span>
                    <div class="metric-icon-box">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="metric-value">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                <div class="metric-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Transaksi terbayar & selesai</span>
                </div>
            </div>

            <div class="metric-card">
                <div class="metric-header">
                    <span>Proyeksi Pendapatan</span>
                    <div class="metric-icon-box">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="metric-value">Rp {{ number_format($projectedEarnings, 0, ',', '.') }}</div>
                <div class="metric-trend">
                    <span>Akumulasi seluruh transaksi</span>
                </div>
            </div>
        </div>

        <div class="chart-section-wrapper">
            <div class="chart-header">
                <div class="chart-title-box">
                    <h3>Visualisasi Keuntungan & Penyewaan</h3>
                    <p>Statistik performa penyewaan produk Gadgetra</p>
                </div>
                <div class="chart-btn-group">
                    <button class="chart-btn active" data-type="hari">Harian</button>
                    <button class="chart-btn" data-type="minggu">Mingguan</button>
                    <button class="chart-btn" data-type="bulan">Bulanan</button>
                </div>
            </div>
            <div class="chart-container" id="profitChart"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chartData = {
            hari: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                earnings: [150000, 300000, 250000, 600000, 450000, 900000, 1200000],
                rentals: [1, 2, 1, 3, 2, 4, 5]
            },
            minggu: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                earnings: [2500000, 3100000, 2800000, 4200000],
                rentals: [10, 12, 11, 16]
            },
            bulan: {
                labels: ['Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei'],
                earnings: [12000000, 15000000, 14200000, 18500000, 22000000, 24500000],
                rentals: [48, 60, 56, 74, 88, 98]
            }
        };

        const options = {
            series: [
                {
                    name: 'Keuntungan (Rp)',
                    type: 'area',
                    data: chartData.hari.earnings
                },
                {
                    name: 'Jumlah Penyewaan',
                    type: 'line',
                    data: chartData.hari.rentals
                }
            ],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans, sans-serif'
            },
            colors: ['#002d72', '#f97316'],
            stroke: {
                width: [4, 4],
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: [0.4, 0],
                    opacityTo: [0.1, 0],
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: chartData.hari.labels,
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: [
                {
                    title: {
                        text: 'Keuntungan (Rp)'
                    },
                    labels: {
                        formatter: function (value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                {
                    opposite: true,
                    title: {
                        text: 'Jumlah Penyewaan'
                    }
                }
            ],
            grid: {
                borderColor: '#f1f5f9'
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return y.toLocaleString('id-ID');
                        }
                        return y;
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#profitChart"), options);
        chart.render();

        const buttons = document.querySelectorAll('.chart-btn');
        buttons.forEach(button => {
            button.addEventListener('click', () => {
                buttons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const type = button.getAttribute('data-type');
                const selectedData = chartData[type];

                chart.updateOptions({
                    xaxis: {
                        categories: selectedData.labels
                    },
                    series: [
                        {
                            name: 'Keuntungan (Rp)',
                            type: 'area',
                            data: selectedData.earnings
                        },
                        {
                            name: 'Jumlah Penyewaan',
                            type: 'line',
                            data: selectedData.rentals
                        }
                    ]
                });
            });
        });
    });
</script>
@endpush
@endsection

