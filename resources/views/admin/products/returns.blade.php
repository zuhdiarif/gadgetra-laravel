@extends('layouts.admin')

@section('title', 'Manajemen Pengembalian - Gadgetra')

@section('content')
<div class="admin-title-row">
    <h1 class="admin-title">Manajemen Pengembalian</h1>
</div>

<div class="double-bezel-wrapper">
    <div class="double-bezel-inner">
        <div class="product-manage-layout">
            <aside class="product-manage-nav">
                <a href="{{ route('admin.products.create') }}" class="product-manage-nav-item">
                    <i class="fas fa-plus"></i>
                    <span>Tambah</span>
                </a>
                <a href="{{ route('admin.products') }}" class="product-manage-nav-item">
                    <i class="fas fa-eye"></i>
                    <span>Lihat</span>
                </a>
                <a href="{{ route('admin.products.returns') }}" class="product-manage-nav-item active">
                    <i class="fas fa-undo"></i>
                    <span>Pengembalian</span>
                </a>
            </aside>

            <div style="flex: 1;">
                @if(session('success'))
                    <div style="background-color: #dcfce7; color: #15803d; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; font-size: 0.875rem; font-weight: 600;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="transaction-list">
                    @php
                        $activeRentals = $transactions->where('status', 'Sedang Disewa');
                    @endphp

                    @forelse($activeRentals as $item)
                        <div class="transaction-card-wrapper">
                            <div class="transaction-card">
                                <div class="transaction-prod-info">
                                    <img src="{{ asset('assets/products/' . $item['product_image']) }}" alt="{{ $item['product_name'] }}" class="transaction-prod-image">
                                    <div class="transaction-prod-details">
                                        <h3>{{ $item['product_name'] }}</h3>
                                        <div class="transaction-meta-text">Penyewa: {{ $item['customer_name'] }}</div>
                                        <div class="transaction-meta-text">Jumlah unit: {{ $item['qty'] }}</div>
                                        <div class="transaction-meta-text">Kode: {{ $item['code'] }}</div>
                                    </div>
                                </div>

                                <div class="transaction-data-column">
                                    <div class="transaction-data-label">Tanggal Sewa</div>
                                    <div class="transaction-data-value" style="font-size: 0.875rem;">
                                        {{ date('d/m/Y', strtotime($item['start_date'])) }} - {{ date('d/m/Y', strtotime($item['end_date'])) }}
                                    </div>
                                </div>

                                <div>
                                    <form action="{{ route('admin.products.mark_returned', $item['code']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-detail-island" style="background-color: #15803d;">
                                            <span>Kembalikan</span>
                                            <div class="btn-detail-icon-circle">
                                                <i class="fas fa-check"></i>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 4rem; color: #64748b;">
                            <i class="fas fa-undo" style="font-size: 2.5rem; margin-bottom: 1rem; display: block; color: #cbd5e1;"></i>
                            <p style="font-size: 1rem; font-weight: 500; margin: 0;">Tidak ada barang yang sedang disewa saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
