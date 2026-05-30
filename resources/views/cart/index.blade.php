@extends('layouts.app')

@section('title', 'Gadgetra - Keranjang Belanja')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Keranjang Belanja</span>
    </div>

    @if($cartItems->isEmpty())
        <div class="cart-empty-state">
            <i class="fas fa-shopping-cart cart-empty-icon"></i>
            <h2 class="cart-empty-title">Keranjangmu Kosong</h2>
            <p class="cart-empty-text">Sepertinya kamu belum memilih gadget impian untuk disewa. Yuk, cari gadget keren sekarang!</p>
            <a href="{{ route('home') }}" class="btn-cart-back">
                <i class="fas fa-search"></i>
                <span>Cari Gadget</span>
            </a>
        </div>
    @else
        <div class="cart-layout">
            <div class="cart-container-left">
                <div class="cart-card">
                    <div class="cart-header-row">
                        <span class="cart-title-main">Barang di Keranjang</span>
                        <label class="cart-select-all-label">
                            <input type="checkbox" id="selectAll" class="cart-item-checkbox" checked>
                            <span>Pilih Semua</span>
                        </label>
                    </div>

                    @foreach($cartItems as $item)
                        @if($item->product)
                            <div class="cart-item" data-id="{{ $item->id }}">
                                <input type="checkbox" class="item-checkbox cart-item-checkbox" 
                                       data-id="{{ $item->id }}" 
                                       data-price="{{ $item->product->price_per_day }}" 
                                       data-slug="{{ $item->product->slug }}" 
                                       data-name="{{ $item->product->name }}" 
                                       data-image="{{ $item->product->image_url }}" checked>
                                
                                <div class="cart-item-image">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                </div>

                                <div class="cart-item-details">
                                    <div class="cart-item-info">
                                        <span class="cart-item-name">{{ $item->product->name }}</span>
                                        <span class="cart-item-price">Rp{{ number_format($item->product->price_per_day, 0, ',', '.') }}/hari</span>
                                    </div>

                                    <div class="cart-item-dates">
                                        <div class="cart-date-input-group">
                                            <label>Mulai Sewa</label>
                                            <input type="date" class="cart-date-input start-date-input" 
                                                   value="{{ $item->start_date ? $item->start_date->format('Y-m-d') : '' }}" 
                                                   data-id="{{ $item->id }}">
                                        </div>
                                        <div class="cart-date-input-group">
                                            <label>Selesai Sewa</label>
                                            <input type="date" class="cart-date-input end-date-input" 
                                                   value="{{ $item->end_date ? $item->end_date->format('Y-m-d') : '' }}" 
                                                   data-id="{{ $item->id }}">
                                        </div>
                                    </div>

                                    <div class="cart-item-actions">
                                        <div class="cart-qty-control">
                                            <button class="cart-qty-btn decrease-cart-qty" data-id="{{ $item->id }}" type="button">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="cart-qty-val qty-input" 
                                                   value="{{ $item->qty }}" 
                                                   data-id="{{ $item->id }}" 
                                                   min="1" max="10" readonly>
                                            <button class="cart-qty-btn increase-cart-qty" data-id="{{ $item->id }}" type="button">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>

                                        <button class="cart-delete-btn delete-cart-item" data-id="{{ $item->id }}" type="button" title="Hapus dari Keranjang">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="cart-summary-card">
                <h3 class="cart-summary-title">Ringkasan Sewa</h3>
                <div class="cart-summary-row">
                    <span>Total Barang Terpilih</span>
                    <span id="selectedCount">0 Barang</span>
                </div>
                <div class="cart-summary-row">
                    <span>Biaya Layanan</span>
                    <span id="serviceFee">Rp0</span>
                </div>
                <div class="cart-summary-row total-row">
                    <span>Total Tagihan</span>
                    <span id="totalPrice" class="price-val">Rp0</span>
                </div>
                <button class="btn-cart-checkout" id="checkoutBtn" type="button">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Sewa Sekarang</span>
                </button>
            </div>
        </div>
    @endif

    <div class="notification-toast" id="notificationToast">
        <i class="fas fa-check-circle"></i>
        <span id="notificationMessage">Keranjang belanja berhasil diperbarui!</span>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
@endpush
