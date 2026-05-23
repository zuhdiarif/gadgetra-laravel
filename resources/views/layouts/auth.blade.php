<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Gadgetra | Sewa Gadget Premium</title>
    <meta name="description" content="@yield('description')">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <span class="page-label">@yield('page_label')</span>

    <div class="toast-container" id="toastContainer"></div>

    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-left-bg">
                <img src="{{ asset('assets/auth/loginimage.png') }}" alt="Gadgetra Welcome" loading="eager">
            </div>
            <div class="auth-left-content">
                <h1>Selamat Datang!</h1>
                <p>@yield('left_text')</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/Gadgetra Logo.png') }}" alt="Gadgetra Logo">
                </a>
            </div>
            <h2 class="auth-title">@yield('auth_title')</h2>

            <div class="alert-box" id="alertBox">
                <i class="fas fa-info-circle"></i>
                <span id="alertText"></span>
            </div>

            @yield('content')

            <div class="auth-divider">
                <span>Or</span>
            </div>

            <div class="social-buttons">
                <button type="button" class="btn-social btn-social-google" id="btnGoogle">
                    <img src="{{ asset('assets/icons/google 1.png') }}" alt="Google">
                    Lanjutkan dengan Google
                </button>
                <button type="button" class="btn-social btn-social-x" id="btnX">
                    <i class="fab fa-x-twitter"></i>
                    Lanjutkan dengan X
                </button>
                <button type="button" class="btn-social btn-social-facebook" id="btnFacebook">
                    <img src="{{ asset('assets/icons/facebook 1.png') }}" alt="Facebook">
                    Lanjutkan dengan Facebook
                </button>
            </div>

            <div class="auth-footer">
                @yield('footer_link')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
    @stack('scripts')
</body>
</html>
