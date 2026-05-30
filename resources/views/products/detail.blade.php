@extends('layouts.app')

@section('title', 'Gadgetra - Detail ' . $product->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endpush

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span>{{ $product->name }}</span>
    </div>

    <div class="product-detail-layout">
        <div class="product-card-left">
            <div class="product-image-container">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </div>
            <h1 class="product-title">{{ $product->name }}</h1>
            <div class="product-price-tag">
                {{ $product->formatted_price }}<span>/hari</span>
            </div>
        </div>
        <div class="product-info-right">
            <div class="detail-tabs-header">
                <button class="tab-btn active" data-tab="spesifikasi">
                    <span>Spesifikasi</span>
                    <span>Produk</span>
                </button>
                <button class="tab-btn" data-tab="kondisi">
                    <span>Kondisi</span>
                    <span>Produk</span>
                </button>
                <button class="tab-btn" data-tab="ulasan">
                    <span>Ulasan</span>
                </button>
            </div>
            <div class="tab-content-panel">
                <div class="tab-panel active" id="panel-spesifikasi">
                    <p>{{ $product->description }}</p>
                    <ul>
                        @if(!empty($product->specifications))
                            @foreach($product->specifications as $key => $val)
                                <li><strong>{{ $key }}:</strong> {{ $val }}</li>
                            @endforeach
                        @else
                            <li>Spesifikasi tidak tersedia.</li>
                        @endif
                    </ul>
                </div>
                <div class="tab-panel" id="panel-kondisi">
                    <p>Semua unit rental kami melalui inspeksi kualitas 21-titik yang ketat untuk menjamin keandalan pemakaian di lapangan:</p>
                    <ul>
                        @if(!empty($product->conditions))
                            @foreach($product->conditions as $key => $val)
                                <li><strong>{{ $key }}:</strong> {{ $val }}</li>
                            @endforeach
                        @else
                            <li><strong>Kondisi Fisik:</strong> 95% ke atas, mulus.</li>
                            <li><strong>Fungsionalitas:</strong> 100% normal dan lancar.</li>
                            <li><strong>Kelengkapan Paket Sewa:</strong> Unit standar dengan charger.</li>
                        @endif
                    </ul>
                </div>
                <div class="tab-panel" id="panel-ulasan">
                    @if(!empty($reviews))
                        @foreach($reviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <span class="reviewer-name">{{ $review['name'] }}</span>
                                    <div class="review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review['rating'])
                                                <i class="fas fa-star" style="color: #FF9900;"></i>
                                            @else
                                                <i class="far fa-star" style="color: #ccc;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="review-text">"{{ $review['text'] }}"</p>
                            </div>
                        @endforeach
                    @else
                        <p>Belum ada ulasan untuk produk ini.</p>
                    @endif
                </div>
            </div>
            
            <div class="calendar-widget-container">
                <div class="calendar-header">
                    <span class="calendar-title" id="calendarMonthYear"></span>
                    <div class="calendar-nav-buttons">
                        <button class="calendar-nav-btn" id="prevMonthBtn" type="button"><i class="fas fa-chevron-left"></i></button>
                        <button class="calendar-nav-btn" id="nextMonthBtn" type="button"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="calendar-weekdays">
                    <span>Mo</span>
                    <span>Tu</span>
                    <span>We</span>
                    <span>Th</span>
                    <span>Fr</span>
                    <span>Sa</span>
                    <span>Su</span>
                </div>
                <div class="calendar-days-grid" id="calendarDays">
                </div>
            </div>

            <div class="bottom-rental-bar">
                <div class="rental-bar-item">
                    <span class="rental-bar-label">Jumlah Alat:</span>
                    <div class="quantity-control">
                        <button class="qty-btn" id="decreaseQty" type="button"><i class="fas fa-minus"></i></button>
                        <input type="number" id="qtyInput" value="1" min="1" max="10" readonly>
                        <button class="qty-btn" id="increaseQty" type="button"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <div class="rental-bar-item">
                    <span class="rental-bar-label">Tanggal Sewa:</span>
                    <div class="date-range-picker-bar">
                        <div class="date-input-wrapper">
                            <input type="text" id="startDateInput" placeholder="dd/mm/yy" readonly>
                            <i class="far fa-calendar-alt" id="startCalendarIcon"></i>
                        </div>
                        <span class="date-separator">-</span>
                        <div class="date-input-wrapper">
                            <input type="text" id="endDateInput" placeholder="dd/mm/yy" readonly>
                            <i class="far fa-calendar-alt" id="endCalendarIcon"></i>
                        </div>
                    </div>
                </div>

                <div class="rental-bar-actions">
                    <button class="btn-orange-cart" id="addToCartBtn" type="button" title="Masukkan Keranjang"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->price_per_day }}"
                            data-image="{{ $product->image_url }}"
                            data-slug="{{ $product->slug }}">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                    <button class="btn-direct-checkout" id="directCheckoutBtn" type="button" title="Sewa Sekarang"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->price_per_day }}"
                            data-image="{{ $product->image_url }}"
                            data-slug="{{ $product->slug }}">
                        Sewa Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="rentals-section">
        <h3>Recent Rentals</h3>
        <div class="rentals-grid">
            @if(!empty($recentRentals))
                @foreach($recentRentals as $rental)
                    <div class="rental-card">
                        <img src="{{ asset('assets/' . $rental['image']) }}" alt="{{ $rental['name'] }}">
                        <div class="rental-info">
                            <strong>{{ $rental['name'] }}</strong>
                            <span class="rental-status {{ $rental['status'] }}">{{ $rental['label'] }}</span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="notification-toast" id="notificationToast">
        <i class="fas fa-check-circle"></i>
        <span id="notificationMessage">Produk ditambahkan ke keranjang!</span>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/product-detail.js') }}"></script>
@endpush
