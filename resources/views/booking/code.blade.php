@extends('layouts.app')

@section('title', 'Gadgetra - Kode Booking')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/booking-code.css') }}">
@endpush

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>Kode Booking</span>
    </div>

    <div class="bc-wrapper">
        <div class="bc-card">
            <h2 class="bc-card-title">KODE BOOKING</h2>
            <hr class="bc-divider">

            <div class="bc-body">
                <div class="bc-left">
                    <div class="bc-product-row">
                        <img class="bc-product-img" src="{{ asset('assets/products/Sony Alpha A7 IV Camera.png') }}" alt="Sony Alpha IV">
                        <div class="bc-product-info">
                            <div class="bc-product-name">Sony Alpha IV</div>
                            <div class="bc-product-meta" id="bcTanggalSewa">Tanggal sewa: 22/11/26 - 23/11/26</div>
                            <div class="bc-product-meta" id="bcJumlah">Jumlah : X buah</div>
                            <div class="bc-product-meta">Total Biaya Sewa:</div>
                            <div class="bc-product-price" id="bcTotalBiaya">RpXXX.000</div>
                        </div>
                    </div>

                    <hr class="bc-divider">

                    <div class="bc-address-block">
                        <span class="bc-label-muted">ALAMAT PENGAMBILAN</span>
                        <div class="bc-address-text">Lowokwaru, kota Malang, Jawa Timur</div>
                    </div>
                </div>

                <div class="bc-right">
                    <img class="bc-qr-img"
                        src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TYZ10CH6U"
                        alt="QR Code Booking">
                    <span class="bc-label-booking">Kode Booking :</span>
                    <div class="bc-code-box">TYZ10CH6U</div>
                </div>
            </div>

            <hr class="bc-divider">
            <p class="bc-footer-note">*Tunjukkan kode ini ke pihak kasir</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/booking-code.js') }}"></script>
@endpush
