@extends('layouts.app')

@section('title', 'Gadgetra - Pesanan Saya')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('profile.show') }}">Profile</a>
        <i class="fas fa-chevron-right"></i>
        <span>Pesanan Saya</span>
    </div>

    <div class="profile-layout">
        <aside class="profile-sidebar">
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    <img id="profile-avatar" src="{{ $user->avatar_url }}" alt="Profile Photo">
                </div>
                <h2 class="profile-name">{{ $user->Nama }}</h2>
                <p class="profile-since">Member sejak {{ $user->member_since }}</p>
                <a href="{{ route('profile.show') }}" class="btn-edit-profile" style="text-align: center; justify-content: center; text-decoration: none;">
                    <i class="fas fa-user"></i> Lihat Profil
                </a>
            </div>
        </aside>

        <section class="profile-main">
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>Pesanan Aktif</h3>
                </div>
                
                @if($activeRentals->isEmpty())
                    <p style="color: #666; font-size: 14px; text-align: center; padding: 20px 0;">Tidak ada pesanan aktif saat ini.</p>
                @else
                    <div class="rentals-grid" style="display: flex; flex-direction: column; gap: 15px;">
                        @foreach($activeRentals as $rental)
                            <div class="rental-card" style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 1px solid #E5E9F0; border-radius: 14px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <img src="{{ asset('assets/products/' . $rental['product_image']) }}" alt="{{ $rental['product_name'] }}" style="width: 60px; height: 60px; border-radius: 10px; object-fit: cover;">
                                    <div class="rental-info">
                                        <strong style="font-size: 15px; display: block;">{{ $rental['product_name'] }}</strong>
                                        <span class="rental-status active" style="color: #22C55E; font-size: 13px; font-weight: 500;">
                                            {{ $rental['status'] }} • Durasi: {{ $rental['start_date'] }} s/d {{ $rental['end_date'] }}
                                        </span>
                                        <span style="font-size: 12px; color: #888; display: block; margin-top: 4px;">Kode Booking: <strong>{{ $rental['code'] }}</strong> | Jumlah: {{ $rental['qty'] }} buah</span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: #002D72; font-size: 16px;">Rp{{ number_format($rental['total_price'], 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="info-card" style="margin-top: 24px;">
                <div class="info-card-header">
                    <i class="fas fa-history"></i>
                    <h3>Riwayat Sewa</h3>
                </div>

                @if($completedRentals->isEmpty())
                    <p style="color: #666; font-size: 14px; text-align: center; padding: 20px 0;">Belum ada riwayat transaksi sewa.</p>
                @else
                    <div class="rentals-grid" style="display: flex; flex-direction: column; gap: 15px;">
                        @foreach($completedRentals as $rental)
                            <div class="rental-card" style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 1px solid #E5E9F0; border-radius: 14px; opacity: 0.85;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <img src="{{ asset('assets/products/' . $rental['product_image']) }}" alt="{{ $rental['product_name'] }}" style="width: 60px; height: 60px; border-radius: 10px; object-fit: cover;">
                                    <div class="rental-info">
                                        <strong style="font-size: 15px; display: block;">{{ $rental['product_name'] }}</strong>
                                        <span class="rental-status returned" style="color: #666; font-size: 13px;">
                                            Selesai • Pengembalian terverifikasi
                                        </span>
                                        <span style="font-size: 12px; color: #888; display: block; margin-top: 4px;">Kode Booking: <strong>{{ $rental['code'] }}</strong></span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <strong style="color: #666; font-size: 16px;">Rp{{ number_format($rental['total_price'], 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
