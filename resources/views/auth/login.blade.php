@extends('layouts.auth')

@section('title', 'Login')
@section('description', 'Masuk ke akun Gadgetra Anda untuk mulai menyewa gadget premium favorit.')
@section('page_label', 'Sign-In')
@section('left_text')
    Silahkan masuk ke akun Anda<br>terlebih dahulu
@endsection
@section('auth_title', 'Login')

@section('content')
    <form class="auth-form" id="loginForm" novalidate autocomplete="off">
        <input type="hidden" name="_token" id="csrfToken" value="{{ csrf_token() }}">

        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="Masukkan Email" 
                autocomplete="email"
                maxlength="50"
                required>
            <div class="field-error" id="emailError">
                <i class="fas fa-exclamation-circle"></i>
                <span></span>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Masukkan Password" 
                    autocomplete="current-password"
                    maxlength="128"
                    required>
                <button type="button" class="password-toggle" id="togglePassword" aria-label="Tampilkan password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="field-error" id="passwordError">
                <i class="fas fa-exclamation-circle"></i>
                <span></span>
            </div>
        </div>

        <button type="submit" class="btn-submit" id="btnLogin">
            <span class="btn-text">Login</span>
            <span class="spinner"></span>
        </button>
    </form>
@endsection

@section('footer_link')
    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
@endsection

@push('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endpush
