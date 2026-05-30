'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const isCart = localStorage.getItem('isCartCheckout') === 'true';
    const bookingCode = localStorage.getItem('bookingCode') || '';
    const bookingCodesStr = localStorage.getItem('bookingCodes');

    let qrData = bookingCode || 'TYZ10CH6U';
    let displayCode = bookingCode || 'TYZ10CH6U';

    if (isCart) {
        const checkoutItems = JSON.parse(localStorage.getItem('checkoutItems') || '[]');
        if (bookingCodesStr) {
            const codes = JSON.parse(bookingCodesStr);
            if (Array.isArray(codes) && codes.length > 0) {
                qrData = codes.join(',');
                displayCode = codes.join(' | ');
            }
        }

        const listContainer = document.getElementById('bookingProductsList');
        if (listContainer) {
            listContainer.innerHTML = '';
            checkoutItems.forEach(item => {
                const qty = item.qty;
                const price = item.productPrice;
                const durationDays = item.durationDays;
                const subtotal = price * qty * durationDays;
                const itemHtml = `
                    <div class="bc-product-row" style="margin-bottom: 20px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 20px; display: flex; gap: 15px;">
                        <img class="bc-product-img" src="${item.product_image.startsWith('/') ? item.product_image : '/assets/products/' + item.product_image}" alt="${item.product_name}" style="width: 70px; height: 70px; object-fit: contain; border-radius: 8px; border: 1px solid #e2e8f0; padding: 5px; background: #fff;">
                        <div class="bc-product-info" style="flex: 1;">
                            <div class="bc-product-name" style="font-size: 15px; font-weight: 700; color: #1a1a1a; margin-bottom: 6px;">${item.product_name}</div>
                            <div class="bc-product-meta" style="font-size: 12px; color: #666; margin-bottom: 2px;">Tanggal sewa: ${item.start_date} s/d ${item.end_date}</div>
                            <div class="bc-product-meta" style="font-size: 12px; color: #666; margin-bottom: 4px;">Jumlah : ${qty} buah</div>
                            <div class="bc-product-price" style="font-size: 14px; font-weight: 600; color: #002d72;">Rp${subtotal.toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                `;
                listContainer.insertAdjacentHTML('beforeend', itemHtml);
            });
        }
    } else {
        const rentalStart = localStorage.getItem('rentalStartDateDisplay') || localStorage.getItem('rentalStartDate') || '';
        const rentalEnd = localStorage.getItem('rentalEndDateDisplay') || localStorage.getItem('rentalEndDate') || '';
        const rentalQty = localStorage.getItem('rentalQty') || 'X';
        const totalFormatted = localStorage.getItem('totalTagihanFormatted') || 'Rp0';
        const productName = localStorage.getItem('productName') || 'Gadget';
        const productImage = localStorage.getItem('productImage') || '';

        const bcTanggalSewa = document.getElementById('bcTanggalSewa');
        if (bcTanggalSewa) {
            bcTanggalSewa.textContent = 'Tanggal sewa: ' + rentalStart + ' - ' + rentalEnd;
        }

        const bcJumlah = document.getElementById('bcJumlah');
        if (bcJumlah) {
            bcJumlah.textContent = 'Jumlah : ' + rentalQty + ' buah';
        }

        const bcTotalBiaya = document.getElementById('bcTotalBiaya');
        if (bcTotalBiaya) {
            bcTotalBiaya.textContent = totalFormatted;
        }

        const bcProductName = document.querySelector('.bc-product-name');
        if (bcProductName) {
            bcProductName.textContent = productName;
        }

        const bcProductImg = document.querySelector('.bc-product-img');
        if (bcProductImg && productImage) {
            bcProductImg.src = productImage.startsWith('/')
                ? productImage
                : '/assets/products/' + productImage;
            bcProductImg.alt = productName;
        }
    }

    const bcCodeEl = document.getElementById('bcBookingCode');
    if (bcCodeEl) {
        bcCodeEl.textContent = displayCode;
    }

    const bcQrImg = document.querySelector('.bc-qr-img');
    if (bcQrImg && qrData) {
        bcQrImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' + encodeURIComponent(qrData);
    }
});
