document.addEventListener('DOMContentLoaded', function () {
    const paymentRows = document.querySelectorAll('.payment-method-row');
    const noteTextarea = document.querySelector('.note-textarea');
    const noteCounter = document.querySelector('.note-counter');
    const payNowBtn = document.querySelector('.btn-pay-now');

    const isCart = localStorage.getItem('isCartCheckout') === 'true';
    const SERVICE_FEE = 2000;
    
    let totalSewa = 0;
    let rentalQty = 0;

    if (isCart) {
        const checkoutItems = JSON.parse(localStorage.getItem('checkoutItems') || '[]');
        const breadcrumbProductLink = document.getElementById('breadcrumbProductLink');
        if (breadcrumbProductLink) {
            breadcrumbProductLink.textContent = 'Keranjang';
            breadcrumbProductLink.href = '/cart';
        }

        const listContainer = document.getElementById('checkoutProductsList');
        if (listContainer) {
            listContainer.innerHTML = '';
            checkoutItems.forEach(item => {
                const durationDays = item.durationDays;
                const qty = item.qty;
                const price = item.productPrice;
                rentalQty += qty;
                totalSewa += price * qty * durationDays;

                const productHtml = `
                    <div style="margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px;">
                        <div class="product-item-title" style="font-size: 16px; font-weight: 700; color: #1a1a1a; margin-bottom: 10px;">${item.product_name}</div>
                        <div class="product-item-body" style="display: flex; gap: 15px; align-items: center;">
                            <div class="product-item-image" style="width: 70px; height: 70px; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #fff;">
                                <img src="${item.product_image}" alt="${item.product_name}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                            <div class="product-item-details" style="flex: 1;">
                                <div class="product-item-meta" style="font-size: 13px; color: #666; margin-bottom: 4px;">Masa Sewa : ${durationDays} Hari (${item.start_date} - ${item.end_date})</div>
                                <div class="product-item-price" style="font-size: 14px; font-weight: 600; color: #002d72; margin-bottom: 4px;">Rp${price.toLocaleString('id-ID')}/hari</div>
                                <div class="product-item-meta" style="font-size: 13px; color: #666;">Jumlah : ${qty} buah</div>
                            </div>
                        </div>
                    </div>
                `;
                listContainer.insertAdjacentHTML('beforeend', productHtml);
            });
        }
    } else {
        const PRICE_PER_DAY = parseInt(localStorage.getItem('productPrice')) || 300000;
        const productName = localStorage.getItem('productName') || 'Sony Alpha IV';
        const productImage = localStorage.getItem('productImage') || '/assets/products/Sony Alpha A7 IV Camera.png';
        const productSlug = localStorage.getItem('productSlug') || 'sony-alpha-iv';

        rentalQty = parseInt(localStorage.getItem('rentalQty')) || 1;
        const rentalDays = parseInt(localStorage.getItem('rentalDurationDays')) || 3;

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

        const displayMasaSewa = document.getElementById('displayMasaSewa');
        if (displayMasaSewa) {
            displayMasaSewa.textContent = 'Masa Sewa : ' + rentalDays + ' Hari';
        }
        const displayJumlah = document.getElementById('displayJumlah');
        if (displayJumlah) {
            displayJumlah.textContent = 'Jumlah : ' + rentalQty + ' buah';
        }

        totalSewa = PRICE_PER_DAY * rentalDays * rentalQty;
    }

    const totalTagihan = totalSewa + SERVICE_FEE;

    function formatRupiah(amount) {
        return 'Rp' + amount.toLocaleString('id-ID');
    }

    const summaryTotalSewaLabel = document.getElementById('summaryTotalSewaLabel');
    const summaryTotalSewaVal = document.getElementById('summaryTotalSewaVal');
    const summaryTotalTagihanVal = document.getElementById('summaryTotalTagihanVal');

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
