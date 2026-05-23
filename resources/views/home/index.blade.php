@extends('layouts.app')

@section('title', 'Gadgetra - Sewa Gadget Impianmu Tanpa Mahal')
@section('description', 'Nikmati teknologi terbaru mulai dari iPhone 15 Pro, MacBook M3, hingga PS5 dengan harga sewa harian yang sangat terjangkau.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/carousel.css') }}">
@endpush

@section('content')
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-image">
                <div class="hero-carousel" id="heroCarousel">
                    <div class="carousel-progress"></div>
                    <div class="carousel-track">
                        <div class="carousel-slide">
                            <span class="carousel-badge"><i class="fas fa-fire"></i> Best Seller</span>
                            <img src="{{ asset('assets/home/Collection of high-end gadgets including MacBook, iPhone, and Sony camera.png') }}" alt="Koleksi Gadget Premium">
                            <div class="carousel-slide-overlay">
                                <h3>Koleksi Gadget Premium</h3>
                                <p>Semua gadget terbaru tersedia untuk disewa kapanpun</p>
                            </div>
                        </div>
                        <div class="carousel-slide">
                            <span class="carousel-badge"><i class="fas fa-bolt"></i> Terbaru</span>
                            <img src="{{ asset('assets/products/iPhone 15 Pro Max Natural Titanium.png') }}" alt="iPhone 15 Pro Max">
                            <div class="carousel-slide-overlay">
                                <h3>iPhone 15 Pro Max</h3>
                                <p>A17 Pro Chip, Triple Camera 48MP, Titanium Frame</p>
                            </div>
                        </div>
                        <div class="carousel-slide">
                            <span class="carousel-badge"><i class="fas fa-laptop"></i> Workstation</span>
                            <img src="{{ asset('assets/products/MacBook Pro M3 Space Black.png') }}" alt="MacBook Pro M3">
                            <div class="carousel-slide-overlay">
                                <h3>MacBook Pro M3</h3>
                                <p>Liquid Retina XDR, 16GB RAM, Battery up to 22h</p>
                            </div>
                        </div>
                        <div class="carousel-slide">
                            <span class="carousel-badge"><i class="fas fa-camera"></i> Profesional</span>
                            <img src="{{ asset('assets/products/Sony Alpha A7 IV Camera.png') }}" alt="Sony Alpha A7 IV">
                            <div class="carousel-slide-overlay">
                                <h3>Sony Alpha A7 IV</h3>
                                <p>33MP Full Frame, 4K 60p, Real-time Eye AF</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-btn carousel-btn-prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="carousel-btn carousel-btn-next"><i class="fas fa-chevron-right"></i></button>
                    <div class="carousel-dots">
                        <button class="carousel-dot"></button>
                        <button class="carousel-dot"></button>
                        <button class="carousel-dot"></button>
                        <button class="carousel-dot"></button>
                    </div>
                </div>
            </div>
            <div class="hero-text">
                <h1>Sewa Gadget Impianmu Tanpa Mahal</h1>
                <p>Nikmati teknologi terbaru mulai dari iPhone 15 Pro, MacBook M3, hingga PS5 dengan harga sewa harian yang sangat terjangkau.</p>
                <a href="#katalog" class="btn-primary">Sewa Sekarang</a>
            </div>
        </div>
    </section>

    <section class="categories container">
        <div class="section-header">
            <h2>Kategori Populer</h2>
            <a href="#" class="view-all">Lihat Semua <i class="fas fa-chevron-right"></i></a>
        </div>
        <div class="category-grid">
            @foreach ($categories as $category)
                <a href="{{ route('home', ['category' => $category['name']]) }}#katalog" class="category-card" style="display: block; text-decoration: none; color: inherit;">
                    <img src="{{ asset('assets/' . $category['icon']) }}" alt="{{ $category['name'] }}">
                    <p>{{ $category['name'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    <section id="katalog" class="products container">
        <h2>Katalog Produk Terbaru</h2>
        <div class="product-grid">
            @foreach ($products as $product)
                <a href="{{ route('product.detail', $product->slug) }}" class="product-card product-card-link">
                    @if ($product->badge)
                        <span class="product-badge">{{ $product->badge }}</span>
                    @endif
                    <div class="product-image">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    </div>
                    <div class="product-info">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ $product->description }}</p>
                        <div class="product-meta">
                            <div class="stars">
                                @php
                                    $fullStars = floor($product->rating);
                                    $hasHalf = $product->rating - $fullStars >= 0.5;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $fullStars)
                                        <i class="fas fa-star" style="color: #FF9900;"></i>
                                    @elseif ($i == $fullStars + 1 && $hasHalf)
                                        <i class="fas fa-star-half-alt" style="color: #FF9900;"></i>
                                    @else
                                        <i class="far fa-star" style="color: #ccc;"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating">{{ number_format($product->rating, 1) }}</span>
                        </div>
                        <div class="product-footer">
                            <div class="price">
                                <span>MULAI DARI</span>
                                <strong>{{ $product->formatted_price }}<sub>/hari</sub></strong>
                            </div>
                            <span class="btn-sewa"><i class="fas fa-shopping-cart"></i> Sewa</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section class="features container">
        <div class="feature-grid">
            <div class="feature-item">
                <img src="{{ asset('assets/icons/iconcentang.png') }}" alt="Produk Original">
                <h3>Produk Original</h3>
                <p>Semua gadget dijamin 100% original dan dalam kondisi fisik prima.</p>
            </div>
            <div class="feature-item">
                <img src="{{ asset('assets/icons/iconmobil.png') }}" alt="Gratis Ongkir">
                <h3>Gratis Ongkir</h3>
                <p>Pengantaran dan penjemputan gratis untuk area Jabodetabek.</p>
            </div>
            <div class="feature-item">
                <img src="{{ asset('assets/icons/iconcallcenter.png') }}" alt="Dukungan 24/7">
                <h3>Dukungan 24/7</h3>
                <p>Tim support kami siap membantu kendala teknis kapanpun dibutuhkan.</p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/carousel.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@endpush
