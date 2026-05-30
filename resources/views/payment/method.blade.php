@extends('layouts.app')

@section('title', 'Gadgetra - Metode Pembayaran')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment-method.css') }}">
@endpush

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="#" class="back-to-product" id="breadcrumbProductLink">Product</a>
        <i class="fas fa-chevron-right"></i>
        <span>Metode Pembayaran</span>
    </div>

    <div class="payment-method-layout">
        <div class="payment-card-left">
            <div class="white-shadow-card">
                <span class="card-label-muted">ALAMAT PENGAMBILAN</span>
                <div class="card-address-text">Lowokwaru, kota Malang, Jawa Timur</div>
            </div>

            <div class="white-shadow-card">
                <div id="checkoutProductsList">
                    <div class="product-item-title">Sony Alpha IV</div>
                    <div class="product-item-body">
                        <div class="product-item-image">
                            <img src="{{ asset('assets/products/Sony Alpha A7 IV Camera.png') }}" alt="Sony Alpha IV">
                        </div>
                        <div class="product-item-details">
                            <div class="product-item-meta" id="displayMasaSewa">Masa Sewa : 3 Hari</div>
                            <div class="product-item-price">Rp 300rb/hari</div>
                            <div class="product-item-meta" id="displayJumlah">Jumlah : 1 buah</div>
                        </div>
                    </div>
                </div>

                <hr class="product-card-divider">

                <div class="note-input-container">
                    <div class="note-header">
                        <label class="note-label">Beri Catatan :</label>
                        <span class="note-counter">0/200</span>
                    </div>
                    <textarea class="note-textarea" maxlength="200"
                        placeholder="Tulis catatan sewa Anda di sini..."></textarea>
                </div>
            </div>
        </div>

        <div class="payment-panel-right">
            <h2 class="payment-panel-title">Metode Pembayaran</h2>

            <div class="payment-methods-list">
                @foreach($paymentMethods as $method)
                    <div class="payment-method-row">
                        <div class="payment-method-logo">
                            <img src="{{ asset('assets/' . $method['logo']) }}" alt="{{ $method['name'] }}">
                        </div>
                        <span class="payment-method-text">{{ $method['name'] }}</span>
                    </div>
                @endforeach
            </div>

            <hr class="product-card-divider">

            <h3 class="transaction-summary-title">Ini ringkasan transaksimu ya...</h3>

            <div class="summary-row">
                <span id="summaryTotalSewaLabel">Total Sewa (1 Barang)</span>
                <span id="summaryTotalSewaVal">Rp900.000</span>
            </div>
            <div class="summary-row">
                <span>Biaya Layanan</span>
                <span>Rp2.000</span>
            </div>

            <hr class="thick-divider">

            <div class="summary-row total-bill-row">
                <span>Total Tagihan</span>
                <span id="summaryTotalTagihanVal">Rp905.000</span>
            </div>

            <button class="btn-pay-now">Bayar Sekarang</button>
        </div>
    </div>

    
    <div class="rentals-section">
        <h3>Recent Rentals</h3>
        <div class="rentals-grid">
            <div class="rental-card">
                <img src="{{ asset('assets/products/MacBook Pro M3 Space Black.png') }}" alt="MacBook">
                <div class="rental-info">
                    <strong>MacBook Pro 16"</strong>
                    <span class="rental-status returned">Returned • Oct 12</span>
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
            <div class="rental-card">
                <img src="{{ asset('assets/products/DJI Mavic 3 Pro.png') }}" alt="DJI">
                <div class="rental-info">
                    <strong>DJI Mavic 3 Pro</strong>
                    <span class="rental-status returned">Returned • Sep 28</span>
                </div>
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/payment-method.js') }}"></script>
@endpush
