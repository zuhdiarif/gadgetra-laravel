document.addEventListener('DOMContentLoaded', function () {
    const totalFormatted = localStorage.getItem('totalTagihanFormatted') || 'RpXXX.000';
    const totalTagihanDisplay = document.getElementById('totalTagihanDisplay');
    if (totalTagihanDisplay) {
        totalTagihanDisplay.textContent = totalFormatted;
    }

    const productName = localStorage.getItem('productName') || 'Sony Alpha IV';
    const productSlug = localStorage.getItem('productSlug') || 'sony-alpha-iv';
    const breadcrumbProductLink = document.getElementById('breadcrumbProductLink');
    if (breadcrumbProductLink) {
        breadcrumbProductLink.textContent = productName;
        breadcrumbProductLink.href = '/product/' + productSlug;
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
            const rawTotal = localStorage.getItem('totalTagihan') || '0';
            copyToClipboard(rawTotal, this);
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
            const rentalStart = localStorage.getItem('rentalStartDate') || '22/11/26';
            const rentalEnd = localStorage.getItem('rentalEndDate') || '23/11/26';
            const rentalQty = parseInt(localStorage.getItem('rentalQty')) || 1;
            const totalTagihan = parseInt(localStorage.getItem('totalTagihan')) || 0;
            const productName = localStorage.getItem('productName') || 'Sony Alpha IV';
            const productSlug = localStorage.getItem('productSlug') || 'sony-alpha-iv';
            const productImage = localStorage.getItem('productImage') || 'Sony Alpha A7 IV Camera.png';

            $.ajax({
                url: '/booking/store',
                type: 'POST',
                data: {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    start_date: rentalStart,
                    end_date: rentalEnd,
                    qty: rentalQty,
                    total_price: totalTagihan,
                    product_name: productName,
                    product_slug: productSlug,
                    product_image: productImage.split('/').pop()
                },
                success: function () {
                    window.location.href = '/booking/code';
                },
                error: function () {
                    window.location.href = '/booking/code';
                }
            });
        });
    }
});
