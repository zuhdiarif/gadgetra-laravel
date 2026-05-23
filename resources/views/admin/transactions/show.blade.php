@extends('layouts.admin')

@section('title', 'Detail Transaksi - Gadgetra')

@section('content')
<div class="breadcrumb-admin">
    <a href="{{ route('admin.transactions') }}">Daftar Transaksi</a>
    <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
    <span>Lihat Detail</span>
</div>

<div class="double-bezel-wrapper">
    <div class="double-bezel-inner">
        <div class="detail-split-layout">
            <div class="detail-info-card">
                <div class="customer-profile-section">
                    <h2 class="customer-name-large">{{ $transaction['customer_name'] }}</h2>
                    <div class="customer-contact-grid">
                        <div class="contact-item-bezel">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $transaction['customer_email'] }}</span>
                        </div>
                        <div class="contact-item-bezel">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $transaction['customer_address'] }}</span>
                        </div>
                        <div class="contact-item-bezel">
                            <i class="fas fa-phone"></i>
                            <span>{{ $transaction['customer_phone'] }}</span>
                        </div>
                        <div class="contact-item-bezel">
                            <i class="fas fa-id-card"></i>
                            <span>NIP/KTP Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <div class="detail-product-showcase">
                    <img src="{{ asset('assets/products/' . $transaction['product_image']) }}" alt="{{ $transaction['product_name'] }}" class="detail-product-image">
                    <div class="detail-product-meta">
                        <h2>{{ $transaction['product_name'] }}</h2>
                        <div class="transaction-meta-text" style="font-size: 0.9375rem;">Tanggal sewa: {{ date('d F Y', strtotime($transaction['start_date'])) }} - {{ date('d F Y', strtotime($transaction['end_date'])) }}</div>
                        <div class="transaction-meta-text" style="font-size: 0.9375rem;">Jumlah: {{ $transaction['qty'] }} unit</div>
                        <div class="transaction-meta-text" style="font-size: 1.125rem; font-weight: 700; color: #002d72; margin-top: 0.5rem;">
                            Total Biaya Sewa: Rp {{ number_format($transaction['total_price'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="detail-status-countdown">
                    <div class="detail-status-box">
                        <h4>Status</h4>
                        @if($transaction['status'] === 'Sedang Disewa')
                            <div class="detail-status-text" style="color: #0369a1;">
                                <i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>{{ $transaction['status'] }}
                            </div>
                        @elseif($transaction['status'] === 'Belum dibayar')
                            <div class="detail-status-text" style="color: #b45309;">
                                <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>{{ $transaction['status'] }}
                            </div>
                        @else
                            <div class="detail-status-text" style="color: #15803d;">
                                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>{{ $transaction['status'] }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="detail-countdown-box">
                        <h4>Sisa Waktu</h4>
                        <div class="detail-countdown-val" id="adminCountdown">{{ $transaction['remaining_time'] }}</div>
                    </div>
                </div>

                @if($transaction['status'] === 'Sedang Disewa')
                    <div style="margin-top: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1.5rem; display: flex; justify-content: flex-end;">
                        <form action="{{ route('admin.products.mark_returned', $transaction['code']) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-submit-admin" style="background-color: #15803d;">
                                <i class="fas fa-undo"></i>
                                <span>Konfirmasi Pengembalian Barang</span>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="ticket-sidebar">
                <div class="qr-wrapper">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ $transaction['code'] }}" alt="QR Code" class="qr-image">
                </div>
                <div class="ticket-code-label">Kode Booking</div>
                <div class="ticket-code-value">{{ $transaction['code'] }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const countdownEl = document.getElementById('adminCountdown');
        if (!countdownEl) return;
        
        let timeStr = countdownEl.textContent.trim();
        let parts = timeStr.split(':').map(x => parseInt(x.trim()));
        if (parts.length !== 3 || parts.every(x => x === 0)) return;

        let totalSeconds = parts[0] * 3600 + parts[1] * 60 + parts[2];

        const interval = setInterval(() => {
            if (totalSeconds <= 0) {
                clearInterval(interval);
                countdownEl.textContent = '00 : 00 : 00';
                return;
            }
            totalSeconds--;

            let h = Math.floor(totalSeconds / 3600);
            let m = Math.floor((totalSeconds % 3600) / 60);
            let s = totalSeconds % 60;

            countdownEl.textContent = 
                String(h).padStart(2, '0') + ' : ' + 
                String(m).padStart(2, '0') + ' : ' + 
                String(s).padStart(2, '0');
        }, 1000);
    });
</script>
@endpush
