@extends('layouts.admin')

@section('title', 'Manajemen Produk - Gadgetra')

@section('content')
<div class="admin-title-row">
    <h1 class="admin-title">Manajemen Produk</h1>
</div>

<div class="double-bezel-wrapper">
    <div class="double-bezel-inner">
        <div class="product-manage-layout">
            <aside class="product-manage-nav">
                <a href="{{ route('admin.products.create') }}" class="product-manage-nav-item">
                    <i class="fas fa-plus"></i>
                    <span>Tambah</span>
                </a>
                <a href="{{ route('admin.products') }}" class="product-manage-nav-item active">
                    <i class="fas fa-eye"></i>
                    <span>Lihat</span>
                </a>
                <a href="{{ route('admin.products.returns') }}" class="product-manage-nav-item">
                    <i class="fas fa-undo"></i>
                    <span>Pengembalian</span>
                </a>
            </aside>

            <div style="flex: 1;">
                <form action="{{ route('admin.products') }}" method="GET" style="margin-bottom: 2rem;">
                    <div class="search-box-wrapper" style="max-width: 100%;">
                        <i class="fas fa-search"></i>
                        <input type="text" name="q" value="{{ $search }}" class="search-input" placeholder="Cari alat berdasarkan nama atau kategori...">
                    </div>
                </form>

                @if(session('success'))
                    <div style="background-color: #dcfce7; color: #15803d; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; font-size: 0.875rem; font-weight: 600;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="product-admin-grid">
                    @forelse($products as $product)
                        <div class="product-admin-card">
                            <div class="product-admin-img-box">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-admin-img">
                            </div>
                            <div class="product-admin-content">
                                <h3 class="product-admin-name">{{ $product->name }}</h3>
                                <div class="product-admin-meta">{{ $product->category }}</div>
                                <div class="product-admin-meta" style="font-weight: 700; color: #002d72;">Rp {{ number_format($product->price_per_day, 0, ',', '.') }} / hari</div>
                                
                                <div class="product-admin-actions">
                                    <span class="product-admin-stock">Stok: {{ $product->stock }} unit</span>
                                    <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-admin" title="Hapus Produk">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: span 3; text-align: center; padding: 4rem; color: #64748b;">
                            <i class="fas fa-boxes" style="font-size: 2.5rem; margin-bottom: 1rem; display: block; color: #cbd5e1;"></i>
                            <p style="font-size: 1rem; font-weight: 500; margin: 0;">Belum ada produk terdaftar dalam katalog.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
