'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    if (!form) return;

    const fields = {
        email: document.getElementById('email'),
        password: document.getElementById('password')
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
        if (val.length > 0) {
            Auth.hideFieldError('password');
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        Auth.hideAlert();

        const rateCheck = Auth.rateLimiter.check('login', 5, 120000);
        if (!rateCheck.allowed) {
            Auth.showAlert(`Terlalu banyak percobaan login. Coba lagi dalam ${rateCheck.waitSec} detik.`, 'error');
            Auth.showToast('Akun terkunci sementara', 'error');
            return;
        }

        const email = fields.email.value.trim();
        const password = fields.password.value;
        const csrfTokenEl = document.getElementById('csrfToken');
        const csrfToken = csrfTokenEl ? csrfTokenEl.value : '';

        Auth.clearAllErrors(['email', 'password']);

        let hasError = false;

        if (!email || !Auth.isValidEmail(email)) {
            Auth.showFieldError('email', 'Email tidak valid');
            hasError = true;
        }

        if (!password || password.length < 1) {
            Auth.showFieldError('password', 'Password harus diisi');
            hasError = true;
        }

        if (hasError) {
            Auth.showToast('Periksa kembali form login', 'error');
            return;
        }

        Auth.setLoading('btnLogin', true);

        try {
            const result = await Auth.apiRequest('/login', {
                email: Auth.sanitizeInput(email),
                password: password,
                csrf_token: csrfToken
            });

            if (result.success) {
                Auth.showToast('Login berhasil! Mengarahkan...', 'success');
                Auth.showAlert('Login berhasil!', 'success');
                Auth.rateLimiter.reset('login');

                setTimeout(() => {
                    window.location.href = result.redirect || '/';
                }, 1000);
            } else {
                Auth.showAlert(result.message || 'Email atau password salah.', 'error');
                Auth.showToast('Login gagal', 'error');

                if (result.errors) {
                    Object.entries(result.errors).forEach(([field, msg]) => {
                        Auth.showFieldError(field, msg);
                    });
                }
            }
        } catch (err) {
            console.error('Login error:', err);
            Auth.showAlert('Terjadi kesalahan server. Silakan coba lagi nanti.', 'error');
            Auth.showToast('Kesalahan koneksi', 'error');
        } finally {
            Auth.setLoading('btnLogin', false);
        }
    });
});
