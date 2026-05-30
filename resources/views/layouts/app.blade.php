<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gadgetra - Sewa Gadget Impianmu Tanpa Mahal')</title>
    <meta name="description" content="@yield('description', 'Gadgetra adalah platform penyewaan gadget premium terpercaya di Indonesia.')">
    @if(request()->routeIs('home'))
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('css/Profile.css') }}">
    @endif
    @stack('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        @if(request()->routeIs('home'))
            <nav class="container nav">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('assets/Gadgetra Logo.png') }}" alt="Gadgetra Logo">
                </a>
                <div class="nav-links">
                    <a href="{{ route('home', ['category' => 'Smartphone']) }}#katalog">Smartphone</a>
                    <a href="{{ route('home', ['category' => 'Laptop']) }}#katalog">Laptop</a>
                    <a href="{{ route('home', ['category' => 'Kamera']) }}#katalog">Kamera</a>
                    <a href="{{ route('home', ['category' => 'Konsol Game']) }}#katalog">Konsol Game</a>
                </div>
                <div class="search-bar">
                    <i class="fas fa-search" style="color: #ccc;"></i>
                    <input type="text" placeholder="Cari gadget impianmu...">
                </div>
                <div class="auth-buttons" style="display: flex; align-items: center; gap: 1rem;">
                    @auth
                        <a href="{{ route('cart.index') }}" class="cart-icon-btn" style="color: var(--primary-blue); font-size: 1.25rem; display: flex; align-items: center; position: relative; margin-right: 0.5rem;" title="Keranjang Belanja">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count-badge" style="position: absolute; top: -8px; right: -8px; background: #FF9900; color: white; font-size: 0.65rem; padding: 2px 5px; border-radius: 50%; font-weight: bold; min-width: 15px; text-align: center;">{{ \App\Models\Cart::where('user_id', Auth::user()->ID)->count() }}</span>
                        </a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="btn-login">Login</a>
                        <a href="{{ route('register') }}" class="btn-daftar">Daftar</a>
                    @endguest
                    @auth
                        <div class="profile-dropdown-wrapper">
                            <div class="profile-circle-nav" title="Menu Profil">
                                @if(Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)))
                                    <img src="{{ asset(Auth::user()->avatar) }}" alt="avatar">
                                @else
                                    <img src="{{ Auth::user()->avatar_url }}" alt="avatar">
                                @endif
                            </div>
                            <div class="profile-dropdown-menu">
                                <div class="dropdown-header">
                                    <div class="dropdown-avatar">
                                        @if(Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)))
                                            <img src="{{ asset(Auth::user()->avatar) }}" alt="avatar">
                                        @else
                                            <img src="{{ Auth::user()->avatar_url }}" alt="avatar">
                                        @endif
                                    </div>
                                    <div class="dropdown-user-info">
                                        <span class="user-name">{{ Auth::user()->Nama }}</span>
                                        <span class="user-email">{{ Auth::user()->Email }}</span>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('profile.show') }}" class="dropdown-item">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Profil Saya</span>
                                </a>
                                @if(!Auth::user()->isAdmin())
                                <a href="{{ route('rentals.index') }}" class="dropdown-item">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>Pesanan Saya</span>
                                </a>
                                @endif
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                        <i class="fas fa-user-shield"></i>
                                        <span>Admin Panel</span>
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item logout-trigger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Keluar</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('profile.show') }}" class="profile-circle-nav" title="Ke Profil Saya">
                            <i class="fas fa-user"></i>
                        </a>
                    @endauth
                </div>
            </nav>
        @else
            <nav class="container nav">
                <a href="{{ route('home') }}" class="logo-text">Gadgetra</a>
                <div class="nav-links">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="#">Rentals</a>
                    <a href="#">Support</a>
                </div>
                <div class="nav-right">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search gadgets...">
                    </div>
                    <div class="nav-icons">
                        <a href="#" class="icon-btn"><i class="fas fa-bell"></i></a>
                        @auth
                        <a href="{{ route('cart.index') }}" class="icon-btn" style="position: relative;" title="Keranjang Belanja">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count-badge" style="position: absolute; top: -5px; right: -5px; background: #FF9900; color: white; font-size: 0.65rem; padding: 2px 5px; border-radius: 50%; font-weight: bold; min-width: 15px; text-align: center;">{{ \App\Models\Cart::where('user_id', Auth::user()->ID)->count() }}</span>
                        </a>
                        @else
                        <a href="{{ route('cart.index') }}" class="icon-btn" title="Keranjang Belanja"><i class="fas fa-shopping-cart"></i></a>
                        @endauth
                        @auth
                            <div class="profile-dropdown-wrapper">
                                <div class="profile-btn {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                    <span>Profile</span>
                                    <div class="avatar-mini">
                                        @if(Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)))
                                            <img id="nav-avatar" src="{{ asset(Auth::user()->avatar) }}" alt="avatar">
                                        @else
                                            <img id="nav-avatar" src="{{ Auth::user()->avatar_url }}" alt="avatar">
                                        @endif
                                    </div>
                                </div>
                                <div class="profile-dropdown-menu">
                                    <div class="dropdown-header">
                                        <div class="dropdown-avatar">
                                            @if(Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)))
                                                <img src="{{ asset(Auth::user()->avatar) }}" alt="avatar">
                                            @else
                                                <img src="{{ Auth::user()->avatar_url }}" alt="avatar">
                                            @endif
                                        </div>
                                        <div class="dropdown-user-info">
                                            <span class="user-name">{{ Auth::user()->Nama }}</span>
                                            <span class="user-email">{{ Auth::user()->Email }}</span>
                                        </div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('profile.show') }}" class="dropdown-item">
                                        <i class="fas fa-user-circle"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    @if(!Auth::user()->isAdmin())
                                    <a href="{{ route('rentals.index') }}" class="dropdown-item">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>Pesanan Saya</span>
                                    </a>
                                    @endif
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                            <i class="fas fa-user-shield"></i>
                                            <span>Admin Panel</span>
                                        </a>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item logout-trigger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Keluar</span>
                                    </a>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn-login-blue">Login</a>
                            <a href="{{ route('register') }}" class="btn-daftar-blue">Daftar</a>
                        @endauth
                    </div>
                </div>
            </nav>
        @endif
        @if(request()->routeIs('profile.*'))
        <div class="mobile-nav">
            <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('home') }}#katalog" class="mobile-nav-item">
                <i class="fas fa-tablet-alt"></i>
                <span>Gadgets</span>
            </a>
            @auth
                <a href="{{ route('profile.show') }}" class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-item">
                    <i class="fas fa-user"></i>
                    <span>Login</span>
                </a>
            @endauth
            <a href="#" class="mobile-nav-item">
                <i class="fas fa-question-circle"></i>
                <span>Support</span>
            </a>
        </div>
        @endif
    </header>

    <main class="{{ request()->routeIs('home') ? '' : 'container' }}">
        @yield('content')
    </main>

    @if(request()->routeIs('home'))
    <footer>
        <div class="container footer-content">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('assets/Gadgetra Logo.png') }}" alt="Gadgetra Logo">
                </a>
                <p>Gadgetra adalah platform penyewaan gadget premium terpercaya di Indonesia. Kami menghadirkan teknologi terbaru ke genggaman Anda.</p>
                <div class="social-links">
                    <a href="#" class="social-icon"><i class="fas fa-share-alt"></i></a>
                    <a href="#" class="social-icon"><i class="fas fa-globe"></i></a>
                    <a href="#" class="social-icon"><i class="fas fa-calendar"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h4>Layanan</h4>
                <ul>
                    <li><a href="#">Sewa Smartphone</a></li>
                    <li><a href="#">Sewa Laptop</a></li>
                    <li><a href="#">Sewa Konsol Game</a></li>
                    <li><a href="#">Sewa Kamera &amp; Lensa</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="#">Cara Menyewa</a></li>
                    <li><a href="#">Syarat &amp; Ketentuan</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Pusat Bantuan</a></li>
                </ul>
            </div>
            <div class="footer-links contact-info">
                <h4>Hubungi Kami</h4>
                <ul>
                    <li><i class="fas fa-map-marker-alt"></i> Jl. Kawi no 1077, Malang, Jawa Timur</li>
                    <li><i class="fas fa-phone"></i> +62 812-3456-7890</li>
                    <li><i class="fas fa-envelope"></i> hello@gadgetra.id</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Gadgetra. All rights reserved. Premium Gadget Experience.</p>
        </div>
    </footer>
    @else
    <footer>
        <div class="container footer-content">
            <div class="footer-brand">
                <span class="logo-text footer-logo">Gadgetra</span>
                <p>Platform penyewaan gadget premium terpercaya di Indonesia.</p>
            </div>
            <div class="footer-links">
                <h4>Layanan</h4>
                <ul>
                    <li><a href="#">Sewa Smartphone</a></li>
                    <li><a href="#">Sewa Laptop</a></li>
                    <li><a href="#">Sewa Kamera</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Bantuan</h4>
                <ul>
                    <li><a href="#">Cara Menyewa</a></li>
                    <li><a href="#">Syarat &amp; Ketentuan</a></li>
                    <li><a href="#">Pusat Bantuan</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Hubungi Kami</h4>
                <ul>
                    <li><i class="fas fa-phone"></i> +62 812-3456-7890</li>
                    <li><i class="fas fa-envelope"></i> hello@gadgetra.id</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Gadgetra. All rights reserved.</p>
        </div>
    </footer>
    @endif

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>
