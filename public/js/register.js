'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registerForm');
    if (!form) return;

    const fields = {
        email: document.getElementById('email'),
        password: document.getElementById('password'),
        confirmPassword: document.getElementById('confirmPassword')
    };

    fields.email.addEventListener('input', () => {
        const val = fields.email.value.trim();
        if (val.length > 0 && !Auth.isValidEmail(val)) {
            Auth.showFieldError('email', 'Format email tidak valid');
        } else if (val.length > 0) {
            Auth.showFieldSuccess('email');
        } else {
            Auth.hideFieldError('email');
        }
    });

    fields.password.addEventListener('input', () => {
        const val = fields.password.value;
        Auth.updatePasswordStrengthUI(val);

        if (val.length > 0 && val.length < 8) {
            Auth.showFieldError('password', 'Password minimal 8 karakter');
        } else if (val.length >= 8) {
            Auth.showFieldSuccess('password');
        } else {
            Auth.hideFieldError('password');
        }

        if (fields.confirmPassword.value.length > 0) {
            if (fields.confirmPassword.value !== val) {
                Auth.showFieldError('confirmPassword', 'Password tidak cocok');
            } else {
                Auth.showFieldSuccess('confirmPassword');
            }
        }
    });

    fields.confirmPassword.addEventListener('input', () => {
        const val = fields.confirmPassword.value;
        if (val.length > 0 && val !== fields.password.value) {
            Auth.showFieldError('confirmPassword', 'Password tidak cocok');
        } else if (val.length > 0) {
            Auth.showFieldSuccess('confirmPassword');
        } else {
            Auth.hideFieldError('confirmPassword');
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        Auth.hideAlert();

        const rateCheck = Auth.rateLimiter.check('register', 5, 60000);
        if (!rateCheck.allowed) {
            Auth.showAlert(`Terlalu banyak percobaan. Coba lagi dalam ${rateCheck.waitSec} detik.`, 'error');
            Auth.showToast('Terlalu banyak percobaan', 'error');
            return;
        }

        const email = fields.email.value.trim();
        const password = fields.password.value;
        const confirmPassword = fields.confirmPassword.value;
        const csrfTokenEl = document.getElementById('csrfToken');
        const csrfToken = csrfTokenEl ? csrfTokenEl.value : '';

        Auth.clearAllErrors(['email', 'password', 'confirmPassword']);

        let hasError = false;

        if (!email || !Auth.isValidEmail(email)) {
            Auth.showFieldError('email', 'Email tidak valid');
            hasError = true;
        }

        if (!password || password.length < 8) {
            Auth.showFieldError('password', 'Password minimal 8 karakter');
            hasError = true;
        }

        const strength = Auth.getPasswordStrength(password);
        if (password.length >= 8 && strength.score < 2) {
            Auth.showFieldError('password', 'Password terlalu lemah. Tambahkan huruf besar, angka, atau simbol.');
            hasError = true;
        }

        if (password !== confirmPassword) {
            Auth.showFieldError('confirmPassword', 'Password tidak cocok');
            hasError = true;
        }

        if (hasError) {
            Auth.showToast('Periksa kembali form registrasi', 'error');
            return;
        }

        Auth.setLoading('btnRegister', true);

        try {
            const result = await Auth.apiRequest('/register', {
                email: Auth.sanitizeInput(email),
                password: password,
                confirm_password: confirmPassword,
                csrf_token: csrfToken
            });

            if (result.success) {
                Auth.showToast('Registrasi berhasil! Mengarahkan ke halaman login...', 'success');
                Auth.showAlert('Registrasi berhasil!', 'success');

                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            } else {
                Auth.showAlert(result.message || 'Registrasi gagal. Silakan coba lagi.', 'error');
                Auth.showToast(result.message || 'Registrasi gagal', 'error');

                if (result.errors) {
                    Object.entries(result.errors).forEach(([field, msg]) => {
                        Auth.showFieldError(field, msg);
                    });
                }
            }
        } catch (err) {
            console.error('Register error:', err);
            Auth.showAlert('Terjadi kesalahan server. Silakan coba lagi nanti.', 'error');
            Auth.showToast('Kesalahan koneksi', 'error');
        } finally {
            Auth.setLoading('btnRegister', false);
        }
    });
});
