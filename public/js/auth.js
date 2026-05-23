'use strict';

const Auth = (() => {

    function generateCSRFToken() {
        const array = new Uint8Array(32);
        crypto.getRandomValues(array);
        return Array.from(array, b => b.toString(16).padStart(2, '0')).join('');
    }

    function initCSRFToken() {
        const tokenEl = document.getElementById('csrfToken');
        if (tokenEl) {
            if (!tokenEl.value) {
                const token = generateCSRFToken();
                tokenEl.value = token;
                sessionStorage.setItem('csrf_token', token);
            } else {
                sessionStorage.setItem('csrf_token', tokenEl.value);
            }
        }
    }

    function sanitize(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function showToast(message, type = 'info', duration = 4000) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            info: 'fa-info-circle'
        };

        toast.innerHTML = `
            <i class="fas ${icons[type] || icons.info}"></i>
            <span>${sanitize(message)}</span>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('removing');
            setTimeout(() => toast.remove(), 350);
        }, duration);
    }

    function showAlert(message, type = 'error') {
        const alertBox = document.getElementById('alertBox');
        const alertText = document.getElementById('alertText');
        if (!alertBox || !alertText) return;

        alertBox.className = `alert-box alert-${type} visible`;
        alertText.textContent = message;

        const icon = alertBox.querySelector('i');
        if (icon) {
            icon.className = type === 'success' 
                ? 'fas fa-check-circle' 
                : 'fas fa-exclamation-triangle';
        }
    }

    function hideAlert() {
        const alertBox = document.getElementById('alertBox');
        if (alertBox) {
            alertBox.classList.remove('visible');
        }
    }

    function showFieldError(fieldId, message) {
        const errorEl = document.getElementById(fieldId + 'Error');
        const inputEl = document.getElementById(fieldId);
        if (errorEl) {
            const span = errorEl.querySelector('span');
            if (span) span.textContent = message;
            errorEl.classList.add('visible');
        }
        if (inputEl) {
            inputEl.classList.add('input-error');
            inputEl.classList.remove('input-success');
        }
    }

    function hideFieldError(fieldId) {
        const errorEl = document.getElementById(fieldId + 'Error');
        const inputEl = document.getElementById(fieldId);
        if (errorEl) {
            errorEl.classList.remove('visible');
        }
        if (inputEl) {
            inputEl.classList.remove('input-error');
        }
    }

    function showFieldSuccess(fieldId) {
        const inputEl = document.getElementById(fieldId);
        if (inputEl) {
            inputEl.classList.remove('input-error');
            inputEl.classList.add('input-success');
        }
        hideFieldError(fieldId);
    }

    function clearAllErrors(fieldIds) {
        fieldIds.forEach(id => hideFieldError(id));
    }

    function isValidEmail(email) {
        const re = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;
        return re.test(email.trim());
    }

    function getPasswordStrength(password) {
        let score = 0;
        if (password.length >= 8) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        if (score <= 1) return { score, label: 'Lemah', level: 'weak' };
        if (score <= 2) return { score, label: 'Sedang', level: 'medium' };
        if (score <= 3) return { score, label: 'Kuat', level: 'strong' };
        return { score, label: 'Sangat Kuat', level: 'strong' };
    }

    function updatePasswordStrengthUI(password) {
        const bars = document.querySelectorAll('#passwordStrength .strength-bar');
        const textEl = document.getElementById('strengthText');
        if (!bars.length || !textEl) return;

        const strength = getPasswordStrength(password);

        bars.forEach((bar, i) => {
            bar.className = 'strength-bar';
            if (i < strength.score) {
                bar.classList.add('active', strength.level);
            }
        });

        if (password.length === 0) {
            textEl.textContent = '';
        } else {
            textEl.textContent = `Kekuatan: ${strength.label}`;
            textEl.style.color = strength.level === 'weak' ? '#E53935' 
                : strength.level === 'medium' ? '#FF9900' : '#43A047';
        }

        return strength;
    }

    function initPasswordToggles() {
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const wrapper = btn.closest('.password-wrapper');
                const input = wrapper.querySelector('input');
                const icon = btn.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fas fa-eye-slash';
                } else {
                    input.type = 'password';
                    icon.className = 'fas fa-eye';
                }
            });
        });
    }

    const rateLimiter = {
        attempts: {},
        
        check(action, maxAttempts = 5, windowMs = 60000) {
            const now = Date.now();
            if (!this.attempts[action]) {
                this.attempts[action] = [];
            }

            this.attempts[action] = this.attempts[action].filter(t => now - t < windowMs);

            if (this.attempts[action].length >= maxAttempts) {
                const oldestAttempt = this.attempts[action][0];
                const waitSec = Math.ceil((windowMs - (now - oldestAttempt)) / 1000);
                return { allowed: false, waitSec };
            }

            this.attempts[action].push(now);
            return { allowed: true };
        },

        reset(action) {
            delete this.attempts[action];
        }
    };

    function setLoading(btnId, isLoading) {
        const btn = document.getElementById(btnId);
        if (!btn) return;

        if (isLoading) {
            btn.classList.add('loading');
            btn.disabled = true;
        } else {
            btn.classList.remove('loading');
            btn.disabled = false;
        }
    }

    async function apiRequest(url, data) {
        const formData = new FormData();
        Object.entries(data).forEach(([key, val]) => {
            formData.append(key, val);
        });

        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        };

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            headers['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
        }

        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: headers,
            credentials: 'same-origin'
        });

        const json = await response.json().catch(() => null);

        if (response.status >= 500) {
            throw new Error(`HTTP Error: ${response.status}`);
        }

        if (!response.ok && json) {
            if (json.errors) {
                const firstKey = Object.keys(json.errors)[0];
                const msg = Array.isArray(json.errors[firstKey])
                    ? json.errors[firstKey][0]
                    : json.errors[firstKey];
                return { success: false, message: msg, errors: json.errors };
            }
            return { success: false, message: json.message || 'Terjadi kesalahan.' };
        }

        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }

        return json;
    }

    function sanitizeInput(str) {
        return str.trim().replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function init() {
        initCSRFToken();
        initPasswordToggles();

        document.querySelectorAll('.form-group input').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.closest('.form-group')?.classList.add('focused');
            });
            input.addEventListener('blur', () => {
                input.parentElement.closest('.form-group')?.classList.remove('focused');
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    return {
        showToast,
        showAlert,
        hideAlert,
        showFieldError,
        hideFieldError,
        showFieldSuccess,
        clearAllErrors,
        isValidEmail,
        getPasswordStrength,
        updatePasswordStrengthUI,
        setLoading,
        apiRequest,
        sanitize,
        sanitizeInput,
        rateLimiter
    };
})();
