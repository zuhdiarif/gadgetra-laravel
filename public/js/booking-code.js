'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const rentalStart = localStorage.getItem('rentalStartDateDisplay') || localStorage.getItem('rentalStartDate') || '';
    const rentalEnd = localStorage.getItem('rentalEndDateDisplay') || localStorage.getItem('rentalEndDate') || '';
    const rentalQty = localStorage.getItem('rentalQty') || 'X';
    const totalFormatted = localStorage.getItem('totalTagihanFormatted') || 'Rp0';
    const productName = localStorage.getItem('productName') || 'Gadget';
    const productImage = localStorage.getItem('productImage') || '';
    const bookingCode = localStorage.getItem('bookingCode') || '';

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

    const bcCodeEl = document.getElementById('bcBookingCode');
    if (bcCodeEl && bookingCode) {
        bcCodeEl.textContent = bookingCode;
    }
});
