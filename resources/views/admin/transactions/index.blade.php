@extends('layouts.admin')

@section('title', 'Daftar Transaksi - Gadgetra')

@section('content')
<div class="admin-title-row">
    <h1 class="admin-title">Daftar Transaksi</h1>
</div>

<div class="double-bezel-wrapper">
    <div class="double-bezel-inner">
        <form action="{{ route('admin.transactions') }}" method="GET">
            <div class="search-filter-row">
                <div class="search-box-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" value="{{ $search }}" class="search-input" placeholder="Cari transaksi berdasarkan nama pelanggan, produk, atau kode...">
                </div>
                
                <select name="filter_type" onchange="this.form.submit()" class="filter-select">
                    <option value="">Urutkan Transaksi</option>
                    <option value="baru" {{ $filterType === 'baru' ? 'selected' : '' }}>Transaksi Terbaru</option>
                    <option value="lama" {{ $filterType === 'lama' ? 'selected' : '' }}>Transaksi Terlama</option>
                </select>
                
                @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
            </div>

            <div class="status-tabs">
                <a href="{{ route('admin.transactions', ['q' => $search, 'filter_type' => $filterType]) }}" class="status-tab {{ empty($status) || $status === 'Semua' ? 'active' : '' }}">Semua</a>
                <a href="{{ route('admin.transactions', ['q' => $search, 'status' => 'Belum dibayar', 'filter_type' => $filterType]) }}" class="status-tab {{ $status === 'Belum dibayar' ? 'active' : '' }}">Belum dibayar</a>
                <a href="{{ route('admin.transactions', ['q' => $search, 'status' => 'Sedang Disewa', 'filter_type' => $filterType]) }}" class="status-tab {{ $status === 'Sedang Disewa' ? 'active' : '' }}">Sedang disewa</a>
                <a href="{{ route('admin.transactions', ['q' => $search, 'status' => 'Selesai', 'filter_type' => $filterType]) }}" class="status-tab {{ $status === 'Selesai' ? 'active' : '' }}">Selesai</a>
            </div>
        </form>

        <div class="transaction-list">
            @forelse($transactions as $item)
                <div class="transaction-card-wrapper">
                    <div class="transaction-card">
                        <div class="transaction-prod-info">
                            <img src="{{ asset('assets/products/' . $item['product_image']) }}" alt="{{ $item['product_name'] }}" class="transaction-prod-image">
                            <div class="transaction-prod-details">
                                <h3>{{ $item['product_name'] }}</h3>
                                <div class="transaction-meta-text">Jumlah unit: {{ $item['qty'] }}</div>
                                <div class="transaction-meta-text">Tanggal sewa: {{ date('d/m/y', strtotime($item['start_date'])) }} - {{ date('d/m/y', strtotime($item['end_date'])) }}</div>
                            </div>
                        </div>

                        <div class="transaction-data-column">
                            <div class="transaction-data-label">Penyewa</div>
                            <div class="transaction-data-value">{{ $item['customer_name'] }}</div>
                        </div>

                        <div class="transaction-data-column">
                            <div class="transaction-data-label">Status</div>
                            <div>
                                @if($item['status'] === 'Sedang Disewa')
                                    <span class="badge-status active">Sedang Disewa</span>
                                @elseif($item['status'] === 'Belum dibayar')
                                    <span class="badge-status pending">Belum dibayar</span>
                                @else
                                    <span class="badge-status completed">Selesai</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('admin.transactions.show', $item['code']) }}" class="btn-detail-island">
                                <span>Lihat Detail</span>
                                <div class="btn-detail-icon-circle">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 4rem; color: #64748b;">
                    <i class="fas fa-search" style="font-size: 2.5rem; margin-bottom: 1rem; display: block; color: #cbd5e1;"></i>
                    <p style="font-size: 1rem; font-weight: 500; margin: 0;">Tidak ada transaksi yang cocok dengan kriteria pencarian.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
