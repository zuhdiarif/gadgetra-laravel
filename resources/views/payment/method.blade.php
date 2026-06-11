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

    <!-- Mock Midtrans Modal -->
    <div id="mockMidtransModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: #fff; border-radius: 12px; width: 90%; max-width: 400px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); text-align: center; font-family: 'Plus Jakarta Sans', sans-serif;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 16px;">
                <span style="font-weight: 800; font-size: 1.25rem; color: #002D72;">midtrans</span>
                <span style="background: #FF9900; color: #fff; font-size: 0.75rem; padding: 2px 8px; border-radius: 20px; font-weight: 700;">SANDBOX SIMULATOR</span>
            </div>
            <p style="color: #666; font-size: 0.875rem; margin-bottom: 20px; line-height: 1.5;">Anda berada dalam mode uji coba/sandbox. Silakan simulasikan pembayaran untuk melanjutkan proses sewa.</p>
            <div style="background: #f8f9fa; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 12px; margin-bottom: 20px;">
                <span style="color: #666; font-size: 0.75rem; display: block; margin-bottom: 4px; font-weight: 600;">TOTAL TAGIHAN</span>
                <strong id="mockTotalTagihan" style="font-size: 1.25rem; color: #002D72;">Rp0</strong>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <button id="btnMockSuccess" style="background: #FF9900; color: #fff; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.3s; font-size: 0.9rem;">Simulasikan Pembayaran Sukses</button>
                <button id="btnMockCancel" style="background: #f1f5f9; color: #475569; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.3s; font-size: 0.9rem;">Batal / Tutup</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-dummy') }}"></script>
    <script src="{{ asset('js/payment-method.js') }}"></script>
@endpush
