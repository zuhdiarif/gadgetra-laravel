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
            @if(isset($transactions) && count($transactions) > 0)
                <div style="display: flex; flex-direction: column; gap: 8px; align-items: center; margin-top: -10px; margin-bottom: 15px;">
                    @foreach($transactions as $t)
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Status ({{ $t->code }}):</span>
                            @if($t->status === 'Sedang Disewa')
                                <span style="background: #10B981; color: white; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Sudah Dibayar</span>
                            @elseif($t->status === 'Belum dibayar')
                                <span style="background: #F59E0B; color: white; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Belum Dibayar</span>
                            @elseif($t->status === 'Batal')
                                <span style="background: #EF4444; color: white; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">Dibatalkan</span>
                            @else
                                <span style="background: #6B7280; color: white; padding: 4px 12px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">{{ $t->status }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
            <hr class="bc-divider">

            <div class="bc-body">
                <div class="bc-left">
                    <div id="bookingProductsList">
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
                    </div>

                    <hr class="bc-divider">

                    <div class="bc-address-block">
                        <span class="bc-label-muted">ALAMAT PENGAMBILAN</span>
                        <div class="bc-address-text">Lowokwaru, kota Malang, Jawa Timur</div>
                    </div>
                </div>

                <div class="bc-right" style="display: flex; flex-direction: column; align-items: center;">
                    <div style="background: #fff; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 12px; width: 174px; height: 174px; display: flex; align-items: center; justify-content: center;">
                        <canvas id="bcQrCanvas"></canvas>
                    </div>
                    <span class="bc-label-booking">Kode Booking :</span>
                    <div class="bc-code-box" id="bcBookingCode">TYZ10CH6U</div>
                </div>
            </div>

            <hr class="bc-divider">
            <p class="bc-footer-note">*Tunjukkan kode ini ke pihak kasir</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/qrious.min.js') }}"></script>
    <script src="{{ asset('js/booking-code.js') }}"></script>
@endpush
