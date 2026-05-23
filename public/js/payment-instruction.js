'use strict';

document.addEventListener('DOMContentLoaded', function () {
    function sanitizeText(str) {
        if (typeof str !== 'string') return '';
        return str.replace(/[<>"'&]/g, function (c) {
            return {'<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','&':'&amp;'}[c];
        });
    }

    const totalFormatted = sanitizeText(localStorage.getItem('totalTagihanFormatted') || 'Rp0');
    const totalTagihanDisplay = document.getElementById('totalTagihanDisplay');
    if (totalTagihanDisplay) {
        totalTagihanDisplay.textContent = totalFormatted;
    }

    const productName = sanitizeText(localStorage.getItem('productName') || 'Gadget');
    const productSlug = (localStorage.getItem('productSlug') || '').replace(/[^a-z0-9-]/g, '');
    const breadcrumbProductLink = document.getElementById('breadcrumbProductLink');
    if (breadcrumbProductLink) {
        breadcrumbProductLink.textContent = productName;
        if (/^[a-z0-9-]+$/.test(productSlug)) {
            breadcrumbProductLink.href = '/product/' + productSlug;
        }
    }

    const VA_NUMBER = '80732XXXXXXXX';

    let totalSeconds = 23 * 3600 + 59 * 60 + 10;

    const hoursEl = document.getElementById('countHours');
    const minutesEl = document.getElementById('countMinutes');
    const secondsEl = document.getElementById('countSeconds');

    function updateCountdown() {
        if (totalSeconds <= 0) {
            hoursEl.textContent = '00';
            minutesEl.textContent = '00';
            secondsEl.textContent = '00';
            clearInterval(countdownInterval);
            alert('Waktu pembayaran habis!');
            return;
        }

        const h = Math.floor(totalSeconds / 3600);
        const m = Math.floor((totalSeconds % 3600) / 60);
        const s = totalSeconds % 60;

        hoursEl.textContent = String(h).padStart(2, '0');
        minutesEl.textContent = String(m).padStart(2, '0');
        secondsEl.textContent = String(s).padStart(2, '0');

        totalSeconds--;
    }

    updateCountdown();
    const countdownInterval = setInterval(updateCountdown, 1000);

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(function () {
            const icon = btn.querySelector('i');
            icon.className = 'fas fa-check';
            btn.classList.add('copied');
            setTimeout(function () {
                icon.className = 'fas fa-copy';
                btn.classList.remove('copied');
            }, 2000);
        }).catch(function () {
            const temp = document.createElement('textarea');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            const icon = btn.querySelector('i');
            icon.className = 'fas fa-check';
            btn.classList.add('copied');
            setTimeout(function () {
                icon.className = 'fas fa-copy';
                btn.classList.remove('copied');
            }, 2000);
        });
    }

    const copyVaBtn = document.getElementById('copyVaBtn');
    if (copyVaBtn) {
        copyVaBtn.addEventListener('click', function () {
            copyToClipboard(VA_NUMBER, this);
        });
    }

    const copyTotalBtn = document.getElementById('copyTotalBtn');
    if (copyTotalBtn) {
        copyTotalBtn.addEventListener('click', function () {
            const rawTotal = parseInt(localStorage.getItem('totalTagihan') || '0', 10);
            copyToClipboard(String(rawTotal), this);
        });
    }

    const successModal = document.getElementById('successModal');
    const openBookingCodeBtn = document.getElementById('openBookingCodeBtn');

    setTimeout(function () {
        if (successModal) {
            successModal.classList.add('visible');
        }
    }, 5000);

    if (openBookingCodeBtn) {
        openBookingCodeBtn.addEventListener('click', function () {
            const rentalStart = sanitizeText(localStorage.getItem('rentalStartDate') || '');
            const rentalEnd = sanitizeText(localStorage.getItem('rentalEndDate') || '');
            const rentalStartDisplay = sanitizeText(localStorage.getItem('rentalStartDateDisplay') || rentalStart);
            const rentalEndDisplay = sanitizeText(localStorage.getItem('rentalEndDateDisplay') || rentalEnd);
            const rentalQty = Math.max(1, Math.min(10, parseInt(localStorage.getItem('rentalQty') || '1', 10)));
            const totalTagihan = Math.max(0, parseInt(localStorage.getItem('totalTagihan') || '0', 10));
            const slug = (localStorage.getItem('productSlug') || '').replace(/[^a-z0-9-]/g, '');

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) return;

            $.ajax({
                url: '/booking/store',
                type: 'POST',
                data: {
                    _token: csrfToken.getAttribute('content'),
                    start_date: rentalStart,
                    end_date: rentalEnd,
                    qty: rentalQty,
                    total_price: totalTagihan,
                    product_slug: slug,
                    product_name: productName,
                    product_image: sanitizeText(localStorage.getItem('productImage') || '').split('/').pop()
                },
                success: function (response) {
                    if (response && response.code) {
                        localStorage.setItem('bookingCode', response.code);
                    }
                    window.location.href = '/booking/code';
                },
                error: function (xhr) {
                    var msg = 'Booking gagal. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = 'Booking gagal: ' + xhr.responseJSON.message;
                    } else if (xhr.status === 500) {
                        msg = 'Terjadi kesalahan server. Silakan coba lagi.';
                    } else if (xhr.status === 404) {
                        msg = 'Produk tidak ditemukan. Silakan kembali ke halaman produk.';
                    }
                    alert(msg);
                }
            });
        });
    }
});
