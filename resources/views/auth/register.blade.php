@extends('layouts.auth')

@section('title', 'Registrasi')
@section('description', 'Daftar akun Gadgetra untuk mulai menyewa gadget premium. Gratis dan mudah!')
@section('page_label', 'Sign-Up')
@section('left_text')
    Silahkan lakukan registrasi<br>terlebih dahulu
@endsection
@section('auth_title', 'Registrasi')

@section('content')
    <form class="auth-form" id="registerForm" novalidate autocomplete="off">
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
                    autocomplete="new-password"
                    maxlength="128"
                    required>
                <button type="button" class="password-toggle" id="togglePassword" aria-label="Tampilkan password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="password-strength" id="passwordStrength">
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
                <div class="strength-bar"></div>
            </div>
            <div class="strength-text" id="strengthText"></div>
            <div class="field-error" id="passwordError">
                <i class="fas fa-exclamation-circle"></i>
                <span></span>
            </div>
        </div>

        <div class="form-group">
            <label for="confirmPassword">Konfirmasi Password</label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    id="confirmPassword" 
                    name="confirm_password" 
                    placeholder="Konfirmasi password" 
                    autocomplete="new-password"
                    maxlength="128"
                    required>
                <button type="button" class="password-toggle" id="toggleConfirmPassword" aria-label="Tampilkan konfirmasi password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="field-error" id="confirmPasswordError">
                <i class="fas fa-exclamation-circle"></i>
                <span></span>
            </div>
        </div>

        <button type="submit" class="btn-submit" id="btnRegister">
            <span class="btn-text">Registrasi</span>
            <span class="spinner"></span>
        </button>
    </form>
@endsection

@section('footer_link')
    Punya akun? <a href="{{ route('login') }}">Login</a>
@endsection

@push('scripts')
    <script src="{{ asset('js/register.js') }}"></script>
@endpush
