@extends('layouts.app')

@section('title', 'Gadgetra - Instruksi Pembayaran')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment-instruction.css') }}">
@endpush

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="#" class="back-to-product" id="breadcrumbProductLink">Product</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('payment.method') }}">Metode Pembayaran</a>
        <i class="fas fa-chevron-right"></i>
        <span>Instruksi Pembayaran</span>
    </div>

    <div class="pi-wrapper">
        <div class="pi-card">
            <div class="pi-countdown-row">
                <div class="pi-countdown-left">
                    <i class="fas fa-clock pi-clock-icon"></i>
                    <span class="pi-pay-before-text">Bayar sebelum</span>
                </div>
                <div class="pi-countdown-boxes">
                    <div class="pi-time-unit">
                        <div class="pi-time-box" id="countHours">23</div>
                        <span class="pi-time-label">Jam</span>
                    </div>
                    <span class="pi-time-colon">:</span>
                    <div class="pi-time-unit">
                        <div class="pi-time-box" id="countMinutes">59</div>
                        <span class="pi-time-label">Menit</span>
                    </div>
                    <span class="pi-time-colon">:</span>
                    <div class="pi-time-unit">
                        <div class="pi-time-box" id="countSeconds">10</div>
                        <span class="pi-time-label">Detik</span>
                    </div>
                </div>
            </div>

            <div class="pi-warning-box">
                <i class="fas fa-exclamation-triangle pi-warning-icon"></i>
                <span>Buruan selesaikan pembayaranmu</span>
            </div>

            <div class="pi-section">
                <span class="pi-label-muted">Nomor Virtual Account</span>
                <div class="pi-copy-row">
                    <span class="pi-bold-value" id="vaNumber">80732XXXXXXXX</span>
                    <button class="pi-copy-btn" id="copyVaBtn" title="Salin nomor VA">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <hr class="pi-divider">

            <div class="pi-section">
                <span class="pi-label-muted">Total Tagihan</span>
                <div class="pi-copy-row">
                    <span class="pi-bold-value" id="totalTagihanDisplay">RpXXX.000</span>
                    <button class="pi-copy-btn" id="copyTotalBtn" title="Salin total tagihan">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

            <hr class="pi-divider">

            <ul class="pi-notes-list">
                <li><strong>Perhatian:</strong> Transfer Virtual Account hanya bisa dilakukan dari bank yang kamu pilih</li>
                <li>Transaksi kamu baru akan diteruskan ke admin setelah pembayaran berhasil diverifikasi.</li>
            </ul>
        </div>
    </div>

    
    <div class="pi-modal-overlay" id="successModal">
        <div class="pi-modal-card">
            <div class="pi-modal-check-circle">
                <i class="fas fa-check"></i>
            </div>
            <p class="pi-modal-title">Pembayaran Berhasil!!!</p>
            <button class="pi-modal-btn" id="openBookingCodeBtn">Buka Booking Code</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/payment-instruction.js') }}"></script>
@endpush
