document.addEventListener('DOMContentLoaded', function () {
    const paymentRows = document.querySelectorAll('.payment-method-row');
    const noteTextarea = document.querySelector('.note-textarea');
    const noteCounter = document.querySelector('.note-counter');
    const payNowBtn = document.querySelector('.btn-pay-now');

    const PRICE_PER_DAY = parseInt(localStorage.getItem('productPrice')) || 300000;
    const SERVICE_FEE = 2000;
    const productName = localStorage.getItem('productName') || 'Sony Alpha IV';
    const productImage = localStorage.getItem('productImage') || '/assets/products/Sony Alpha A7 IV Camera.png';
    const productSlug = localStorage.getItem('productSlug') || 'sony-alpha-iv';

    const rentalQty = parseInt(localStorage.getItem('rentalQty')) || 1;
    const rentalDays = parseInt(localStorage.getItem('rentalDurationDays')) || 3;
    const rentalStart = localStorage.getItem('rentalStartDate') || '';
    const rentalEnd = localStorage.getItem('rentalEndDate') || '';

    const displayMasaSewa = document.getElementById('displayMasaSewa');
    const displayJumlah = document.getElementById('displayJumlah');
    const summaryTotalSewaLabel = document.getElementById('summaryTotalSewaLabel');
    const summaryTotalSewaVal = document.getElementById('summaryTotalSewaVal');
    const summaryTotalTagihanVal = document.getElementById('summaryTotalTagihanVal');

    
    const breadcrumbProductLink = document.getElementById('breadcrumbProductLink');
    if (breadcrumbProductLink) {
        breadcrumbProductLink.textContent = productName;
        breadcrumbProductLink.href = '/product/' + productSlug;
    }
    const itemTitleEl = document.querySelector('.product-item-title');
    if (itemTitleEl) {
        itemTitleEl.textContent = productName;
    }
    const itemImageEl = document.querySelector('.product-item-image img');
    if (itemImageEl) {
        itemImageEl.src = productImage;
        itemImageEl.alt = productName;
    }
    const itemPriceEl = document.querySelector('.product-item-price');
    if (itemPriceEl) {
        itemPriceEl.textContent = 'Rp' + PRICE_PER_DAY.toLocaleString('id-ID') + '/hari';
    }

    if (displayMasaSewa) {
        displayMasaSewa.textContent = 'Masa Sewa : ' + rentalDays + ' Hari';
    }
    if (displayJumlah) {
        displayJumlah.textContent = 'Jumlah : ' + rentalQty + ' buah';
    }

    const totalSewa = PRICE_PER_DAY * rentalDays * rentalQty;
    const totalTagihan = totalSewa + SERVICE_FEE;

    function formatRupiah(amount) {
        return 'Rp' + amount.toLocaleString('id-ID');
    }

    if (summaryTotalSewaLabel) {
        summaryTotalSewaLabel.textContent = 'Total Sewa (' + rentalQty + ' Barang)';
    }
    if (summaryTotalSewaVal) {
        summaryTotalSewaVal.textContent = formatRupiah(totalSewa);
    }
    if (summaryTotalTagihanVal) {
        summaryTotalTagihanVal.textContent = formatRupiah(totalTagihan);
    }

    localStorage.setItem('totalTagihan', totalTagihan);
    localStorage.setItem('totalTagihanFormatted', formatRupiah(totalTagihan));

    paymentRows.forEach(row => {
        row.addEventListener('click', function () {
            paymentRows.forEach(r => r.classList.remove('active'));
            this.classList.add('active');
        });
    });

    if (noteTextarea && noteCounter) {
        noteTextarea.addEventListener('input', function () {
            const currentLength = this.value.length;
            noteCounter.textContent = `${currentLength}/200`;
        });
    }

    if (payNowBtn) {
        payNowBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const activePayment = document.querySelector('.payment-method-row.active');

            if (!activePayment) {
                alert('Silakan pilih metode pembayaran terlebih dahulu!');
                return;
            }

            const selectedMethod = activePayment.querySelector('.payment-method-text').textContent;
            localStorage.setItem('selectedPaymentMethod', selectedMethod);

            window.location.href = '/payment/instruction';
        });
    }
});
