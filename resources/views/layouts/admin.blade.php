<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Gadgetra')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
    <header class="admin-header">
        <a href="{{ route('admin.dashboard') }}" class="admin-logo">
            <img src="{{ asset('assets/Gadgetra Logo.png') }}" alt="Logo">
        </a>
        <div class="admin-profile-group">
            <div class="admin-profile">
                @auth
                    @if(Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar)))
                        <img class="admin-avatar" src="{{ asset(Auth::user()->avatar) }}" alt="Avatar">
                    @else
                        <img class="admin-avatar" src="{{ Auth::user()->avatar_url }}" alt="Avatar">
                    @endif
                @else
                    <img class="admin-avatar" src="https://ui-avatars.com/api/?name=Admin&background=002D72&color=fff" alt="Avatar">
                @endauth
                <span class="admin-icon-pill" title="Admin Role">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin</span>
                </span>
            </div>
            @auth
                <a href="#" class="admin-logout-btn" title="Keluar" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endauth
        </div>
    </header>

    <div class="admin-wrapper">
        <aside class="admin-sidebar-shell">
            <nav class="admin-sidebar-inner">
                <a href="{{ route('admin.dashboard') }}" class="admin-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.transactions') }}" class="admin-menu-item {{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
                    <i class="fas fa-list-alt"></i>
                    <span>Daftar Transaksi</span>
                </a>
                <a href="{{ route('admin.products') }}" class="admin-menu-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i>
                    <span>Manajemen Produk</span>
                </a>
            </nav>
        </aside>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>
